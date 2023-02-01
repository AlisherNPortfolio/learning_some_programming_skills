# Facade

Service Container mavzusida service container-dagi obyektlarni qanday olishni ko'rgan edik. Eslaydigan bo'lsak, service container-dan obyektlar `app(class_nomi::class)` ko'rinishida yoki **Auto** **Dependency Resoulution** orqali olish mumkin edi:

```php
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

```
