<?php

namespace App\Services\Facade;

use App\Services\Geolocation\Geolocation;

class FacadeTest
{
    public function __construct(Geolocation $geolocation) // <== 1-usul
    {
        $geolocation = app(Geolocation::class); // <== 2-usul
    }
}
