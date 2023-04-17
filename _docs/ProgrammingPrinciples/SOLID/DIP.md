# Dependency Inversion Principle

DIP ning ikkita asosiy qoidasi bor:

* Yuqori darajadagi modullar (klaslar) quyi darajadagi modullarga (klaslarga) bog'liq bo'lmasligi kerak. Ikkalasi ham abstaraksiyaga bog'liq bo'lishi kerak.
* Abstraksiya detallarga bog'liq bo'lmasligi kerak. Detallar abstraksiyaga bog'liq bo'lishi kerak.

Real misol bilan aytganda, biror metodga VideoPlayer nomli aniq bitta klasning o'rniga, VideoPlayerInterface ni ishlatgan obyektni qabul qilishi kerak. Shu yo'l bilan bitta metodga Video Playerning turli xil ko'rinishlarini berishimiz mumkin bo'ladi.

**Misol**

*DIPning buzilgan ko'rinishi*

Quyidagi misolda, `SendWelcomeMessage` klasi konstruktorida `Mailer` klasini dependency injection ko'rinishida qabul qiladi. Bu esa DIPga to'g'ri kelmaydi. Chunki, metod abstractsiyaga emas, aniq bir klasga bog'liq bo'lib qolgan:

```php
class Mailer
{
// Mailer klasi kodlari
}

class SendWelcomeMessage
{
	public function __construct(private Mailer $mailer)
	{
	}
}
```

*DIPning to'g'ri ishlatilishi*

Yuqoridagi kodni DIPga moslab yozish uchun konstruktorga inject qilinuvchi argumentning type hintini umumiylashtiramiz (abstraksiyalaymiz). Misolimizda, `MailerInterface` nomli umumiy tip yaratib, unda `send` nomli umumiy metod e'lon qilamiz:

```php
interface MailerInterface
{
	public function send();
}

class SmtpMailer implements MailerInterface
{
	public function send()
	{
		// SMTP orqali xabar jo'natish
	}
}

class SendSlackMailer implements MailerInterface
{
	public function send()
	{
		// Slack orqali xabar jo'natish
	}
}

class SendWelcomeMessage
{
	public function __construct(private MailerInterface $mailer) {}
}
```

2-misol

Faraz qilaylik, Databasedagi ma'lumotlarni olib budjet hisobotini shakllantirib beruvchi tizim bor bo'lsin. Masalan, quyidagicha ko'rinishga ega:

```php
class BudgetReport {
    public $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function open(){
        $this->database->get();
    }

    public function save(){
        $this->database->insert();
    }
}

class MySQLDatabase {
    // fields

    public function get(){
        // get by id
    }

    public function insert(){
        // inserts into db
    }

    public function update(){
        // update some values in db
    }

    public function delete(){
        // delete some records in db
    }
}

// Client
$database = new MySQLDatabase();
$report = new BudgetReport($database);

$report->open();
```

Bu kod hech qanday muammosiz ishlaydi. Lekin, u DIPga mos kelmaydi. Chunki, `BudgetReport` yuqori darajadagi modul quyi darajadagi `MySQLDatabase` moduliga bog'liq bo'lib qolyapti. Bu yana OCPni ham buzadi. Chunki, agar biz MongoDB kabi boshqa turdagi biror database bilan ishlashga to'g'ri kelishi ham mumkin.

Bu kamchilikni tuzatish uchun aniq bitta database klasni emas umumlashtirilgan abstraksiyani ishlatishimiz kerak bo'ladi. Buning uchun har qanday database klas ishlatadigan `DatabaseInterface` nomli interface yaratamiz va shu interface orqali database klaslarimizni konstruktorga inject qilamiz.

```php
interface DatabaseInterface {
    public function get();
    public function insert();
    public function update();
    public function delete();
}

class MySQLDatabase implements DatabaseInterface {
    // fields

    public function get(){
        // get by id
    }

    public function insert(){
        // inserts into db
    }

    public function update(){
        // update some values in db
    }

    public function delete(){
        // delete some records in db
    }
}

class MongoDB implements DatabaseInterface {
    // fields

    public function get(){
        // get by id
    }

    public function insert(){
        // inserts into db
    }

    public function update(){
        // update some values in db
    }

    public function delete(){
        // delete some records in db
    }
}

class BudgetReport {
    public $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function open(){
        $this->database->get();
    }

    public function save(){
        $this->database->insert();
    }
}

// Client
$mysql = new MySQLDatabase();
$report_mysql = new BudgetReport($mysql);

$report_mysql->open();

$mongo = new MongoDB();
$report_mongo = new BudgetReport($mongo);

$report_mongo->open();
```

Endi, `BudgetReport` klasimiz aynan bitta database klasiga bog'lanib qolmadi.
