# Laravelda testlash

Dasturni yaratish, uni keyinchalik support qilish jarayoni ancha murakkab jarayon hisoblanadi. Shuning uchun ham, bu jarayonda dasturdagi kamchiliklarni oldini olish va bartaraf etishda testlash muhim.

Quyidagicha testlash usullari mavjud:

* Manual testing
* Unit testing
* Feature testing
* End to End (E2E)

Ro'yxatdagi testlashning eng keng tarqalgan usuli - bu manual testing, ya'ni qo'lda testlash. Bunda dasturchining o'zi kod bo'ylab bitta bitta yurib, tekshirib chiqadi. 

Qolgan uchta testlash esa auto-testing hisoblanib, ularda dasturchi qatnashmaydi.

 Auto-testing-ning asosiy foydali tomoni tez bajarilshi va inson omilining pastligida. Bu foyda ayniqsa katta projectlarda bilinadi.

Auto-testing-ning mohiyati har safar project-ga biror xususiyat (feature yoki funksionallik) qo'shganda dasturchi auto-testing script-ini ishga tushirib kod ishlashini tekshirib oladi. Dasturchi anchagina kod yozganidan keyin, qaytib kelib dastlabki yozgan kodini qo'lda qaytadan tekshirib o'tirishi shart bo'lmaydi.

##### Unit testing

Unit testing-da kodning eng kichik qismlari test-lanadi. Dastudagi kodning eng kichik qismi esa **function** hisoblanadi. Ya'ni, dasturga qo'shilgan xususiyat ko'plab function-lardan tashkil topgan bo'ladi. Dasturning biror xususiyatini (funsionalligini) unit test-dan o'tkazganda shu xususiyatga tegishli barcha function-lar testlab chiqiladi.

Ammo auto-testing yaxshi o'tdi degani, shu test qilingan funksionallik kutilganidek to'g'ri ishlayapti degani emas. Chunki dasturdagi biror funksionallik xatosiz ishlashi mumkin, lekin biz kutgan natijani bermasligi mumkin. Ya'ni unda biror mantiqiy xato chiqishi mumkin.

Unit testing tez ishlashi mumkin, lekin juda ham ishonchli testlash usuli emas.

##### Feature testing

Feature testing unit testing-ga solishtirganda test-lashga yuqori darajadagi yondashuv hisoblanadi. Feature testing ko'proq e'tiborni har bir function-ga emas, butun funksionallikka qaratadi. Shu sababli ham ushbu testlash unit testing-ga nisbatan ancha ishonchli hisoblanadi.

Misol uchun, maqola yaratish funksiyasini ko'rib chiqaylik.

Unit testing holatida, misol uchun, quyidagi qismlarni ko'rib chiqishimiz mumkin:

* maqolaning title-ini kiritib bilamizmi?
* maqolaning asosiy matnini kiritib bilamizmi?
* bu maydon matn yoki son yoki massiv qabul qiladimi?
* Noto'g'ri ma'lumot turi (data type) bersak, xatolik (Exception) qaytaradimi?
* va shu kabilar

Feature testing-da esa umuman boshqa narsalarni ko'rib chiqamiz. Ushbu turdagi testni qiziqtiradigan yagona narsa maqolani database-ga yozib bildikmi yoki yo'q. Feature testing-da ma'lumotni database-ga yozib, yangi yaratilgan record-ni qaytarib oladi va undan title hamda body maydonlari to'g'ri kiritilganligini tekshiradi.

##### End to End (E2E)

E2E testing - bu butun dasturni boshlanishidan oxirigacha tekshiruvchi testlash. E2E testing-da dasturdaning boshqa tizimlarga bog'liqliklari, aloqalari va ma'lumotlarning integratsiyasini testlanadi. Bu testlash turi feature testing-dan ham yuqoriroq darajada hisoblanadi. Bunda test o'zini xuddi oddiy foydalanuvchiday tutib, foydalanuvchilar bajarib ko'radigan barcha amallarni boshdan oyoq bajarib chiqadi.

E2E testing feature testing-ga o'xshab, alohida function-larni emas, dasturdan chiquvchi natijaga e'tibor beradi.

E2E testing ancha ishonchli test-lash turi hisoblansada, ishlatish juda qiyin.

# Test-lash turlari bo'yicha umumiy xulosa

|                 | Ishonchlilik | Tezlik     |
| --------------- | ------------ | ---------- |
| Manual testing  | ?            | juda sekin |
| Unit testing    | o'rtacha     | tez        |
| Feature testing | yaxshi       | sekinroq   |
| E2E testing     | juda yaxshi  | sekin      |

![1675422378700](image/README/1675422378700.png)

Ushbu darsda, Laravel-da unit testing va feature testing yozishni ko'ramiz.

# Amaliyot

Laravel-da barcha testlash uchun mo'ljallangan fayllar `tests` papkasida joylashadi.

Laravel phpunit paketidan foydalanib test-lashni bajaradi. Testlashning asosiy sozlamalari `root` papkasidagi `phpunit.xml` faylida berilgan.

`test/TestCase.php` faylidagi `TestCase` abstract klasi testni yaratib beradi. Bu klas har bir alohida test klasga ota klas qilib berilishi kerak.

`tests/CreateApplication.php` fayldagi `CreateApplication` trait-i mock app-ni yaratib beradi.

Ishni avval, unit test-ni ko'rishdan boshlaymiz. Buning uchun unit test yozib ko'ramiz:

`php artisan make:test --unit PostRepositoryTest`

Bu buyruq `tests/Unit` papkasida `PostRepositoryTest.php` faylini yaratib beradi:

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PostRepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }
}

```
