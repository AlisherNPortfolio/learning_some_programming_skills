<?php

namespace App\Patterns\AbstractFactory\Form\Contracts;

interface ICreateFormFactory
{
    public function getTitle();

    public function getBodyElements();

    public function getSubmitAction();
}
