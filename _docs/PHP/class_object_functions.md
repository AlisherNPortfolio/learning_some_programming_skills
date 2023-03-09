## Class/Object functions. Klass va Obyektlar bilan ishlovchi funksiyalar.
Quyidagi funksiyalar mavjud:
* class_alias
* class_exists
* get_called_class
* get_class_methods
* get_class_vars
* get_class
* get_declared_classes
* get_declared_interfaces
* get_declared_traits
* get_object_vars
* get_parent_class
* interface_exists
* is_a
* is_subclass_of
* method_exists
* property_exists
* trait_exists

## Qo'llanilishi

### class_alias
Klass uchun alias yaratib beradi

` class_alias ( string $original , string $alias [, bool $autoload = true ] ) : bool`

`$origianl` - orginal kalss
`$alias` - klass uchun alias nomi
`$autoload` - orginal klass topilmasa autolad bo'lsinmi

Misol:



```php
<?php
    class foo { }
    
    class_alias('foo', 'bar');
    
    $a = new foo;
    $b = new bar;
    
    // Obyektlar bir xil
    var_dump($a == $b, $a === $b);
    var_dump($a instanceof $b);
    
    // Klasslar bir xil
    var_dump($a instanceof foo);
    var_dump($a instanceof bar);
    
    var_dump($b instanceof foo);
    var_dump($b instanceof bar);

?>

```

## class_exists

Klass e'lon qilinganligini tekshirish. Ya'ni klass mavjudmi yoki yo'qligini tekshirish

` class_exists ( string $class_name [, bool $autoload = true ] ) : bool`

`$class_name` - klass nomi
`$autoload` - default holatda __autoload chaqirilsinmi

Misol:

```php
<?php
function __autoload($class)
{
    include($class . '.php');

    if (!class_exists($class, false)) {
        trigger_error("Klassni yuklab bo'lmadi: $class", E_USER_WARNING);
    }
}
```

## get_called_class
PHP 5.3.0, PHP 7
Statik metod ichida turib chaqirilgan klass nomini olib beradi

` get_called_class ( ) : string`

Misol:

```php
<?php
    class foo {
        public static function test() {
            var_dump(get_called_class()); // o'rniga `static::class` deb ham ishlatish mumkin
        }
    }
    
    class bar extends foo {
    }
    
    foo::test();
    bar::test();
?>
```

## get_class_methods
PHP 4, PHP 5, PHP 7

Klass metodlari nomlarini oladi

` get_class_methods ( mixed $class_name ) : array`

`$class_name` - klass nomi yoki obyekt

Misol:

```php
<?php

    class myclass {

        function __construct()
        {
            return true;
        }
    
        function myfunc1()
        {
            return(true);
        }
    
        function myfunc2()
        {
            return(true);
        }
    }
    
    $class_methods = get_class_methods('myclass');
    // yoki obyekt qilib
    // $class_methods = get_class_methods(new myclass());
    
    foreach ($class_methods as $method_name) {
        echo "$method_name\n";
    }
?>
```

## get_class_vars
PHP 4, PHP 5, PHP 7

Klassning default xususiyatlarini oladi

` get_class_vars ( string $class_name ) : array`

`$class_name` - klass nomi

Misol:
```php

<?php

    class myclass {
    
        var $var1; // bu xususiyatda default qiymat yo'q
        var $var2 = "xyz";
        var $var3 = 100;
        private $var4;
    
        // constructor
        function __construct() {
            // ba'zi xususiyatlarni o'zgartiramiz
            $this->var1 = "foo";
            $this->var2 = "bar";
            return true;
        }
    }
    
    $my_class = new myclass();
    
    $class_vars = get_class_vars(get_class($my_class));
    
    foreach ($class_vars as $name => $value) {
        echo "$name : $value\n";
    }
?>
```

## get_class
PHP 4, PHP 5, PHP 7

Obyektning klassi nomini beradi

` get_class ([ object $object ] ) : string`

`$object` - obyekt nomi. agar klass ichida ishlatilsa berish shart emas.

PHP 7.2.0 dan boshlab `$obyekt` sifatida `null` berilmaydi. Parametersiz get_class() hali ham ishlaydi. Lekin, E_WARNING  qaytaradi

Misol:

```php
<?php
    class foo {
        function name()
        {
            echo "Mening ismim " , get_class($this) , "\n";
        }
    }
    
    // obyektni yaratish
    $bar = new foo();
    
    // tashqaridan chaqirish
    echo "Uninng ismi " , get_class($bar) , "\n";
    
    // ichkarida chaqirish
    $bar->name();
?>
```

Natija:

```
Its name is foo
My name is foo
```

Misol 2:

```php
<?php
    abstract class bar {
        public function __construct()
        {
            var_dump(get_class($this));
            var_dump(get_class());
        }
    }
    
    class foo extends bar {
    }
    
    new foo;
?>
```
Natija:

```
string(3) "foo"
string(3) "bar"
```

## get_declared_classes
PHP 4, PHP 5, PHP 7

Barcha e'lon qilingan klasslar nomlarini arrayda chiqarib beradi

` get_declared_classes ( ) : array`

7.4.0 dan oldin avval ota klasslarni, keyin bola klasslarni chiqargan. 7.4.0 dan boshlab esa tartib bilan chiqmasligi mumkin.

```php
<?php
    print_r(get_declared_classes());
?>
```

## get_declared_interfaces
PHP 5, PHP 7

Barcha e'lon qilingan interfeyslar arrayini beradi.

 `get_declared_interfaces ( ) : array`
 
 ```php
<?php
    print_r(get_declared_interfaces());
?>
```

## get_declared_traits
PHP 5 >= 5.4.0, PHP 7

Barcha e'lon qilingan traitlar arrayini oladi.

` get_declared_traits ( ) : array`

## get_object_vars
PHP 4, PHP 5, PHP 7

Berilgan obyektning xususiyatlarini olib beradi (Statik bo'lmagan xususiyatlarni).

` get_object_vars ( object $object ) : array`

Misol:

```php
<?php
    class foo {
        private $a;
        public $b = 1;
        public $c;
        private $d;
        static $e;
       
        public function test() {
            var_dump(get_object_vars($this));
        }
    }
    
    $test = new foo;
    var_dump(get_object_vars($test));
    
    $test->test();
?>
```

Natija:
```
array(2) {
  ["b"]=>
  int(1)
  ["c"]=>
  NULL
}
array(4) {
  ["a"]=>
  NULL
  ["b"]=>
  int(1)
  ["c"]=>
  NULL
  ["d"]=>
  NULL
}
```

## get_parent_class
PHP 4, PHP 5, PHP 7

Obyekt yoki klassning ota klasi nomini beradi.

` get_parent_class ([ mixed $object ] ) : string`

`$object` - obyekt yoki klass nomi


Misol:

```php
<?php
    class dad {
        function __construct()
        {
        // ...
        }
    }
    
    class child extends dad {
        function __construct()
        {
            echo "Men " , get_parent_class($this) , "ning bolasiman\n";
        }
    }
    
    class child2 extends dad {
        function __construct()
        {
            echo "Men " , get_parent_class('child2') , "ning bolasiman\n";
        }
    }
    
    $foo = new child();
    $bar = new child2();
?>
```

## interface_exists
PHP 5 >= 5.0.2, PHP 7

Interfeys e'lon qilinganligini tekshiradi.

` interface_exists ( string $interface_name [, bool $autoload = true ] ) : bool`

Misol:

```php
<?php
if (interface_exists('MyInterface')) {
    class MyClass implements MyInterface
    {  
    }
}
?>
```


## is_a
PHP 4 >= 4.2.0, PHP 5, PHP 7

Obyekt berilgan klassdan yoki uning ota klasslarining biridan olinganligini tekshiradi.

` is_a ( mixed $object , string $class_name [, bool $allow_string = false ] ) : bool`

Misol:

```php

<?php
// define a class
class WidgetFactory
{
  var $oink = 'moo';
}

// create a new object
$WF = new WidgetFactory();

if (is_a($WF, 'WidgetFactory')) {
  echo "yes, \$WF is still a WidgetFactory\n";
}
?>

```

Misol 2:

PHP5 da instanceof deb ishlatilishi

```php

<?php
class WidgetFactory
{
  var $oink = 'moo';
}

// create a new object
$WF = new WidgetFactory();
if ($WF instanceof WidgetFactory) {
    echo 'Yes, $WF is a WidgetFactory';
}
?>

```

Misol 3:
use bilan chiqirilganda ishlamaydi

```php
<?php
namespace foo\bar;

class A {}
class B extends A {}
?>

<?php
namespace har\var;

use foo\bar\A;
$foo = new foo\bar\B();

is_a($foo, 'A'); // returns false;
is_a($foo, 'foo\bar\A'); // returns true;
?>
```

## is_subclass_of
PHP 4, PHP 5, PHP 7


Berilgan obyekt biror klassning yoki uning ota klassiga tegishliligini tekshiradi

` is_subclass_of ( mixed $object , string $class_name [, bool $allow_string = true ] ) : bool`

Misol:

```php

<?php
// define a class
class WidgetFactory
{
  var $oink = 'moo';
}

// define a child class
class WidgetFactory_Child extends WidgetFactory
{
  var $oink = 'oink';
}

// create a new object
$WF = new WidgetFactory();
$WFC = new WidgetFactory_Child();

if (is_subclass_of($WFC, 'WidgetFactory')) {
  echo "yes, \$WFC is a subclass of WidgetFactory\n";
} else {
  echo "no, \$WFC is not a subclass of WidgetFactory\n";
}


if (is_subclass_of($WF, 'WidgetFactory')) {
  echo "yes, \$WF is a subclass of WidgetFactory\n";
} else {
  echo "no, \$WF is not a subclass of WidgetFactory\n";
}


// usable only since PHP 5.0.3
if (is_subclass_of('WidgetFactory_Child', 'WidgetFactory')) {
  echo "yes, WidgetFactory_Child is a subclass of WidgetFactory\n";
} else {
  echo "no, WidgetFactory_Child is not a subclass of WidgetFactory\n";
}
?>

```

Natija:

```
yes, $WFC is a subclass of WidgetFactory
no, $WF is not a subclass of WidgetFactory
yes, WidgetFactory_Child is a subclass of WidgetFactory
```

## method_exists
PHP 4, PHP 5, PHP 7

Klassda metod bor yo'qligini tekshiradi.

` method_exists ( string|object $object , string $method_name ) : bool`

Misol:
```php
<?php
$directory = new Directory('.');
var_dump(method_exists($directory,'read'));
?>
```

Natija:

```
bool(true)
```

## property_exists
PHP 5 >= 5.1.0, PHP 7

Klass yoki obyektda xususiyat borligini tekshiradi.

` property_exists ( mixed $class , string $property ) : bool`



Misol:

```php

<?php

class myClass {
    public $mine;
    private $xpto;
    static protected $test;

    static function test() {
        var_dump(property_exists('myClass', 'xpto')); //true
    }
}

var_dump(property_exists('myClass', 'mine'));   //true
var_dump(property_exists(new myClass, 'mine')); //true
var_dump(property_exists('myClass', 'xpto'));   //true, as of PHP 5.3.0
var_dump(property_exists('myClass', 'bar'));    //false
var_dump(property_exists('myClass', 'test'));   //true, as of PHP 5.3.0
myClass::test();

?>

```

## trait_exists
PHP 5 >= 5.4.0, PHP 7

Trait mavjudligini tekshiradi

` trait_exists ( string $traitname [, bool $autoload ] ) : bool`

Misol:

```php

<?php
trait World {

    private static $instance;
    protected $tmp;

    public static function World()
    {
        self::$instance = new static();
        self::$instance->tmp = get_called_class().' '.__TRAIT__;
       
        return self::$instance;
    }

}

if ( trait_exists( 'World' ) ) {
   
    class Hello {
        use World;

        public function text( $str )
        {
            return $this->tmp.$str;
        }
    }

}

echo Hello::World()->text('!!!'); // Hello World!!!

```
