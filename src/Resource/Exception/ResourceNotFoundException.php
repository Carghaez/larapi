<?php

namespace Carghaez\Larapi\Resource\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceNotFoundException extends NotFoundHttpException
{
    public function __construct($resource)
    {
        parent::__construct("The {$resource} was not found.");
    }
}
