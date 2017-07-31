<?php

namespace Carghaez\Larapi\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class InvalidCredentialsException extends UnauthorizedHttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        if (!$message) {
            $message = 'Invalid Credentials';
        }
        parent::__construct('', $message, $previous, $code);
    }
}
