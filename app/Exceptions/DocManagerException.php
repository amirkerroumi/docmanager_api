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

    protected $errorType;
    protected $customCode;
    protected $customHint;
    protected $customMessages = [
        1 => "Invalid access token",
        2 => "Expired access token",
        3 => "Revoked access token",
        4 => "Failed user authentication via token",
        5 => "Required access token",
        6 => "Invalid user credentials",
        7 => "Unsupported grant type",
        8 => "Failed client authentication",
        9 => "Invalid request",
        10 => "Incorrect HTTP Content-Type",
        11 => "Failed access token request"

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

    protected $customHints = [
        4 => "The user authentication via bearer token has failed",
        5 => "Bearer access token parameter is missing from the Authorization header",
        6 => "Check the 'username' and 'password' parameters",
        7 => "Grant type must be password",
        8 => "Check the 'client_id' and 'client_secret' parameters in access token request",
        9 => "Check the request parameters",
        10 => "Content must be of type: x-www-form-urlencoded"

    ];

    public function __construct($customCode = 0, $httpCode = 500, $customMessage = null, $customHint = null, $previousException = null, $userError = false)
    {
        $errorType = $userError ? "docmanager_user_error" : "docmanager_api_error";
        $this->setErrorType($errorType);
        $this->setCustomCode($customCode);
        $this->setCustomHint($customHint);
        if(!$customMessage)
        {
            if(array_key_exists($this->customCode, $this->customMessages))
            {
                $customMessage = $this->customMessages[$this->customCode];
            }
            else if(array_key_exists($httpCode, $this->httpMessages))
            {
                $customMessage = $this->httpMessages[$httpCode];
            }
            else
            {
                $customMessage = "";
            }
        }
        parent::__construct($customMessage, $httpCode, $previousException);
    }

    public function getCustomCode()
    {
        return $this->customCode;
    }

    public function setCustomCode($customCode)
    {
        $this->customCode = $customCode;
    }

    public function getCustomHint()
    {
        return $this->customHint;
    }

    public function setCustomHint($customHint)
    {
        if($customHint)
        {
            $this->customHint = $customHint;
        }
        else if(array_key_exists($this->customCode, $this->customHints))
        {
            $this->customHint = $this->customHints[$this->customCode];
        }
        else
        {
            $this->customHint = "";
        }
    }

    public function setErrorType($errorType)
    {
        $this->errorType = $errorType;
    }

    public function getErrorType()
    {
        return $this->errorType;
    }
}