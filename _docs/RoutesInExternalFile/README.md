# Route-larni alohida faylga olib chiqish

Route-larni alohida faylga olib chiqishdan maqsad, bir guruhga kiruvchi route-larni alohida qilish va route-lardagi kodning tozaligini saqlash hisoblanadi.

Route-larni alohida olib chiqishni `users`, `posts` va `comments` larning route-lar misolida ko'ramiz:

1. `routes/api/v1` papkasini ochamiz.
2. unda 3 ta `users.php`, `posts.php` va `comments.php` fayllarini ochamiz:

`routes/api/v1/users.php` fayli:

```php
<?php

use Illuminate\Support\Facades\Route;

// route-lar
```

`routes/api/v1/posts.php` fayli:

```php
<?php

use Illuminate\Support\Facades\Route;

// route-lar
```

`routes/api/v1/comments.php` fayli:

```php
<?php

use Illuminate\Support\Facades\Route;

// route-lar
```

3. `app/Helpers/Routes` papkasini yaratib unda `RouteHelper.php` faylini ochamiz. Faylda esa `RouteHelper` klasini yaratamiz:

```php
<?php

namespace App\Helpers\Routes;

class RouteHelper
{
    public static function includeRouteFiles(string $folder)
    {
        /**
         * Har bir faylni bittalab (require __DIR__ . 'folder' ko'rinishida) chaqirmaslik uchun
         * Recursive Directory Iterator-dan foydalanamiz
         */
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /** @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator($dirIterator);

        while ($it->valid()) {

            if ($it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                require $it->key();
            }

            $it->next();
        }
    }
}

```

Bir nechta route-ni chaqirish kerak bo'lganda, har birini `require` yordamida bitta-bittalab yuklab olmaslik uchun yuqoridagi `RouteHelper` klasini yaratdik.

4. `routes/api.php` faylda `routes/api/v1` papkadagi fayllarni yuklab, route-ga qo'shish uchun `RouterHelper` klasidan foydalanamiz:

```php
Route::prefix('v1') // api route-da api-dan keyin v1 qo'shish
    ->group(function () {
        // require __DIR__ . '/api/v1/users.php';
        // require __DIR__ . '/api/v1/posts.php';
        // require __DIR__ . '/api/v1/comments.php';

        // Yuqoridagidey bittalab chaqirib o'tirmaslik uchun ...
        \App\Helpers\Routes\RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');
    });

```
