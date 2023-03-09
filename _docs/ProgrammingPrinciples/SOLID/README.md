# SOLID

SOLID 5 ta object-oriented design (OOD) tamoyillarining birinchisi hisoblanadi. U dasturchilar uchun dasrtur tuzishda va ularni support qilishda qo'llanma bo'lib hisoblanadi. SOLID tamoyillarini qo'llash orqali kodning sifatini oshirish imkoniga ega bo'lasiz.

SOLID:

* S: Single Responsibility Principle
* O: Open-Closed Principle
* L: Liskov Substitution Principle
* I: Interface Segrigation Principle
* D: Dependency Inversion Principle

**Single Responsibility Principle**

> Klas faqatgina bitta vazifani bajarishi kerak

Faraz qilaylik User nomli klasimiz bor:

```bash
<?php

class User {
  
    private $email;
  
    // ...
  
    public function store() {
        // Atributlarni DB-ga saqlash kodi
    }
}
```

Bu yerdagi kodda `store` metodi noto'g'ri joylashtirilgan.  Chunki `User` klasi faqat user-ning ma'lumotlarini saqlashi mumkin. `Store` metodi esa DB bilan ham ishlaydi. Kodni `Single Responsibility Principle`-ga moslaymiz. Buning uchun `store` metodini alohida, baza bilan ishlaydigan klasga olib chiqamiz:

```bash
<?php

class User {
  
    private $email;
  
    // Getter and setter...
}
```

```bash
<?php

class UserDB {
  
    public function store(User $user) {
        // Atributlarni DB-ga saqlash kodi
    }
}
```

Hayotiy misol:

Matnli hujjatni ko'rsatadigan klasimiz bor bo'lsin. Unda title va content qismlari mavjud bo'lib, hujjatni HTML va PDF ko'rinishda eksport qilish kerak.

SRP-ning buzilgan ko'rinishi:

```bash
class Document
{
    protected $title;
    protected $content;

    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content= $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

        public function exportHtml() {
                echo "DOCUMENT EXPORTED TO HTML".PHP_EOL;
        echo "Title: ".$this->getTitle().PHP_EOL;
        echo "Content: ".$this->getContent().PHP_EOL.PHP_EOL;
        }

        public function exportPdf() {
                echo "DOCUMENT EXPORTED TO PDF".PHP_EOL;
        echo "Title: ".$this->getTitle().PHP_EOL;
        echo "Content: ".$this->getContent().PHP_EOL.PHP_EOL;
        }
}
```

SRP asosida yozilgan kodi.

Document klasi faqatgina hujjat haqida ma'lumot berishi kerak. Kodni SRP bo'yicha yozish uchun export amalini alohida chiqarish kerak bo'ladi.

Avval, Document obyektini qabul qiladigan interface ochamiz:

```bash
interface ExportableDocumentInterface
{
    public function export(Document $document);
}
```

Keyin esa, eksport qilishning biznes-logikasini alohida qilib olib chiqamiz:

```bash
class HtmlExportableDocument implements ExportableDocumentInterface
{
    public function export(Document $document)
    {
        echo "DOCUMENT EXPORTED TO HTML".PHP_EOL;
        echo "Title: ".$document->getTitle().PHP_EOL;
        echo "Content: ".$document->getContent().PHP_EOL.PHP_EOL;
    }
}
```

```bash
class PdfExportableDocument implements ExportableDocumentInterface
{
    public function export(Document $document)
    {
        echo "DOCUMENT EXPORTED TO PDF".PHP_EOL;
        echo "Title: ".$document->getTitle().PHP_EOL;
        echo "Content: ".$document->getContent().PHP_EOL.PHP_EOL;
    }
}
```

Document klasi quyidagi ko'rinishiga kelib qoladi:

```bash
class Document
{
    protected $title;
    protected $content;

    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content= $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
```

**Open-Closed Principle**

> Klas kengaytirish uchun ochiq, o'zgartirish uchun yopiq bo'lishi kerak.

Ya'ni, dasturning biror funksionalligini klasni o'zgartirish orqali amalga oshirmaslik kerak degani.

Misol uchun, tasavvur qiling, turli xildagi geometrik shakllarning yuzasini hisoblovchi dastur tuzyapmiz. Bunda `AreaCalculator` degan shakl yuzasini hisoblovchi klasimiz bor.

Yuzani hisoblashdagi muammoyimiz - turli xildagi shakl yuzasini turli usulda hisoblash kerak bo'ladi:

```bash
<?php

class Rectangle {
  
    public $width;
    public $height;
  
    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }
}

class Square {
  
    public $length;
  
    public function __construct($length) {
        $this->length = $length;
    }
}


class AreaCalculator {
  
    protected $shapes;
  
    public function __construct($shapes = array()) {
        $this->shapes = $shapes;
    }
  
    public function sum() {
        $area = [];
    
        foreach($this->shapes as $shape) {
            if($shape instanceof Square) {
                $area[] = pow($shape->length, 2);
            } else if($shape instanceof Rectangle) {
                $area[] = $shape->width * $shape->height;
            }
        }
  
        return array_sum($area);
    }
}
```

Agar, biror bir boshqa shakl, masalan `Circle`-ni qo'shmoqchi bo'lsak, `AreaCalculator` klasidagi `sum` metodini o'zgartirishimizga to'g'ri keladi. Bu esa, `open-closed` tamoyiliga mos emas.

Yechim esa oddiy: `Shape` degan interface ochamiz va unda shakl yuzasini hisoblash uchun metod qo'shamiz. `AreaCalculator` klasida esa shu metodni ishlatamiz. Agar yangi shakl qo'shish kerak bo'lsa shunchaki `Shape` interface-ini ishlatib ketsak bo'lgani:

```bash
<?php

interface Shape {
    public function area();
}

class Rectangle implements Shape {
  
    private $width;
    private $height;
  
    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }
  
    public function area() {
        return $this->width * $this->height;
    }
}

class Square implements Shape {
  
    private $length;
  
    public function __construct($length) {
        $this->length = $length;
    }
  
    public function area() {
        return pow($this->length, 2);
    }
}


class AreaCalculator {
  
    protected $shapes;
  
    public function __construct($shapes = array()) {
        $this->shapes = $shapes;
    }
  
    public function sum() {
        $area = [];
    
        foreach($this->shapes as $shape) {
            $area[] = $shape->area();
        }
  
        return array_sum($area);
    }
}
```

Hayotiy misol.

Faraz qilaylik, login qilishni amalga oshirishimiz kerak. Agar, faqat login va password bilan tizimga kirish kerak bo'lsa muammo bo'lmaydi. Lekin, agar Twitter yoki Gmail bilan login qilish kerak bo'lsa nima qilish kerak?

OCP-ni buzgan holda yozilgan kod:

```bash
class LoginService
{
    public function login($user)
    {
        if ($user instanceof User) {
            $this->authenticateUser($user);
        } else if ($user instanceOf ThirdPartyUser) {
            $this->authenticateThirdPartyUser($user);
        }
    }
}
```

OCP asosida yozilgan kod:

Avvalo, login uchun interface ochib olamiz:

```bash
interface LoginInterface
{
    public function authenticateUser($user);
}
```

Endi, yuqorida login uchun yaratilgan klas kodini interface ishlatgan holda ajratib chiamiz:

```bash
class UserAuthentication implements LoginInterface
{
    public function authenticateUser($user)
    {
        // login va password bilan auth uchun authenticateUser() methodi kodi.
    }
}
```

```bash
class GmailAuthentication implements LoginInterface
{
    public function authenticateUser($user)
    {
        // Gmail uchun authenticateUser() methodi kodi.
    }
}
```

```bash
class LoginService
{
    public function login(LoginInterface $user)
    {
        $user->authenticateUser($user);
    }
}
```

Endi, LoginService berilgan obyektga qarab authentication-ni ishlatadi.

**Liskov Substitution Principle**

> Bola klas obyektlari tizim funksionalligini o'zgartirmagan holda o'zining ota klas obyektlari o'rinini bosa olishi kerak.

Barbara Liskov tomonidan ta'riflab berilgan.

Oddiy misol:

```bash
class A
{
    public function doSomething(){}
}
class B extends A
{

}
```

Ikkita A va B degan klaslarimiz bor. B klas A klasdan meros oladi. Liskov almashtirish tamoyiliga ko'ra A klas obyektini B klas obyekti bilan almashtirib bo'lishi kerak.

Endi, to'rtburchak va kvadrat masalasini ko'raylik:

```bash
<?php
// to`rtburchak va kvadrat masalasi
class Rectangle
{
  protected $width;
  protected $height;

  public function setHeight($height)
  {
    $this->height = $height;
  }
  public function getHeight()
  {
    return $this->height;
  }
  public function setWidth($width)
  {
    $this->width = $width;
  }
  public function getWidth()
  {
    return $this->width;
  }
  public function area()
  {
    return $this->height * $this->width;
  }
}
class Square extends Rectangle
{
  public function setHeight($value)
  {
    $this->width = $value;
    $this->height = $value;
  }
  public function setWidth($value)
  {
    $this->width = $value;
    $this->height = $value;
  }
}
class AreaTester
{
  private $rectangle;
  public function __construct(Rectangle $rectangle)
  {
    $this->rectangle = $rectangle;
  }
  public function testArea($width,$height)
  {
    $this->rectangle->setHeight($width);
    $this->rectangle->setWidth($height);
    return $this->rectangle->area();
  }
}
$rectangle = new Rectangle();
$rectangleTest = new AreaTester($rectangle);
$rectangleTest->testArea(2,3); // 6 chiqadi
$squre = new Square();
$rectangleTest = new AreaTester($squre);
$rectangleTest->testArea(2,3); // 6 chiqishi kerak, 9 chiqadi
```

Yuqoridagi kodda, Rectangle klasidan meros olib Square klasini yaratyapmiz va Rectangle-ning metodlarini override qilinyapti. Lekin bu kodlar Liskov almashtirish tamoyilini buzyapti. Chunki, bu kodda Rectangle-dan meros olgan Square obyekti Rectangle obyekti o'rnini bosa olmaydi. Agar, Rectangle-ni Square bilan almashtirsak, Rectangle-ning yuzasini hisoblash noto'g'ri bajariladi.

LSP asosida yozilgan kod:

```bash
<?php
abstract class AbstractShape
{
    abstract public function Area() : int;
}
class Rectangle extends AbstractShape
{
    private $height;
    private $width;
    public function setHeight(int $height)
    {
        $this->height = $height;
    }
    public function setWidth(int $width)
    {
        $this->width = $width;
    }
    public function Area() : int
    {
        return $this->height * $this->width;
    }
}
class Square extends AbstractShape
{
    private $sideLength;
    public function setSideLength(int $sideLength)
    {
        $this->sideLength = $sideLength;
    }
    public function Area() : int
    {
        return $this->sideLength * $this->sideLength;
    }
}

class AreaTester
{
  private $shape;
  public function __construct(AbstractShape $shape)
  {
    $this->shape = $shape;
  }
  public function testArea($width,$height)
  {
    return $this->shape->Area();
  }
}
```

**Interface Segrigation Principle**

> Klas hech qachon o'ziga kerak bo'lmaga interfeysni ishlatmasligi kerak

Ya'ni, klas o'ziga kerak bo'lmagan metodlarni ishlatmasligi kerak.

Misol ko'raylik. Bizda FutureCar degan klas bor. Unda, ham fly, ham drive metodlari mavjud:

```bash
interface VehicleInterface {
    public function drive();
    public function fly();
}

class FutureCar implements VehicleInterface {
  
    public function drive() {
        echo 'Driving a future car!';
    }
  
    public function fly() {
        echo 'Flying a future car!';
    }
}

class Car implements VehicleInterface {
  
    public function drive() {
        echo 'Driving a car!';
    }
  
    public function fly() {
        throw new Exception('Not implemented method');
    }
}

class Airplane implements VehicleInterface {
  
    public function drive() {
        throw new Exception('Not implemented method');
    }
  
    public function fly() {
        echo 'Flying an airplane!';
    }
}
```

Bu yerdagi muammo - Car va Airplane klaslari hamma metodlarni ham ishlatmaydi. Bu muammoning yechimi esa, interface-ni bo'lib chiqish hisoblanadi:

```bash
<?php

interface CarInterface {
    public function drive();
}

interface AirplaneInterface {
    public function fly();
}

class FutureCar implements CarInterface, AirplaneInterface {
  
    public function drive() {
        echo 'Driving a future car!';
    }
  
    public function fly() {
        echo 'Flying a future car!';
    }
}

class Car implements CarInterface {
  
    public function drive() {
        echo 'Driving a car!';
    }
}

class Airplane implements AirplaneInterface {
  
    public function fly() {
        echo 'Flying an airplane!';
    }
}
```

**Dependency Inversion**

> Klas boshqa klasga to'g'ridan to'g'ri emas, balki uning abstraksiyasiga bog'liq bo'lishi kerak

Ya'ni, biror klas metodida boshqa klasni to'g'ridan to'g'ri inject qilmasdan, uning abstrakt klasi yoki interfeysi orqali chaqirish kerak.

Masalan, UserDB klasi DB connection bilan bog'liq holda ishlaydi:

```bash
<?php

class UserDB {
  
    private $dbConnection;
  
    public function __construct(MySQLConnection $dbConnection) {
        $this->$dbConnection = $dbConnection;
    }
  
    public function store(User $user) {
        // Ma`lumotni bazaga saqlash
    }
}
```

Yuqoridagi kodda, UserDB klas MySQL database-ga to'g'ridan to'g'ri bog'lanyapti. Bu degani, agar DB-ni boshqasiga almashtirishga to'g'ri kelsa, UserDB klasni o'zgartirish kerak bo'ladi. Bu esa Open-Close Principle-ni buzadi.

Bu muammo yechimi - DB connection uchun abstract klas yaratish hisoblanadi:

```bash
<?php

interface DBConnectionInterface {
    public function connect();
}

class MySQLConnection implements DBConnectionInterface {
  
    public function connect() {
        // MySQL connection qaytariladi...
    }
}

class UserDB {
  
    private $dbConnection;
  
    public function __construct(DBConnectionInterface $dbConnection) {
        $this->$dbConnection = $dbConnection;
    }
  
    public function store(User $user) {
        // Ma`lumotni bazaga saqlash
    }
}
```
