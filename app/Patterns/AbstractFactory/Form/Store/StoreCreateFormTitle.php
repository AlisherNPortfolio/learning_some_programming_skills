<?php

namespace App\Patterns\AbstractFactory\Form\Store;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormTitle;

class StoreCreateFormTitle implements ICreateFormTitle
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Do\'kon yaratish oynasi';
    }

    public function getTitle()
    {
        return $this->title;
    }
}
