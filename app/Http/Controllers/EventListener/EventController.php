<?php

namespace App\Http\Controllers\EventListener;

use App\Events\ClearCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // ...
        $arr_caches = ['categories', 'products'];

        event(new ClearCache($arr_caches)); // <== CacheClear eventini ishga tushirish
    }
}
