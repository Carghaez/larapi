<?php

namespace Carghaez\Larapi\Resource;

class ResourceInfo
{
    protected $table;
    protected $name;
    protected $class;
    protected $model;

    /**
    * Returns a lowercase string of class name without namespaces
    */
    private function get_strlower_class($obj) {
        $classname = get_class($obj);

        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return strtolower($classname);
    }

    public function __construct($resourceClass)
    {
        $this->setModel($resourceClass);
    }

    public function setModel($resourceClass)
    {
        $this->class = $resourceClass;
        $this->model = new $resourceClass;
        $this->name = $this->get_strlower_class($this->model);
        $this->table = $this->model->getTable();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getModel()
    {
        return new $this->class;
    }

    public function getCreateRules()
    {
        return $this->model->getCreateRules();
    }

    public function getUpdateRules()
    {
        return $this->model->getUpdateRules();
    }
}
