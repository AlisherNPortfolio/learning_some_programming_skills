<?php

namespace App\Patterns\AbstractFactory\Form\Account;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormBody;

class AccountCreateFormBody implements ICreateFormBody
{
    protected $elements;

    public function __construct()
    {
        $this->elements = [
            ['type' => 'text', 'name' => 'name', 'placeholder' => 'Name'],
            ['type' => 'email', 'name' => 'email', 'placeholder' => 'Email'],
            ['type' => 'text', 'name' => 'phone', 'placeholder' => 'Phone number']
        ];
    }

    public function getBodyElements()
    {
        return $this->elements;
    }
}
