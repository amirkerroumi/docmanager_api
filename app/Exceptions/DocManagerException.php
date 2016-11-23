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
    const AUTHENTICATION_PROBLEM = 1;


    protected $customMessage;
    protected $customCode;
    protected $customMessages = [
        1 => "Authentication problem"
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

    public function __construct($customCode = 0, $customMessage = null, $httpCode = 500, $previousException = null)
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