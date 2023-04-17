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
