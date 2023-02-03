<?php

namespace App\Services\Facade;

use App\Services\Geolocation\Geolocation;
use Illuminate\Support\Facades\Facade;

class GeolocationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Geolocation::class;
    }
}
