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

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * ModelNameNotFoundException.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ModelNameNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(500, 'the var $model must be declared in Carghaez\Larapi\Resource\ResourceController children.');
    }
}
