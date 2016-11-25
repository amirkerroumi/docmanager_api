<?php

namespace App\Http\Controllers;

use League\OAuth2\Server\Exception\OAuthServerException;
use Validator;

use Dusterio\LumenPassport\Http\Controllers\AccessTokenController;

use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use Dusterio\LumenPassport\LumenPassport;

use App\Exceptions\DocManagerException;

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
//        $response = $this->withErrorHandling(function () use ($request) {
//            return $this->server->respondToAccessTokenRequest($request, new Psr7Response);
//        });

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

            return $response;
        }
        catch(\Exception $e)
        {
            if($e instanceof OAuthServerException)
            {
                switch($e->getErrorType())
                {
                    case "invalid_credentials":
                        throw new DocManagerException(DocManagerException::INVALID_USER_CREDENTIALS, $e->getHttpStatusCode());
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
                        throw new DocManagerException(DocManagerException::INVALID_REQUEST, $e->getHttpStatusCode(), "Invalid request: ".$e->getHint());
                        break;
                    default:
                        throw new DocManagerException(DocManagerException::FAILED_ACCESS_TOKEN_ISSUING, $e->getHttpStatusCode());

                }
            }
        }





    }
}
