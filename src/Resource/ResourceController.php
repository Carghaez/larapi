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

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use Optimus\Bruno\LaravelController as BaseController;

use Carghaez\Larapi\Resource\ResourceService;
use Carghaez\Larapi\Resource\ResourceRepository;
use Carghaez\Larapi\Resource\ResourceInfo;
use Carghaez\Larapi\Resource\Exception\ModelNameNotFoundException;

/**
 * ResourceController.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ResourceController extends BaseController
{
    protected $model;
    protected $repository;
    protected $service;
    protected $resourceInfo;

    protected function validateRequest(Request $request, $validationRules)
    {
        if (!is_array($validationRules)) {
            return;
        }

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            throw new UnprocessableEntityHttpException($validator->errors()->toJson());
        }
    }

    /**
     * Create a raw response
     * @param  mixed  $data
     * @param  integer $statusCode
     * @param  array  $headers
     * @return Illuminate\Http\Response
     */
    protected function responseRaw($data, $statusCode = 200, array $headers = [])
    {
        if ($data instanceof Arrayable && !$data instanceof JsonSerializable) {
            $data = $data->toArray();
        }

        return new Response($data, $statusCode, $headers);
    }

    public function __construct()
    {
        if (!$this->model) {
            throw new ModelNameNotFoundException();
        }
        app()->when(ResourceInfo::class)
            ->needs('$resourceClass')
            ->give($this->model);
        if ($this->repository) {
            app()->when(ResourceService::class)
                ->needs('$repository')
                ->give($this->repository);
        } else {
            app()->when(ResourceService::class)
                ->needs('$repository')
                ->give(ResourceRepository::class);
        }
        $this->resourceInfo = app()->make(ResourceInfo::class);
        $this->service = app()->make(ResourceService::class);
    }

    public function getAll()
    {
        $resourceOptions = $this->parseResourceOptions();

        $data = $this->service->getAll($resourceOptions);
        $parsedData = $this->parseData($data, $resourceOptions, $this->resourceInfo->getTable());

        return $this->response($parsedData[$this->resourceInfo->getTable()]);
    }

    public function getById($resourceId)
    {
        $resourceOptions = $this->parseResourceOptions();

        $data = $this->service->getById($resourceId, $resourceOptions);
        $parsedData = $this->parseData($data, $resourceOptions, $this->resourceInfo->getName());

        return $this->response($parsedData[$this->resourceInfo->getName()]);
    }

    public function create(Request $request)
    {
        $this->validateRequest($request, $this->resourceInfo->getCreateRules());
        $data = $request->input($this->resourceInfo->getName(), []);
        return $this->response($this->service->create($data), 201);
    }

    public function update($resourceId, Request $request)
    {
        $this->validateRequest($request, $this->resourceInfo->getUpdateRules());
        $data = $request->input($this->resourceInfo->getName(), []);
        return $this->response($this->service->update($resourceId, $data));
    }

    public function delete($resourceId)
    {
        return $this->response($this->service->delete($resourceId));
    }
}
