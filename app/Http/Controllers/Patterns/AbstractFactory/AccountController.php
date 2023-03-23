<?php

namespace App\Http\Controllers\Patterns\AbstractFactory;

use App\Http\Controllers\Controller;
use App\Patterns\AbstractFactory\Form\Account\AccountCreateFormFactory;
use App\Patterns\AbstractFactory\Form\CreateForm;

class AccountController extends Controller
{
    public function create()
    {
        $createForm = CreateForm::getInstance(new AccountCreateFormFactory());
        return view('patterns.abstract-factory.pages.create', ['form' => $createForm]);
    }
}
