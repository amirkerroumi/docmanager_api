<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use League\OAuth2\Server\Exception\OAuthServerException;
use Validator;

use Dusterio\LumenPassport\Http\Controllers\AccessTokenController;

use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use Dusterio\LumenPassport\LumenPassport;

use App\Exceptions\DocManagerException;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocManagerAccessTokenController extends AccessTokenController
{
    /**
     * Authorize a client to access the user's account.
     *
     * @param  ServerRequestInterface  $request
     * @return Response
     */
    public function issueToken(ServerRequestInterface $request)
    {
        /*
         * OAuth uses the username field for the authentication even though
         * the database column is called 'email'
         * The input parameter is called 'email' instead of 'username' in order to be able to use
         * the Laravel Validator object and check that the email is unique
         */
        $parsedBody = $request->getParsedBody();
        $parsedBody['username'] = $parsedBody['email'];
        unset($parsedBody['email']);
        $request=$request->withParsedBody($parsedBody);
        try
        {
            $response = $this->server->respondToAccessTokenRequest($request, new Psr7Response);

            $payload = json_decode($response->getBody()->__toString(), true);

            if (isset($payload['access_token'])) {
                $tokenId = $this->jwt->parse($payload['access_token'])->getClaim('jti');
                $token = $this->tokens->find($tokenId);

                if ($token->client->firstParty() && LumenPassport::$allowMultipleTokens) {
                    // We keep previous tokens for password clients
                } else {
                    $this->revokeOrDeleteAccessTokens($token, $tokenId);
                }
            }

            if ($response instanceof PsrResponseInterface) {
                $response = (new HttpFoundationFactory)->createResponse($response);
            } elseif (! $response instanceof SymfonyResponse) {
                $response = new Response($response);
            } elseif ($response instanceof BinaryFileResponse) {
                $response = $response->prepare(Request::capture());
            }
            $response = json_decode($response->getContent(), true);
            $data['access_token'] = $response['access_token'];
            $data['expires_at'] = microtime(true) + $response['expires_in'];
            return docmanager_response()->success($data);
        }
        catch(\Exception $e)
        {
            if($e instanceof OAuthServerException)
            {
                switch($e->getErrorType())
                {
                    case "invalid_credentials":
                        $userMessages['email'] = [$e->getMessage()];
                        $userMessages['password'] = [$e->getMessage()];
                        throw new DocManagerException(DocManagerException::INVALID_USER_CREDENTIALS, $e->getHttpStatusCode(), null, null, null, true, $userMessages);
                        break;
                    case "unsupported_grant_type":
                        $requestContent = (array) $request->getParsedBody();
                        if(isset($requestContent['grant_type']) && $requestContent['grant_type'] != "password")
                        {
                            throw new DocManagerException(DocManagerException::UNSUPPORTED_GRANT_TYPE, $e->getHttpStatusCode());
                        }
                        else
                        {
                            throw new DocManagerException(DocManagerException::INCORRECT_CONTENT_TYPE, $e->getHttpStatusCode());
                        }
                        break;
                    case "invalid_client":
                        throw new DocManagerException(DocManagerException::INVALID_CLIENT, $e->getHttpStatusCode());
                        break;
                    case "invalid_request":
                        throw new DocManagerException(DocManagerException::INVALID_REQUEST, $e->getHttpStatusCode(), "", $e->getHint());
                        break;
                    default:
                        throw new DocManagerException(DocManagerException::FAILED_ACCESS_TOKEN_ISSUING, $e->getHttpStatusCode());

                }
            }
        }





    }
}
