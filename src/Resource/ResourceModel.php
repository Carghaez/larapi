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

use Illuminate\Database\Eloquent\Model;

/**
 * ResourceModel.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ResourceModel extends Model
{
    protected $create_rules;
    protected $update_rules;
    protected $update_excluded_params;

    public function getCreateRules()
    {
        return $this->create_rules;
    }

    public function getUpdateRules()
    {
        return $this->update_rules;
    }

    public function getUpdateExcludedParams()
    {
        return $this->update_excluded_params;
    }
}
