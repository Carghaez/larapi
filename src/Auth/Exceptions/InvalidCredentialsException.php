<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * InvalidCredentialsException.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class InvalidCredentialsException extends UnauthorizedHttpException
{
    /**
     * Constructor.
     *
     * @param string     $message   The internal exception message
     * @param \Exception $previous  The previous exception
     * @param int        $code      The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        if (!$message) {
            $message = 'Invalid Credentials';
        }
        parent::__construct('', $message, $previous, $code);
    }
}
