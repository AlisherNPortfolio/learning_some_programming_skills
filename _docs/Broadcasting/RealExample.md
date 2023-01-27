### Laravel Broadcasting Example

#### Tavsif

Bugun Laravel-da broadcasting qilishni ko'ramiz. Biror Laravel freymvorkida qilingan project-da server-da biror hodisa yuz bergan payt bu haqida mijoz qismiga (browser-ga) xabar jo'natishda broadcasting-dan foydalanamiz. Buning uchun Pusher kutubxonasidan foydalanamiz.

Project-ning server qismida biror hodisa sodir bo'lganda, bu haqida frontend qismiga xabar jo'natish kerak bo'ladigan bo'lsa, demak sizga aynan broadcasting kerak bo'ladi.

Misol uchun, aytaylik biz foydalanuvchilar o'zaro xabar almashadigan tizim qilmoqchimiz. Ya'ni, A foydalanuvchi B foydalanuvchiga xabar jo'natgan paytida, biz B foydalanuvchiga o'sha zahoti yuborilgan xabarni ko'rsatishimiz kerak bo'lsin. Bunda, yangi xabarni B foydalanuvchiga popup bilan chiqarib berishimiz kerak.

Bunda asosiy ishni websocket-lar bajaradi. Shu sababli ishni boshlashdan oldin websocket-lar bilan bo'ladigan jarayon haqida biroz ma'lumotga ega bo'lib olaylik:

* Avval, bizga websocket protokoli ishlaydigan va mijoz bu websocket bilan bog'lana oladigan web server kerak bo'ladi.
* Biz websocket ishlata oladigan webserver ko'tarishimiz yoki websocket xizmatini ko'rsatadigan qo'shimcha service-dan foydalanishimiz mumkin (xuddi Pusher singari). Misolimizda ikkinchi variantdan foydalanamiz.
* Mijoz websocket bog'lanishni websocket serverga intisialization qilib, unique ID-ni qabul qilib oladi.
* Bog'lanish tugallangandan so'ng, mijoz event-larni qabul qilishi kerak bo'lgan birorta kanalga ulanadi.
* Mijoz o'zi ulangan kanalda o'zi kuzatmoqchi bo'lgan kanalini register qilib oladi.
* Server tomonida esa, biror hodisa sodir bo'lganda, u bu haqida kanal va event nomi orqali websocket server-ni xabardor qiladi.
* Va nihoyat, websocket server event-ni frontend qismidagi kanal bo'ylab broadcast qilishni boshlaydi.

#### Broadcast-ning sozlamalar fayli

Quyidagi `config/broadcasting.php` faylida broadcasting-ning odatiy sozlamalari berilgan:

```php
<?php
return [
    /* 
|-------------------------------------------------------------------------- 
| Default Broadcaster 
|-------------------------------------------------------------------------- 
| 
| This option controls the default broadcaster that will be used by the 
| framework when an event needs to be broadcast. You may set this to 
| any of the connections defined in the "connections" array below. 
| 
| Supported: "pusher", "redis", "log", "null" 
| 
*/
    'default' => env('BROADCAST_DRIVER', 'null'),
    /* 
|-------------------------------------------------------------------------- 
| Broadcast Connections 
|-------------------------------------------------------------------------- 
| 
| Here you may define all of the broadcast connections that will be used 
| to broadcast events to other systems or over websockets. Samples of 
| each available type of connection are provided inside this array. 
| 
*/
    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ],
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],
        'log' => [
            'driver' => 'log',
        ],
        'null' => [
            'driver' => 'null',
        ],
    ],
];
```

Laravel, bir nechta asosiy broadcast driver-laridan foydalana oladi.

Hozirgi misolimizda `pusher`-dan foydalanamiz. Debugging qilishda `log` driver-idan ham foydalanish mumkin. Lekin, bunda frontend qismiga hech qanday ma'lumot yuborilmaydi. Barcha broadcasting qilinayotgan ma'lumotlar `laravel.log` faylida yozib boriladi.

#### Broadcasting uchun qo'yiladigan talablar

Broadcasting-da uch xil turdagi kanallar bo'ladi: public, private va presence. Agar event-larni ochiq holda broadcast qilmoqchi bo'lsak, public turdagi kanaldan foydalanamiz. Agar aksincha, faqat ma'lum doirada broadcast qilinadigan event-lar bo'lsa, u holda private kanallardan foydalaniladi.

Bizning holatimizda, foydalanuvchilar bir biri bilan ma'lumot almashishi uchun ular tizimga kirishlari kerak bo'ladi. Shu sababli ham, biz private kanaldan foydalanamiz.

#### Authentication qismini tayyorlash

Ishni boshlashdan avval, register, login qismlarini qilib qo'ygan bo'lishimiz kerak. Bugungi asosiy ishimiz broadcasting bo'lgani uchun authentication qismini ko'rib o'tirmaymiz. Tayyorlab qo'yilganidan foydalanamiz.

#### Pusher SDK: o'rnatish va sozlash

Pusher-dan foydalanish uchun, eng avvalo, pusher service-dan [account ](https://dashboard.pusher.com/accounts/sign_up)ochishimiz kerak.

Keyin, Laravel project-imizda Pusher PHP SDK-ni o'rnatish kerak. Bu kutubxona yordamida server qismida event ishga tushganda bu haqida frontend qismiga xabar yuboriladi.

Laravel root papkasida quyidagi buyruq ishga tushiriladi: `composer require pusher/pusher-php-server "~3.0"`

Endi, `.env` faylda pusher sozlamalarini to'g'rilab chiqamiz:

```apache
BROADCAST_DRIVER=pusher
PUSHER_APP_ID={YOUR_APP_ID}
PUSHER_APP_KEY={YOUR_APP_KEY}
PUSHER_APP_SECRET={YOUR_APP_SECRET}
PUSHER_APP_CLUSTER={YOUR_APP_CLUSTER}
```

Bundan tashqari, pusher account-ini ochganimizda berilgan sozlamalarni ham qo'yib chiqishimiz kerak.

Va nihoyat, `config/app.php` faylda broadcasting service-ni yoqib qo'yamiz (izohdan chiqarib qo'yiladi):

```php
App\Providers\BroadcastServiceProvider::class,
```

Shu yergacha, server tomonida ishlaydigan kutubxonani o'rnatdik.

#### Pusher va Laravel Echo kutubxonalari - o'rnatish, sozlash

Broadcasting paytida, frontend qismining vazifasi kanallarga ulanish va biror event-ni kuzatish hisoblanadi. Aslida, u bu ishlarni websocket serverga yangi kanal ochib bajaradi.

Frontend-da bu ishlarni qilish muammo emas. Laravel-da bu oldindan qilib qo'yilgan. Bu vazifa frontend qismida Laravel Echo kutubxonasi orqali qilinadi.

Laravel Echo kutubxonasi NPM paket menejeri yordamida o'rnatiladi: `npm install laravel-echo`

Qiladigan yana bitta ishimiz, `node_modules/laravel-echo/dist/echo.js` faylini `public/echo.js` fayliga nusxalash bo'ladi.

Nihoyat, frontend qismidagi kutubxonalarni ham o'rnatib bo'ldik.

#### Backend-da kerakli kodlarni yozish

Shu yergacha kerakli barcha kutubxonalarni o'rnatdik va ularning sozlamalarini ham to'g'rilab chiqdik. Endi esa, asosiy qismga o'tamiz.

##### Model yaratish

Boshlanishiga, yuborilgan xabarlarni saqlab turuvchi `Message` modelini yaratib olamiz:

`php artisan make:model Message --migration`
