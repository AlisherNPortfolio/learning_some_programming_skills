<?php

namespace App\Http\Controllers\Patterns\AbstractFactory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('patterns.abstract-factory.home');
    }
}
