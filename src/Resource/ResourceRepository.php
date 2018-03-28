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
    // 0 = insensitive case
    // 1 = lower case
    // 2 = upper case
    protected function str_random_column_unique($key, $length, $flagCase = 0)
    {
        $value = '';
        do {
            $value = str_random($length);
            switch ($flagCase) {
                case 1:
                    $value = strtolower($value);
                    break;
                case 2:
                    $value = strtoupper($value);
                    break;
            }
        } while ($this->getWhere($key, $value)->isNotEmpty());
        return $value;
    }

    public function getModel()
    {
        return (app()->make(ResourceInfo::class))->getModel();
    }

    public function getModelName()
    {
        return (app()->make(ResourceInfo::class))->getName();
    }

    public function getUpdateExcludedParams()
    {
        return (app()->make(ResourceInfo::class))->getUpdateExcludedParams();
    }

    public function excludeParamsFromData(array $data)
    {
        return array_diff_key($data, array_flip($this->getUpdateExcludedParams()));
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
