<?php
/**
 * Created by PhpStorm.
 * User: a_kerroumi
 * Date: 16/11/2016
 * Time: 07:16
 */

namespace App\Exceptions;


class DocManagerException extends \Exception
{
    const INVALID_ACCESS_TOKEN = 1;
    const EXPIRED_ACCESS_TOKEN = 2;
    const REVOKED_ACCESS_TOKEN = 3;
    const FAILED_AUTHENTICATION_VIA_TOKEN = 4;
    const MISSING_ACCESS_TOKEN = 5;
    const INVALID_USER_CREDENTIALS = 6;
    const UNSUPPORTED_GRANT_TYPE = 7;
    const INVALID_CLIENT = 8;
    const INVALID_REQUEST = 9;
    const INCORRECT_CONTENT_TYPE = 10;
    const FAILED_ACCESS_TOKEN_ISSUING = 11;


    protected $customMessage;
    protected $customCode;
    protected $customMessages = [
        1 => "Invalid Access Token",
        2 => "Expired Access Token",
        3 => "Revoked Access Token",
        4 => "The user authentication via bearer token has failed",
        5 => "Bearer access token parameter is missing from the Authorization header",
        6 => "The user credentials were incorrect. Check the 'username' and 'password' parameters",
        7 => "Unsupported grant type. (grant_type must be password)",
        8 => "Client application authentication failed. Check the 'client_id' and 'client_secret' parameters",
        9 => "Invalid request. Check request parameters",
        10 => "Incorrect HTTP Content-Type (Content must be of type: x-www-form-urlencoded)",
        11 => "The attempt to issue an access token has failed"

    ];
    protected $httpMessages = [
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];

    public function __construct($customCode = 0, $httpCode = 500, $customMessage = null, $previousException = null)
    {
        $this->customCode = $customCode;
        if($customMessage)
        {
            $this->customMessage = $customMessage;
        }
        else
        {
            $this->setCustomMessage($customCode);
        }
        parent::__construct($this->getHttpMessage($httpCode), $httpCode, $previousException);
    }

    public function getHttpMessage($httpCode)
    {
        return $this->httpMessages[$httpCode];
    }

    public function getCustomMessage()
    {
        return $this->customMessage;
    }

    public function getCustomCode()
    {
        return $this->customCode;
    }

    public function setCustomMessage($customCode)
    {
        $this->customMessage = $this->customMessages[$customCode];
    }
}