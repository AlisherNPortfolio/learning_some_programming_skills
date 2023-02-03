<?php

namespace App\Services\Facade;

class Playground
{
    public function __construct()
    {
        $result = GeolocationFacade::search('a');
        dump($result);
    }
}
