<?php

namespace Carghaez\Larapi\Resource;

use Illuminate\Database\Eloquent\Model;

class ResourceModel extends Model
{
    protected $create_rules;
    protected $update_rules;

    public function getCreateRules()
    {
        return $this->create_rules;
    }

    public function getUpdateRules()
    {
        return $this->update_rules;
    }
}
