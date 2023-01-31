<?php

namespace App\Services\Satellite;

class Satellite
{
    public function pinpoint(array $info)
    {
        // ...
        $result = implode(", ", $info);

        return "Xarita {$result} joylashuviga o'tkazildi";
    }
}
