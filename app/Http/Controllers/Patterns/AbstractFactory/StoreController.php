<?php

namespace App\Http\Controllers\Patterns\AbstractFactory;

use App\Http\Controllers\Controller;
use App\Patterns\AbstractFactory\Form\CreateForm;
use App\Patterns\AbstractFactory\Form\Store\StoreCreateFormFactory;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function create()
    {
        $createForm = CreateForm::getInstance(new StoreCreateFormFactory());
        return view('patterns.abstract-factory.pages.create', ['form' => $createForm]);
    }
}
