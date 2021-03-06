<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Resource;

use Illuminate\Auth\AuthManager;
use Illuminate\Database\DatabaseManager;
// use Illuminate\Events\Dispatcher;

use Carghaez\Larapi\Resource\Exception\ResourceNotFoundException;

/**
 * ResourceService.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ResourceService
{
    private $auth;
    private $database;
    private $repository;

    public function __construct(
        AuthManager $auth,
        DatabaseManager $database,
        $repository
    ) {
        $this->auth = $auth;
        $this->database = $database;
        $this->repository = new $repository;
    }

    public function getAll($options = [])
    {
        return $this->repository->get($options);
    }

    public function getById($resourceId, array $options = [])
    {
        $resource = $this->getRequestedResource($resourceId, $options);

        return $resource;
    }

    public function create($data)
    {
        $resource = $this->repository->create($data);
        // $this->dispatcher->fire(new ResourceWasCreated($resource));
        return $resource;
    }

    public function update($resourceId, array $data)
    {
        $resource = $this->getRequestedResource($resourceId);
        $this->repository->update($resource, $data);
        // $this->dispatcher->fire(new ResourceWasUpdated($resource));
        return $resource;
    }

    public function delete($resourceId)
    {
        $resource = $this->getRequestedResource($resourceId);
        $this->repository->delete($resourceId);
        // $this->dispatcher->fire(new ResourceWasDeleted($resource));
    }

    private function getRequestedResource($resourceId, array $options = [])
    {
        $resource = $this->repository->getById($resourceId, $options);

        if (is_null($resource)) {
            throw new ResourceNotFoundException($this->repository->getModelName());
        }

        return $resource;
    }
}
