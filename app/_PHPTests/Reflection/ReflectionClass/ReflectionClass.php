<?php

class Request
{
    public $methodName;

    public function __construct()
    {
    }

    public function getMethod()
    {
        return static::$methodName;
    }
}

class FormRequest extends Request
{
    private $params = [];

    public function __construct(string $methodName)
    {
        $this->methodName = $methodName;
    }

    public function getParams()
    {
        return $this->params;
    }
}


$reflection = new ReflectionClass(new FormRequest('POST'));
