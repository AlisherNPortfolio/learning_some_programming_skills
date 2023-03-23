<?php

namespace App\Patterns\AbstractFactory\Form\Store;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormBody;

class StoreCreateFormBody implements ICreateFormBody
{
    protected $elements;

    public function __construct()
    {
        $this->elements = [
            ['type' => 'text', 'name' => 'name', 'placeholder' => 'Name'],
            ['type' => 'email', 'name' => 'email', 'placeholder' => 'Email'],
            ['type' => 'text', 'name' => 'country', 'placeholder' => 'Country'],
            ['type' => 'text', 'name' => 'city', 'placeholder' => 'City']
        ];
    }

    public function getBodyElements()
    {
        return $this->elements;
    }
}
