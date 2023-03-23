<?php

namespace App\Patterns\AbstractFactory\Form\Store;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateSubmitAction;

class StoreCreateFormSubmitAction implements ICreateSubmitAction
{
    protected $actionUrl;

    public function __construct()
    {
        $this->actionUrl = '/create-store';
    }

    public function getActionUrl()
    {
        return $this->actionUrl;
    }
}
