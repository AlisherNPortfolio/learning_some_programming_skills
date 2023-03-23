# Abstract Factory Pattern-ning Laravel-da qo'llanilishi

## **Vazifa**

Laravel-da dastur qilish paytida foydalanuvchi uchun account yoki do'kon yaratish imkoniyatini qilish.

## **Dasturning asosiy qismining kodi**

Avvalo, asosiy oynada account yoki do'kon ochishni taklif qiluvchi tugmalarni chiqaramiz.

`app\Http\Controllers\Patterns\AbstractFactory\HomeController.php`:

```php
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

```

Yuqoridagi kontrollerning `index` metodi `home` sahifasini ochib beradi. Bu sahifaga o'tishdan avval, asosiy blade sahifani yozib qo'yaylik:

`resources\views\patterns\abstract-factory\app.blade.php`:

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        @yield('content')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

```

`resources\views\patterns\abstract-factory\home.blade.php`:

```html
@extends('app')

@section('title', 'Home page')

@section('content')

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Welcome</h1>
</div>
<div class="text-center">
    <div class="mb-2">
        <a href="/create-store" class="btn btn-lg btn-block btn-outline-primary">Create a store</a>
    </div>
    <div class="mb-2">
        <a href="/create-account" class="btn btn-lg btn-block btn-primary">Create an account</a>
    </div>
</div>
@endsection
```

Keyin, account va do'kon yaratib beruvchi `AccountController` va `StoreController` controller-larini yaratib olamiz:

`app\Http\Controllers\Patterns\AbstractFactory\AccountController.php`:

```php
class AccountController extends Controller
{
    public function create()
    {
        $createForm = null;
        return view('patterns.abstract-factory.pages.create', ['form' => $createForm]);
    }
}

```

`app\Http\Controllers\Patterns\AbstractFactory\StoreController.php`:

```php
class StoreController extends Controller
{
    public function create()
    {
        $createForm = null;
        return view('patterns.abstract-factory.pages.create', ['form' => $createForm]);
    }
}
```

`resources\views\patterns\abstract-factory\pages\create.blade.php`:

```html
@extends('app')

@section('title', 'Create page')

@section('content')
<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    @if (isset($form))

    @else
        <h1 class="display-4">Welcome</h1>
    @endif
</div>
@endsection
```

`home` view-da berilgan `/create-store` va `/create-account` URL-lari uchun route ochamiz:

`routes/web.php`:

```php
//...
Route::get('abstract-factory', [HomeController::class, 'index']);
Route::get('create-account', [AccountController::class, 'create']);
Route::get('create-store', [StoreController::class, 'create']);
```

## Abstract Factory patternini qo'llash

Bu pattern biror klasning obyektini yaratib o'tirmasdan, bu obyektlarni interfeys orqali olishni ta'minlab beradi.

Bizdagi create sahifasidagi formada title, forma maydonlari va ma'lumotlar yuboriladigan URL bo'ladi. Bu esa har bir forma komponenti uchun alohida interfeys yaratish kerakligini anglatadi.

`app\Patterns\AbstractFactory\Form\Contracts\ICreateFormTitle.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Contracts;

interface ICreateFormTitle
{
    public function getTitle();
}

```

`app\Patterns\AbstractFactory\Form\Contracts\ICreateFormBody.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Contracts;

interface ICreateFormBody
{
    public function getBodyElements();
}

```

Bu interfeys uni ishlatadigan klas forma maydonlarini arrayda qaytaruvchi metodga ega bo'lishi kerakligini bildiradi. Array esa har bir forma maydoninig nomi, tipi, placeholderi kabi ma'lumotlarga ega bo'ladi.

`app\Patterns\AbstractFactory\Form\Contracts\ICreateSubmitAction.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Contracts;

interface ICreateSubmitAction
{
    public function getActionUrl();
}

```

`ICreateSubmitAction` interfeysi forma ma'lumotlari yuborilishi kerak bo'lgan URLni beradi.

Quyidagi interfeys esa formaning barcha elementlarini chaqiruvchi interfeys bo'ladi:

`app\Patterns\AbstractFactory\Form\Contracts\ICreateFormFactory.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Contracts;

interface ICreateFormFactory
{
    public function getTitle();

    public function getBodyElements();

    public function getSubmitAction();
}

```

Shu yergacha barcha kerakli interfeyslarni yozib chiqdik. Endi, ularni ishlatamiz.

Account formasini yaratishda interfeyslarni ishlatish uchun har bir interfeysning metodlarini yaratib chiqamiz.

`app\Patterns\AbstractFactory\Form\Account\AccountCreateFormTitle.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Account;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormTitle;

class AccountCreateFormTitle implements ICreateFormTitle
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Account yaratish oynasi';
    }

    public function getTitle()
    {
        return $this->title;
    }
}
```

`app\Patterns\AbstractFactory\Form\Account\AccountCreateFormBody.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Account;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormBody;

class AccountCreateFormBody implements ICreateFormBody
{
    protected $elements;

    public function __construct()
    {
        $this->elements = [
            ['type' => 'text', 'name' => 'name', 'placeholder' => 'Name'],
            ['type' => 'email', 'name' => 'email', 'placeholder' => 'Email'],
            ['type' => 'text', 'name' => 'phone', 'placeholder' => 'Phone number']
        ];
    }

    public function getBodyElements()
    {
        return $this->elements;
    }
}

```

`app\Patterns\AbstractFactory\Form\Account\AccountCreateFormSubmitAction.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Account;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateSubmitAction;

class AccountCreateFormSubmitAction implements ICreateSubmitAction
{
    protected $actionUrl;

    public function __construct()
    {
        $this->actionUrl = '/create-account';
    }

    public function getActionUrl()
    {
        return $this->actionUrl;
    }
}
```

`app\Patterns\AbstractFactory\Form\Account\AccountCreateFormFactory.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Account;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormFactory;

class AccountCreateFormFactory implements ICreateFormFactory
{
    public function getTitle()
    {
        $title = new AccountCreateFormTitle();
        return $title->getTitle();
    }

    public function getBodyElements()
    {
        $formBody = new AccountCreateFormBody();
        return $formBody->getBodyElements();
    }

    public function getSubmitAction()
    {
        $submitAction = new AccountCreateFormSubmitAction();
        return $submitAction->getActionUrl();
    }
}
```

Endi, yuqoridagi klaslarni Store uchun ham yozib olamiz.

`app\Patterns\AbstractFactory\Form\Store\StoreCreateFormTitle.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Store;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormTitle;

class StoreCreateFormTitle implements ICreateFormTitle
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Do\'kon yaratish oynasi';
    }

    public function getTitle()
    {
        return $this->title;
    }
}

```

`app\Patterns\AbstractFactory\Form\Store\StoreCreateFormBody.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Store;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormBody;

class StoreCreateFormBody implements ICreateFormBody
{
    protected $elements;

    public function __construct()
    {
        $this->elements = [
            ['type' => 'text', 'name' => 'name', 'placeholder' => 'Name'],
            ['type' => 'email', 'name' => 'email', 'placeholder' => 'Email'],
            ['type' => 'text', 'name' => 'country', 'placeholder' => 'Country'],
            ['type' => 'text', 'name' => 'city', 'placeholder' => 'City']
        ];
    }

    public function getBodyElements()
    {
        return $this->elements;
    }
}

```

`app\Patterns\AbstractFactory\Form\Store\StoreCreateFormSubmitAction.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Store;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateSubmitAction;

class StoreCreateFormSubmitAction implements ICreateSubmitAction
{
    protected $actionUrl;

    public function __construct()
    {
        $this->actionUrl = '/create-store';
    }

    public function getActionUrl()
    {
        return $this->actionUrl;
    }
}

```

`app\Patterns\AbstractFactory\Form\Store\StoreCreateFormFactory.php`:

```php
<?php

namespace App\Patterns\AbstractFactory\Form\Store;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormFactory;

class StoreCreateFormFactory implements ICreateFormFactory
{
    public function getTitle()
    {
        $title = new StoreCreateFormTitle();
        return $title->getTitle();
    }

    public function getBodyElements()
    {
        $formBody = new StoreCreateFormBody();
        return $formBody->getBodyElements();
    }

    public function getSubmitAction()
    {
        $submitAction = new StoreCreateFormSubmitAction();
        return $submitAction->getActionUrl();
    }
}

```

Oxirgi qiladigan ishimiz, klaslarni ishlatish bo'ladi.

`app\Patterns\AbstractFactory\Form\CreateForm.php:`

```php
<?php

namespace App\Patterns\AbstractFactory\Form;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormFactory;

class CreateForm
{
    private static $instance;

    public function __construct(protected ICreateFormFactory $createFormFactory)
    {
    }

    public static function getInstance(ICreateFormFactory $createFormFactory): self
    {
        if (empty(self::$instance)) {
            self::$instance = new CreateForm($createFormFactory);
        }

        return self::$instance;
    }

    public function getTitle(): string
    {
        return $this->createFormFactory->getTitle();
    }

    public function getBodyElements(): array
    {
        return $this->createFormFactory->getBodyElements();
    }

    public function getSubmitAction(): string
    {
        return $this->createFormFactory->getSubmitAction();
    }
}

```

`CreateForm` klasi `singleton pattern`dan foydalanadi. Bunga sabab, har bir kontrollerda bu klasning yagona obyekti bo'lishi yetarliligida.

Endi, `CreateForm` klasini kontrollerlarda ishlatamiz.

`app\Http\Controllers\Patterns\AbstractFactory\AccountController.php`:

```php
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

```

`app\Http\Controllers\Patterns\AbstractFactory\StoreController.php`:

```php
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

```

`resources\views\patterns\abstract-factory\pages\create.blade.php`:

```html
@extends('patterns.abstract-factory.app')

@section('title', 'Create page')

@section('content')
<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    @if (isset($form))
    <h1 class="display-4 mb-4">{{ $form->getTitle() }}</h1>
    <form action="{{ $form->getSubmitAction() }}">
        @foreach ($form->getBodyElements() as $element)
            <div class="mb-3">
                <div class="input-group">
                    <input type="{{ $element['type'] }}" name="{{ $element['name'] }}" placeholder="{{ $element['placeholder'] }}">
                </div>
            </div>
        @endforeach

        <button class="btn btn-primary btn-lg btn-block" type="submit">
            {{ $form->getTitle() }}
        </button>
    </form>
    @else
        <h1 class="display-4">Welcome</h1>
    @endif
</div>
@endsection


```

## Natija

![1679557101166](image/README/1679557101166.png)
