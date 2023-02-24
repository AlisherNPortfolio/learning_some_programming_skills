## PHP magic methods. PHP ichki metodlar

### PHP dasturlash tilida o'zining ichki metodlari mavjud. Bu metodlar PHP klasslarda ishlatiladi. Quyidagi ichki metodlar mavjud:

* __construct()
* __destruct()
* __call()
* __callStatic()
* __get()
* __set()
* __isset()
* __unset()
* __sleep()
* __wakeup()
* __toString()
* __invoke()
* __set_state()
* __clone()
* __debugInfo()
* __autoload() - php5, php7. PHP7.2 dan boshlab eskirgan hisoblanadi. Bu metodni ishlatmaslik kerak deb hisoblanadi.

Bu metodlar ingliz tilida magic ya'ni "sehrli" metodlar deb nomlanadi. Bunga sabab esa, siz ularni klass ichida e'lon qilib klassni ishlatganingizda, bu metodlar siz ularni chaqirmasangiz ham o'z-o'zidan ishga tushadi.

Ichki metodlarning eng ko'p ishlatiladigani - bu __construct() metodi. Bu metod klassdan obyekt olingan zahoti ishga tushadi.

## Ichki metodlarni ishlatishga misollar.

### __contstruct().

Bu metodni klass ichida e'lon qilsangiz, klassdan obyekt olishingiz bilan ishga tushadi. Bu metodning maqsadi klass ichidagi xususiyat(property)larga boshlang'ich qiymat berib qo'yish hisoblandi.

Ushbu metodni ***contstructor*** deb ham aytishadi.

Misol:

```
<?php
class Talaba {
    private $ism;
    private $pochta;

    public function __construct($ism, $pochta)
    {
        $this->ism = $ism;
        $this->pochta = $pochta;
    }
}

$talabaObyekt = new Talaba('Alisher', 'myemail9445@inbox.ru');
?>
```

Bu yerda Talaba klassi ichida e'lon qilingan __construct metodi Talaba klasidan obyekt olishda obyektdagi ism va pochta xususiyatlariga qiymat berish uchun ishlatilgan.

### __destruct()

Bu metod destructor deb ham nomlanadi. U, klass ichida e'lon qilingan bo'lsa, obyekt o'chirilishdan oldin ishga tushadi. Odatda, ushbu metod skript to'xtaganda yoki skriptdan chiqib ketganda ishga tushiriladi. Bu metoddan obyektning oxirgi holatini saqlab qo'yishda yoki boshqa biror amalni obyekt to'xtashidan oldin bajarib olishda foydalaniladi.

Misol:

```bas
<?php
class Talaba {
    private $ism;
    private $pochta;

    public function __construct($ism, $pochta)
    {
        $this->ism = $ism;
        $this->pochta = $pochta;
    }

    public function __destruct()
    {
        echo 'Bu metod skript to'xtashi bilan ishga tushadi';
        // obyekt holatini saqlang yoki biror kerakli ishni bajarib oling
    }
}

$talabaObyekt = new Talaba('Alisher', 'myemail9445@inbox.ru');
?>

```


### __set()

__set() ichki metodi obyektning mavjud bo'lmagan yoki private bo'lgan xususiyatiga qiymat berish  paytida ishga tushadi. Bu metod obyektda ishlatmoqchi bo'lgan xususiyatingiz bo'lmasa lekin siz obyektga qo'shimcha ma'lumot kiritishingiz kerak bo'lganda ishlatiladi.

Misol:

``````bash
<?php
class Talaba {
    private $data = array();

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}

$talabaObyekt = new Talaba();

//  __set() ishga tushadi
$objStudent->telefon = '0491 570 156';
?>
``````

Yuqoridagi misolda ko'rinib turibdiki, biz mavjud bo'lmagan telefon xususiyatiga qiymat bermoqchi bo'lganimizda set() metodi ishga tushdi. Metoddagi birinchi argument biz qiymat bermoqchi bo'lgan xususiyat nomi, ya'ni 'telefon', ikkinchi argument esa shu xususiyatga bermoqchi bo'lagan qiymatimiz, ya'ni '0491 570 156'.

### __get()

Bundan oldingi __set() metodi qanday qilib mavjud bo'lmagan xususiyatlarga qiymat berishini ko'rdik. __get() metodi __set() metodi qiladigan ishning aynana teskarisini bajaradi. Ya'ni, obyektning mavjud bo'lmagan yoki murojaat ruxsati yopiq (private) bo'lgan xususiyatidagi ma'lumotni olishda ishlatiladi. Bu metodning maqsadi mana shunday xususiyatlarga qiymat berishdan iborat.

Misol:

```bash
<?php
class Talaba {
    private $data = array();

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        If (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }
}

$talabaObyekt = new Talaba();

//  __set() ishga tushadi
$talabaObyekt->telefon = '0491 570 156';   

//  __get() ishga tushadi
echo $talabaObyekt->telefon;  
?>
```

### toString()

__toString() metodidan obyektni string sifatida chiqarmoqchi bo'lganingizda qanday qiymat chiqishi kerakligini ko'rsatishda foydalaniladi. Agar, siz biror obyektda __toString() metodini bermasdan uni `echo` yoki `print` funksiyasida ishlatsangiz xatolik kelib chiqadi.

Misol:

```bash
<?php
class Talaba {
    private $ism;
    private $pochta;

    public function __construct($ism, $pochta)
    {
        $this->ism = $ism;
        $this->pochta = $pochta;
    }

    public function __toString()
    {
        return 'Talaba ismi: '.$this->ism
        . '<br>'
        . 'Talaba pochtasi: '.$this->pochta;
    }
}

$talabaObyekt = new Talab('Alisher', 'myemail9445@inbox.ru');
echo $talabaObyekt;
?>
```

Yuqoridagi misolda, `$talabaObyekt` `echo` bilan ekranga chiqarilyapti. Bunda u __toString() metodini chariqradi.

### __call() va __callStatic()

__set() va __get() metodlari mavjud bo'lmagan yoki private bo'lgan xususiyatlar bilan ishlaganda ishlatilsa, __call() metodidan klassda e'lon qilinmagan metodlar ishga tushirishda foydalaniladi.

Misol 1:
```bash
<?php
class Talaba {
    public function __call($methodName, $arguments)
    {
        // $methodName = getStudentDetails
        // $arguments = array('1')
    }
}

$talabaObyekt = new Talaba();
$talabaObyekt->getStudentDetails(1);
?>
```

Misol 2:
```bash
<?php
class Person
{                             
    function say()
    {
           echo "Hello, world!<br>";
    }     

    function __call($funName, $arguments)
    {
          echo "The function you called：" . $funName . "(parameter：" ;  // Print the method\'s name that is not existed.
          print_r($arguments); // Print the parameter list of the method that is not existed.
          echo ")does not exist!！<br>\n";                   
    }                                         
}
$Person = new Person();           
$Person->run("teacher"); // If the method which is not existed is called within the object, then the __call() method will be called automatically.
$Person->eat("John", "apple");             
$Person->say();
```

Ko'rib turganingizdek, biz klassda e'lon qilinmagan `getStudentDetails` metodini chaqiryapmiz. Klassda bunday metod yo'qligi sababli __call() metodi ishga tushyapti. Metoddagi birinchi argument chaqirilayotgan metodning nomi, ikkinchisi esa shu metodga beriladigan argumentlar massivi bo'ladi.

__callStatic() metod ham __call() metodga juda o'xshaydi. Farqi, bu metod e'lon qilinmagan static metodni chaqirishda ishlatiladi.

Misol:
```bash
<?php
class Person
{
    function say()
    {
        echo "Hello, world!<br>";
    }

    public static function __callStatic($funName, $arguments)
    {
        echo "The static method you called：" . $funName . "(parameter：" ;  // Print the method\'s name that is not existed.
        print_r($arguments); // Print the parameter list of the method that is not existed.
        echo ")does not exist！<br>\n";
    }
}
$Person = new Person();
$Person::run("teacher"); // If the method which is not existed is called within the object, then the __callStatic() method will be called automatically.
$Person::eat("John", "apple");
$Person->say();
```


### __isset() va __unset()

__isset() metodi obyektning private yoki ma'vjud bo'lmagan xususiyati uchun `isset()` funksiyasini ishlatganda ishga tushadi.

Misol:

```bash
<?php
class Talaba {
    private $data = array();

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
}

$talabaObyekt = new Talaba();
echo isset($talabaObyekt->telefon);
?>
```

__unset() metodi esa obyektning private yoki mavjud bo'lmagan xususiyati uchun `unset()` funksiyasini ishlatganda ishga tushadi.
Misol:
```bash
<?php
class Person
{
    public $sex;
    private $name;
    private $age;

    public function __construct($name="",  $age=25, $sex='Male')
    {
        $this->name = $name;
        $this->age  = $age;
        $this->sex  = $sex;
    }

    /**
     * @param $content
     *
     * @return bool
     */
    public function __unset($content) {
        echo "It is called automatically when we use the unset() method outside the class.<br>";
        echo  isset($this->$content);
    }
}

$person = new Person("John", 25); // Initially assigned.
unset($person->sex),"<br>";
unset($person->name),"<br>";
unset($person->age),"<br>";
```

### __sleep() va __wakeup()

__sleep() metodi shu paytgacha ko'rgan ichki metodlarimizdan birmuncha farq qiladi. Bu metod obyektni `serialize()` funksiya bilan ishlatganda paytda ishga tushadi. Juda katta hajmli obyekt bo'lgan holatda, siz obyektni serializatsiya qilganda yoki tozalaganda faqat tanlangan xususiyatlarnigina saqlashni xohlasangiz __sleep() metoddan foydalanasiz. __sleep() metodi obyektning serializatsiya bo'lishi kerak bo'lgan xususiyatlari nomlarini arrayda qaytarishi kerak bo'ladi.

Misol 1:

```bash
<?php
class Talaba {
    private $ism;
    private $pochta;
    private $telefon;
    private $bazaga_havola;

    public function __construct($ism, $pochta, $telefon)
    {
        $this->ism = $ism;
        $this->pochta = $pochta;
        $this->telefon = $telefon;
    }

    public function __sleep()
    {
        return array('ism', 'pochta', 'telefon');
    }

    public function __wakeup()
    {
        $this->bazaga_havola = bazaga_ulanish_funksiyasi();
    }
}
?>
```

Misol 2:
```bash
<?php
class Person
{
    public $sex;
    public $name;
    public $age;

    public function __construct($name="",  $age=25, $sex='Male')
    {
        $this->name = $name;
        $this->age  = $age;
        $this->sex  = $sex;
    }

    /**
     * @return array
     */
    public function __sleep() {
        echo "It is called when the serialize() method is called outside the class.<br>";
        $this->name = base64_encode($this->name);
        return array('name', 'age'); // It must return a value of which the elements are the name of the properties returned.
    }
}

$person = new Person('John'); // Initially assigned.
echo serialize($person);
echo '<br/>';
```

Yuqoridagi misolda, Talaba obyektini serialize qilinganda, __sleep() metodi chaqiriladi. Bu metod esa faqatgina `ism`, `pochta`, va `telefon` o'zgaruvchilarini saqlab qo'yadi.

__wakeup() metodi esa `unserialize()` funksiyasi ishlatilganda ishga tushadi.
Misol:
```bash
<?php
class Person
{
    public $sex;
    public $name;
    public $age;

    public function __construct($name="",  $age=25, $sex='Male')
    {
        $this->name = $name;
        $this->age  = $age;
        $this->sex  = $sex;
    }

    /**
     * @return array
     */
    public function __sleep() {
        echo "It is called when the serialize() method is called outside the class.<br>";
        $this->name = base64_encode($this->name);
        return array('name', 'age'); // It must return a value of which the elements are the name of the properties returned.
    }

    /**
     * __wakeup
     */
    public function __wakeup() {
        echo "It is called when the unserialize() method is called outside the class.<br>";
        $this->name = 2;
        $this->sex = 'Male';
        // There is no need to return an array here.
    }
}

$person = new Person('John'); // Initially assigned.
var_dump(serialize($person));
var_dump(unserialize(serialize($person)));
```


### __invoke()

__invoke() metodi obyektni funksiya sifatida ishlatganda ishga tushadi.
Misol: 
```bash
<?php
class Talaba {
    private $ism;
    private $pochta;
 
    public function __construct($ism, $pochta) 
    {
        $this->ism = $ism;
        $this->pochta = $pochta;
    }
 
    public function __invoke()
    {
        echo 'Talaba obyekti funksiya sifatida ishlatildi';
    }
}
 
$talabaObyekt = new Student('Alisher', 'myemail9445@inbox.ru');
$talabaObyekt();
?>
```

__invoke() metodidan obyektni callable funksiya qilib ishlatishda foydalaniladi.

### __clone()
Mavjud obyektning duplikatini olishda `clone` kalit so'zidan foydalaniladi. Ammo, agar klon qilingandan keyin klon qilingan obyektning xususiyatlarini o'zgartirmoqchi bo'lsak, klass ichida __clone() ichki metodini e'lon qilgan bo'lishimiz kerak bo'ladi.
Misol:
```bash
<?php
Class Maktab_Oquvchisi {
}
 
class Oquvchi {
    private $ism;
    private $pochta;
    private $maktab_oquvchisi_obyekti;
 
    public function __construct()
    {
        $this->maktab_oquvchisi_obyekti = new Maktab_Oquvchisi();
    }
 
    public function __clone()
    {
        $this->maktab_oquvchisi_obyekti = clone $this->maktab_oquvchisi_obyekti;
    }
}
 
$oquvchiBirObyekt = new Oquvchi();
$oquvchiIkkiObyekt = clone $oquvchiBirObyekt;
?>
```

Obyektni clone kalit so'z bilan klon qilishda klass ichida __clone() metodining e'lon qilinishiga sabab clone bilan duplikat olishda sayoz nusxalash (shallow copy) amalga oshiriladi. Natijada esa klon olinayotgan obyektimiz ichidagi obyekt xususiyatlari klonlanmay qoladi.
Yuqoridagi misolda, agar biz __clone() metodini e'lon qilmaganimizda `$oquvchiIkkiObyekt` obyekti `$oquvchiIkkiObyekt`idagi `$maktab_oquvchisi_obyekti` ga murojaat qilayotgan bo'lardi.

### __debugInfo()
Bu metod obyektni `var_dump()` funksiyasida ishlatganda ishga tushadi. Agar bu metod e'lon qilinmagan bo'lsa var_dump() obyektning barcha public, protected, private xususiyatlarini ko'rsatadi. Shu sababli ham dump qilish paytida chiquvchi ma'lumotlarni cheklamoqchi bo'lsangiz, shu metoddan foydalansangiz bo'ladi.
Misol:
```bash
<?php
class Talaba {
    public $ism;
    private $pochta;
    private $ssn;
 
    public function __debugInfo() 
    {
        return array('talaba_ismi' => $this->ism);
    }
}
 
$talabaObyekt = new Talaba();
var_dump($talabaObyekt);
// object(Talaba)#1 (1) { ["talaba_ismi"]=> NULL } 
?>
```

Metod var_dump() funksiyasida obyekt chaqirilganda ko'rsatishi uchun kalit-qiymat juftligini qaytarishi kerak.

### __set_state()
__set_state() metodi `var_export()` funksiyasi bilan birgalikda ishlatiladigan static metod hisoblanadi. `var_export()` funksiyasi o'zgaruvchi haqida tuzilmaviy ma'lumot chiqarib beradi. Bu funksiya yordamida klasslarni eksport qilishda klass ichida, albatta, `__set_state()` metodi e'lon qilingan bo'lishi kerak.
```bash
<?php
class Talaba {
    public $ism;
    private $pochta;   
 
    public function __construct($ism, $pochta) 
    {
        $this->ism = $ism;
        $this->pochta = $pochta;
    }
 
    public static function __set_state(array $array) 
    {
        $obj = new Talaba;
        $obj->ism = $array['ism'];
        $obj->pochta = $array['pochta'];
 
        return $obj;
    }
}
 
$talabaObyekt = new Talaba('Alisher','myemail9445@inbox.ru');
var_export($talabaObyekt);
// Output: Talaba::__set_state(array( 'ism' => 'Alisher', 'pochta' => 'myemail9445@inbox.ru', ))
?>
```

### __autoload()
Noma'lum klassni yuklab olishga harakat qiladi.

```bash
<?php
//myClass.php
class myClass {
    public function __construct() {
        echo "myClass init'ed successfuly!!!";
    }
}
?>


<?php
./index.php
function __autoload($classname) {
    $filename = "./". $classname .".php";
    include_once($filename);
}

$obj = new myClass();
?>
```

Misol 2:
autoload ishlatmasdan
```bash
<?php
/**
 * file non_autoload.php
 */

require_once('project/class/A.php');
require_once('project/class/B.php');
require_once('project/class/C.php');
.
.
.

if (ConditionA) {
    $a = new A();
    $b = new B();
    $c = new C();
    // …
} else if (ConditionB) {
    $a = newA();
    $b = new B();
    // …
}
```

autoload bilan
```bash
<?php
/**
 * file autoload_demo.php
 */
function  __autoload($className) {
    $filePath = “project/class/{$className}.php”;
    if (is_readable($filePath)) {
        require($filePath);
    }
}

if (ConditionA) {
    $a = new A();
    $b = new B();
    $c = new C();
    // …
} else if (ConditionB) {
    $a = newA();
    $b = new B();
    // …
}
```
