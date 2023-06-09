# Laravel Broadcasting Example

# Tavsif

Bugun Laravel-da broadcasting qilishni ko'ramiz. Biror Laravel freymvorkida qilingan project-da server-da biror hodisa yuz bergan payt bu haqida mijoz qismiga (browser-ga) xabar jo'natishda broadcasting-dan foydalanamiz. Buning uchun Pusher kutubxonasidan foydalanamiz.

Project-ning server qismida biror hodisa sodir bo'lganda, bu haqida frontend qismiga xabar jo'natish kerak bo'ladigan bo'lsa, demak sizga aynan broadcasting kerak bo'ladi.

Misol uchun, aytaylik biz foydalanuvchilar o'zaro xabar almashadigan tizim qilmoqchimiz. Ya'ni, A foydalanuvchi B foydalanuvchiga xabar jo'natgan paytida, biz B foydalanuvchiga o'sha zahoti yuborilgan xabarni ko'rsatishimiz kerak bo'lsin. Bunda, yangi xabarni B foydalanuvchiga popup bilan chiqarib berishimiz kerak.

Bunda asosiy ishni websocket-lar bajaradi. Shu sababli ishni boshlashdan oldin websocket-lar bilan bo'ladigan jarayon haqida biroz ma'lumotga ega bo'lib olaylik:

- Avval, bizga websocket protokoli ishlaydigan va mijoz bu websocket bilan bog'lana oladigan web server kerak bo'ladi.
- Biz websocket ishlata oladigan webserver ko'tarishimiz yoki websocket xizmatini ko'rsatadigan qo'shimcha service-dan foydalanishimiz mumkin (xuddi Pusher singari). Misolimizda ikkinchi variantdan foydalanamiz.
- Mijoz websocket bog'lanishni websocket serverga intisialization qilib, unique ID-ni qabul qilib oladi.
- Bog'lanish tugallangandan so'ng, mijoz event-larni qabul qilishi kerak bo'lgan birorta kanalga ulanadi.
- Mijoz o'zi ulangan kanalda o'zi kuzatmoqchi bo'lgan kanalini register qilib oladi.
- Server tomonida esa, biror hodisa sodir bo'lganda, u bu haqida kanal va event nomi orqali websocket server-ni xabardor qiladi.
- Va nihoyat, websocket server event-ni frontend qismidagi kanal bo'ylab broadcast qilishni boshlaydi.

# Broadcast-ning sozlamalar fayli

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

Hozirgi misolimizda `pusher`\-dan foydalanamiz. Debugging qilishda `log` driver-idan ham foydalanish mumkin. Lekin, bunda frontend qismiga hech qanday ma'lumot yuborilmaydi. Barcha broadcasting qilinayotgan ma'lumotlar `laravel.log` faylida yozib boriladi.

# Broadcasting uchun qo'yiladigan talablar

Broadcasting-da uch xil turdagi kanallar bo'ladi: public, private va presence. Agar event-larni ochiq holda broadcast qilmoqchi bo'lsak, public turdagi kanaldan foydalanamiz. Agar aksincha, faqat ma'lum doirada broadcast qilinadigan event-lar bo'lsa, u holda private kanallardan foydalaniladi.

Bizning holatimizda, foydalanuvchilar bir biri bilan ma'lumot almashishi uchun ular tizimga kirishlari kerak bo'ladi. Shu sababli ham, biz private kanaldan foydalanamiz.

# Authentication qismini tayyorlash

Ishni boshlashdan avval, register, login qismlarini qilib qo'ygan bo'lishimiz kerak. Bugungi asosiy ishimiz broadcasting bo'lgani uchun authentication qismini ko'rib o'tirmaymiz. Tayyorlab qo'yilganidan foydalanamiz.

# Pusher SDK: o'rnatish va sozlash

Pusher-dan foydalanish uchun, eng avvalo, pusher service-dan [account](https://dashboard.pusher.com/accounts/sign_up) ochishimiz kerak.

Keyin, Laravel project-imizda Pusher PHP SDK-ni o'rnatish kerak. Bu kutubxona yordamida server qismida event ishga tushganda bu haqida frontend qismiga xabar yuboriladi.

Laravel root papkasida quyidagi buyruq ishga tushiriladi: `composer require pusher/pusher-php-server "~3.0"`

Endi, `.env` faylda pusher sozlamalarini to'g'rilab chiqamiz:

```
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

# Pusher va Laravel Echo kutubxonalari - o'rnatish, sozlash

Broadcasting paytida, frontend qismining vazifasi kanallarga ulanish va biror event-ni kuzatish hisoblanadi. Aslida, u bu ishlarni websocket serverga yangi kanal ochib bajaradi.

Frontend-da bu ishlarni qilish muammo emas. Laravel-da bu oldindan qilib qo'yilgan. Bu vazifa frontend qismida Laravel Echo kutubxonasi orqali qilinadi.

Laravel Echo kutubxonasi NPM paket menejeri yordamida o'rnatiladi: `npm install laravel-echo`

Qiladigan yana bitta ishimiz, `node_modules/laravel-echo/dist/echo.js` faylini `public/echo.js` fayliga nusxalash bo'ladi.

Nihoyat, frontend qismidagi kutubxonalarni ham o'rnatib bo'ldik.

#### Backend-da kerakli kodlarni yozish

Shu yergacha kerakli barcha kutubxonalarni o'rnatdik va ularning sozlamalarini ham to'g'rilab chiqdik. Endi esa, asosiy qismga o'tamiz.

# Model yaratish

Boshlanishiga, yuborilgan xabarlarni saqlab turuvchi `Message` modelini yaratib olamiz:

`php artisan make:model Message --migration`

Ushbu model-da `from`, `to` va `message` maydonlari bo'ladi. Shunga ko'ra `Message` modeliga tegishli migration-imiz quyidagi ko'rinishda bo'ladi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('from', false, true);
            $table->integer('to', false, true);
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
```

Bu migration-ni database-ga yozish uchun migration buyrug'ini ishga tushiramiz.

# Event yaratish

Endi, broadcast qilish uchun event yaratishimiz kerak. Event-ning turiga qarab laravel kerakli amallarni o'zi bajaradi. Agar oddiy event ishga tushadigan bo'lsa, Laravel uni tinglab turgan listener-ni ishga tushiradi. Aks holda, agar broadcasting event bo'ladigan bo'lsa, bu event-ni `config/broadcasting.php` orqali sozlangan websocket-ga uzatadi.

Bizning misolimizda, sozlamalarda Pusher sozlanganligi sababli, event-lar Pusher server-iga yuboriladi.

Xabar yuborilganini bildiruvchi event-ni yaratamiz: `php artisan make:event NewMessageNotification`. Yaratgan `App\Events\NewMessageNotification` event-ini quyidagicha o'zgartiramiz:

```php
<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user' . $this->message->to); // <== PrivateChannel-da yuboramiz
    }
}
```

Bu yerdagi eng muhim jihatlardan biri bu - \`ShouldBroadcastNow\` interface-ining ishlatilishi. Bu interface ishlatilganidan keyin Laravel bu event-ni broadcast qilish kerakligini tushunadi.

O'zi, aslida, ShouldBroadcast interface-ni ishlatishimiz ham mumkin edi. Bunda laravel event-ni  event queue-ga qo'shadi va event queue worker event-ning ishlashiga imkon bo'lishi bilan uni ishga tushiradi. Ammo, bizning holatda, event-larni xabar yuborilishi bilan broadcast qilishimiz kerak bo'lgani uchun, ShouldBroadcastNow interface-ini ishlatamiz.

Foydalanuvchiga kelgan xabarni darhol ko'rsatish uchun event constructor-iga `Message` modelini argument qilib berdik. Bundan tashqari event-ni nomi bo'yicha broadcast qilish uchun `broadcastOn` metodini e'lon qilamiz. Bu metod-da event-ni oldinroq aytganimizdek private turdagi kanalda broadcast qilamiz. Kanalning nomini `user{USER_ID}` ko'rinishida beramiz.

# Broadcast route-larini yaratish

Private kanal ishlatganda websocket-ga bog'lanish uchun, foydalanuvchi avval tizimga kirgan (login qilgan) bo'lishi kerak bo'ladi. Bu esa, private kanalda broadcast qilayotgan event-larni faqat tizimga kirgan foydalanuvchilarga yetib borishini kafolatlaydi. Bundan tashqari bizning holatimizda, kanal nomi tizimga kirgan foydalanuvchining ID-si yordamida yaratiladi (`user{USER_ID}`).

Laravel Echo tizimga kirgan foydalanuvchilar bilan ishlashga moslashtirilgan. Bunda foydalanuvchining tizimga kirganini tekshirib yurish shart emas. Laravel Echo-ning o'zi shug'ullanadi. Biz faqat route-ni yaratsak yetarli.

`routes/channels.php` faylida quyidagicha route-larni yozamiz:

```php
<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{toUserId}', function ($user, $toUserId) {
    return $user->id === $toUserId;
});
```

O'zimizning private kanalimiz uchun `user.{toUserId}` nomli route yaratdik. Route-ning closure qismidagi birinchi argument bu - tizimga kirgan foydalanuvchi modeli ma'lumotlari bo'lsa, ikkinchi argument kanal orqali ma'lumot xabar jo'natgan foydalanuvchining ID-si. Bu ID kanal nomidan chiqarib olingan.

Foydalanuvchi `user.{USER_ID}` private kanalga subscribe qilishmoqchi bo'lganida, Laravel Echo-ning o'zi orqa fonda XMLHttpRequest yordamida tizimga kirishni amalga oshiradi.

Shu yergacha broadcastingni sozlash va o'rnatishni tugatdik. Endi uni test qilib ko'ramiz.

# Controller yaratish

`MessageController` controllerini yaratamiz:

```php
<?php

namespace App\Http\Controllers\Broadcasting;

use App\Events\NewMessageNotification;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::user()->id;
        $data = ['user_id' => $userId];

        return view('broadcasting.index', $data);
    }

    public function send()
    {
        // ...

        $message = new Message();
        $message->from = 1;
        $message->to = 2;
        $message->message = "Assalomu aleykum";
        $message->save();

        event(new NewMessageNotification($message));

        return back()->intended('broadcast');
    }
}
```

View yaratish
