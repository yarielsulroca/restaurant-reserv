<?php

namespace App\Services;

abstract class BaseService
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract protected function setModel();

    protected function getModel()
    {
        return $this->model;
    }
}
