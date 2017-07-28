<?php

namespace Carghaez\Larapi\Resource\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ModelNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(500, 'the var $model must be declared in Carghaez\Larapi\Resource\ResourceController children.');
    }
}
