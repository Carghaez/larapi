<?php

namespace Carghaez\Larapi\Resource;

use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Optimus\Bruno\LaravelController as BaseController;

use Carghaez\Larapi\Resource\ResourceService;
use Carghaez\Larapi\Resource\ResourceInfo;
use Carghaez\Larapi\Resource\Exception\ModelNameNotFoundException;

class ResourceController extends BaseController
{
    protected $model;
    protected $service;
    protected $resourceInfo;

    protected function validateRequest(Request $request, $validationRules)
    {
        if (!is_array($validationRules)) {
            return;
        }

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            throw new HttpException(401, json_encode($validator->errors()->all()));
        }
    }

    public function __construct()
    {
        if (!$this->model) {
            throw new ModelNotFoundException();
        }
        app()->when(ResourceInfo::class)
            ->needs('$resourceClass')
            ->give($this->model);
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
