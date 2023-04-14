# Dependency Injection (DI) va DI Container

**Dependency Injection nima?**

Dependency Injection - bu bitta obyektning ishlashi uchun boshqa obyektni ishlatish usuli. Bu bitta obyekt o'zida boshqa bir obyekt bo'lishini ham talab qiladi degani.

Bu tushuncha juda sodda bo'lib, bitta obyektni ishlatish paytimizda unga boshqa obyektni beramiz (inject qilamiz):

```php
<?php

class Profile
{
    public function deactivateProfile(Setting $setting)
    {
        $setting->isActive = false;
    }
}

class Setting
{
    public $isActive = true;
    //...
}
```

`Profile` klasida `Setting` klasi obyekti ishlatilgani uchun unga `$setting` obyektini parametr sifatida inject qildik.

**Obyektlarni inject qilish usullari**

Obyektlarni inject qilishning turli xil yo'llari mavjud:

* **Contsructor injection**: obyektni konstruktorga inject qilish:

```php
<?php

class Profile
{
    public function __construct(private Setting $setting)
    {

    }
}

class Setting
{
    public $isActive = true;
    //...
}

$setting = new Setting();
$profile = new Profile($setting);
```

Bu usul eng ko'p ishlatiladigan usul hisoblanadi. Konstruktorga inject qilish orqali zaif bog'lanishni (loosely coupled) ta'minlash mumkin bo'ladi. Constructor injection dasturni testlashda ancha foydali hisoblanadi.

* **Setter Injection**: obyektni klasga setter funksiyasi yordamida inject qilish:

```php
<?php

class Profile
{
    private $setting;

    public function setSetting(Setting $setting)
    {
        $this->setting = $setting;
    }
}

class Setting
{
    public $isActive = true;
    //...
}

$setting = new Setting();
$profile = new Profile();
$profile->setSetting($setting);
```

**DI Container**

Dependency Injection Container - bu dasturda obyektlarni hamda tashqi kutubxonalarni inject qilish va boshqarish usuli.

PHP-FIG PSR-11 ga ko'ra dasturda DI Container quyidagicha bo'ladi:

```php
<?php

interface ContainerInterface
{
    public function get($id);

    public function has($id);
}

```

Bu kod `ContainerInterface` ning eng sodda ko'rinishi bo'lib, unda faqat ikkita `get()` va `has()` metodi bor.

* `get()` - containerdan obyektni olib beradi.
* `has()` - obyektni containerda mavjudligini tekshirib beradi.

**ReflectionClass va DI Container**

Ko'pchilik frameworklar dependencylar bilan qulay va oson ishlash uchun DI Containerdan foydalanadi. Ammo, "qulay va oson ishlash" deganda nimani tushunish mumkin? Bu biror obyektni boshqa biror klasga qo'lda emas avtomatik inject qilishni bildiradi.

Quyidagi misolni ko'raylik:

```php
<?php

class Setting
{

}

class Profile
{
    public function __construct(private Setting $setting)
    {

    }
}

```

Bu yerda `Profile` klasidan quyidagi ko'rinishda obyekt olishimi mumkin:

```php
$setting = new Setting();
$profile = new Profile($setting);
```

Ko'rib turganingizdek, `Profile` klasidan obyekt olishimiz uchun, albatta, `Setting` klasidan ham obyekt olishib, uni `Profile` klasining konstruktoriga inject qilishimiz kerak bo'ladi. Ammo, bu yo'l bilan obyekt olishda ish ham, kod ham ko'payib ketadi. Bu muammoni dasturga dependency klaslarni avtomatik yuklab beradigan `Container` klas yordamida hal qilishimiz mumkin (ushbu kod quyidagi [linkdan](https://gist.github.com/MustafaMagdi/2bb27aebf6ab078b1f3e5635c0282fac "https://gist.github.com/MustafaMagdi/2bb27aebf6ab078b1f3e5635c0282fac") olingan):

```php
<?php

/*
 * Class Container
 */

 class Container
 {
     /**
      * @var array
      */
     protected $instances = [];

     /**
      * @param $abstract
      * @param null $concrete
      */
     public function set($abstract, $concrete = null)
     {
         if ($concrete === null) {
             $concrete = $abstract;
         }

         $this->instances[$abstract] = $concrete;
     }

     /**
      * @param $abstract
      *
      * @return array $parameters
      * @return mixed|object|null
      *
      * @throws Exception
      */
     public function get($abstract, $parameters = [])
     {
         if (!isset($this->instances[$abstract])) {
             $this->set($abstract);
         }

         return $this->resolve($this->instances[$abstract], $parameters);
     }

     /**
      * resolve single.
      *
      * @param $concrete
      * @param $parameters
      *
      * @return mixed|object
      *
      * @throws Exception
      */
     public function resolve($concrete, $parameters)
     {
         if ($concrete instanceof Closure) {
             return $concrete($this, $parameters);
         }

         $reflector = new ReflectionClass($concrete);
         if (!$reflector->isInstantiable()) {
             throw new Exception('Class '.$concrete.'is not instantiable');
         }

         $constructor = $reflector->getConstructor();
         if (is_null($constructor)) {
             return $reflector->newInstance();
         }

         $parameters = $constructor->getParameters();
         $dependencies = $this->getDependencies($parameters);

         return $reflector->newInstanceArgs($dependencies);
     }

     /**
      * get all dependencies resolved.
      *
      * @param $parameters
      *
      * @return array
      *
      * @throws Exception
      */
     public function getDependencies($parameters)
     {
         $dependencies = [];

         foreach ($parameters as $parameter) {
             // $dependency = $parameter->getClass(); // PHP 8 da getClass() metodi depricated qilingan

             $dependency = ($parameter->getType() && !$parameter->getType()->isBuiltin()) ?
                            new ReflectionClass($parameter->getType()->getName()) :
                            null;

             if ($dependency === null) {
                 if ($parameter->isDefaultValueAvailable()) {
                     $dependencies[] = $parameter->getDefaultValue();
                 } else {
                     throw new Exception('Parameter '.$parameter->getName().' has no default value');
                 }
             } else {
                 $dependencies[] = $this->get($dependency->name);
             }
         }

         return $dependencies;
     }
 }

```

`Container` klasi turli xil klaslarni `set()` metodi yordamida containerga qo'shadi:

```php
<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'Container.php';

//...

$container = new Container(); // <==
$container->set('Profile'); // <==
```

Endi xohlagan paytimizda `Profile` klasidan osonlik bilan obyekt olishimiz mumkin:

```php
$profile = $container->get('Profile');
```

Bu yerda, `get()` funksiyasi `resolve()` funksiyasini ishlatib, `Profile` klasining `__construct()` metodida dependency bor yo'qligini tekshiradi va agar dependency mavjud bo'lsa rekursiv holda ulardan obyekt olib, kerakli joyga qo'yib chiqadi. Rekursiv holda tekshirish deganda, `Profile`ga berilgan `Setting`da ham dependency borligini tekshirishni anglatadi. Agar `Profile` klasida hech qanaqa dependency bo'lmasa, undan to'g'ridan to'g'ri obyekt olib, qaytaradi.

`ReflectionClass` va `ReflectionParameter` asosan `Container` klasida ishlatiladi:

```php
$reflector = new ReflectionClass($concrete);
// klasdan obyekt olib bo'lish yoki bo'lmasligini tekshirish
$reflector->isInstantiable();

// klas konstruktorini olish
$reflector->getConstructor();

// klasdan yangi obyekt olish
$reflector->newInstance();

// dependencylar bilan birga yangi obyekt olish
$reflector->newInstanceArgs($dependencies);

// konstructor parametrlarini olish
$constructor->getParameters();

// type hint qilingan klasni olish
$params->getClass();

// parametr uchun default qiymat bor yoki yo'qligini tekshirish
$parameter->isDefaultValueAvailable();

// paramtrning default qiymatini olish
$parameter->getDefaultValue();
```
