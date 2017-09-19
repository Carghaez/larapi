<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Resource\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ResourceNotFoundException.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ResourceNotFoundException extends NotFoundHttpException
{
    public function __construct($resource)
    {
        parent::__construct("The {$resource} was not found.");
    }
}
