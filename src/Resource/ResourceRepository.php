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

use Optimus\Genie\Repository;
use Carghaez\Larapi\Resource\ResourceInfo;

/**
 * ResourceRepository.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ResourceRepository extends Repository
{
    protected function str_random_column_unique($key, $length)
    {
        $value = '';
        do {
            $value = str_random($length);
        } while ($this->getWhere($key, $value)->isNotEmpty());
        return $value;
    }

    public function getModel() {
        return (app()->make(ResourceInfo::class))->getModel();
    }

    public function getModelName() {
        return (app()->make(ResourceInfo::class))->getName();
    }

    public function getUpdateExcludedParams()
    {
        return (app()->make(ResourceInfo::class))->getUpdateExcludedParams();
    }

    public function excludeParamsFromData(array $data)
    {
        return array_intersect_key($data, array_reverse($this->getUpdateExcludedParams()));
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
        if (is_array($this->getUpdateExcludedParams())) {
            $data = $this->excludeParamsFromData($data);
        }
        $resource->fill($data);
        $resource->save();
        return $resource;
    }
}
