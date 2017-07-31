<?php

namespace Carghaez\Larapi\Resource;

use Optimus\Genie\Repository;
use Carghaez\Larapi\Resource\ResourceInfo;

class ResourceRepository extends Repository
{
    protected function str_random_column_unique($key, $length)
    {
        $value = '';
        do {
            $value = str_random($length);
        } while($this->getWhere($key, $value)->isNotEmpty());
        return $value;
    }

    public function getModel() {
        return (app()->make(ResourceInfo::class))->getModel();
    }

    public function getModelName() {
        return (app()->make(ResourceInfo::class))->getName();
    }

    public function create(array $data)
    {
        $resource = $this->getModel();
        $resource->fill($data);
        $resource->save();
        return $resource;
    }

    public function update($resource, array $data)
    {
        $resource->fill($data);
        $resource->save();
        return $resource;
    }
}
