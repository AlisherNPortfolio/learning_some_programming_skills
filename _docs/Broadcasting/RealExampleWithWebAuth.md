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
        //...

        return back()->intended('broadcast');
    }
}
```

# View yaratish

Yuqoridagi controllerning index metodidagi broadcasting.index view-ni yozamiz:

```php
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Test</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand123" href="{{ url('/') }}">
                        Test
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                        <li>
                            <a href="{{ route('login') }}">Login</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}">Register</a>
                        </li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content">
            <div class="m-b-md">
                Yangi notification!
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/echo.js') }}"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        Pusher.logToConsole = true;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '84cd3fd6046bf794217b',
            cluster: 'ap2',
            encrypted: true,
            logToConsole: true
        });

        Echo.private('user.{{ $user_id }}')
        .listen('NewMessageNotification', (e) => {
            alert(e.message.message);
        })
    </script>

</body>
</html>
```

# Route qo'shish

`routes/web.php` da route-larni yozamiz:

```php
Route::get('message/index', [MessageController::class, 'index']);
Route::post('message/send', [MessageController::class, 'send']);
```

# Yuqoridagi barcha kodlarning umumiy ishlash jarayoni

`MessageController`\-ning constructor-ida controller bilan faqat tizimga kirgan foydalanuvchilar ishlay olishi uchun `auth` middleware-idan foydalanganmiz.

index metodi broadcasting.index view-ni render qiladi. Bu view-dagi eng muhim qismi bu Laravel Echo-ning script kodlari:

```html
<script src="{{ asset('js/echo.js') }}"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
  Pusher.logToConsole = true;

  window.Echo = new Echo({
    broadcaster: "pusher",
    key: "84cd3fd6046bf794217b",
    cluster: "ap2",
    encrypted: true,
    logToConsole: true,
  });

  Echo.private("user.{{ $user_id }}").listen("NewMessageNotification", (e) => {
    alert(e.message.message);
  });
</script>
```

Avval, Pusher websocket serverga websocket bog'lanishni amalga oshirish uchun frontendda muhim bo'lgan kutubxonalar - Laravel Echo va Pusher-ni yuklaymiz.

Keyin, Echo obyektini yaratib olamiz.

Undan keyin, `user.{USER_ID}` private kanalga ulanish uchun Echo-ning `private` metodidan foydalanamiz. Oldin aytganimizdek, foydalanuvchi xabar jo'natishdan oldin tizimga kirgan, ya'ni login qilgan, bo'lishi kerak. Bunday holatda, Echo obyekti kerak authentication-ni kerakli parametrlar bilan XHR orqali orqa fonda o'zi amalga oshiradi. Shundan so'ng, Laravel `routes/channels.php` fayldan `user.{USER_ID}` route-ni qidiradi.

Agar yuqoridagi barcha ishlar ko'ngildagidek ishlasa, bizda Pusher websocket server tomonidan ochilgan websocket bog'lanishga ega bo'lamiz va bu bog'lanish `user.{USER_ID}` kanalda kerakli event-ni kuzatib turadi. Shu bilan asosiy tugadi. Endi, bu kanal orqali barcha keluvchi event-larni qabul qilishimiz mumkin bo'ladi.

Bizning holatimizda biz `NewMessageNotification` event-ini qabul qilib olishimiz kerak. Shuning uchun ham, `Echo`\-ning `listen` metodidan foydalandik. Soddaroq bo'lishi uchun, Pusher server-dan kelgan xabarlarni shunchaki ekranga chiqarib qo'yamiz xolos.

Yuqoridagilar websocket serverdan ma'lumotlarni qabul qilish uchun edi. Endi, event-ni broadcast qilishni ishga tushiradigan controllerdagi `send` metodini ko'raylik.

`send` metodi quyidagi ko'rinishda edi:

```php
public function send()
    {
        // ...

        $message = new Message();
        $message->from = 1;
        $message->to = 2;
        $message->message = "Assalomu aleykum";
        $message->save();

        event(new NewMessageNotification($message));
        //...

        return back()->intended('broadcast');
    }
```

Bu yerda, tizimga kirgan foydalanuvchilarga ularga yuborilgan xabarlarni jo'natib beramiz. `NewMessageNotification` event-ni ishga tushirish uchun \``event` helper funksiyasidan foydalandik. `NewMessageNotification` event-i `ShouldBroadcastNow` turiga tegishli bo'lgani uchun Laravel `config/broadcasting.php` sozlamalar faylidagi odatiy sozlamalarni yuklab oladi. Oxirida esa, u `NewMessageNotification` event-ini `user.{USER_ID}` kanalida sozlamalarda ko'rsatilgan websocket serveri orqali jo'natadi.

Hozir bizning misolimizda, event Pusher websocket serveri orqali `user.{USER_ID}` kanalida broadcast qilinyapti. Agar qabul qiluvchi foydalanuvchining ID-is 1 bo'lsa, kanal nomi `user.1`  bo'ladi.

# Ishlatib ko'rish

Endi, yuqoridagi ishimizning ishlatib ko'raylik.

Buning uchun avval, `http://site-domain-name/message/index` URL-ini browser-da ochamiz. Agar foydalanuvchi tizimga kirmagan bo'lsa, login sahifasiga redirect bo'ladi. Agar foydalanuvchi tizimga kirgan bo'lsa, `broadcasting.index` view sahifasi ochiladi. Sahifaga kirishimiz bilan, ayrim kerakli ishlarni Laravel-ning o'zi orqa fonda bajarishni boshlaydi. Buni browser-ning console-ini ochib ko'rishimiz mumkin. Frontend qismida Pusher kutubxonasining `Pusher.logToConsole` sozlamasiga ruxsat berish orqali buni amalga oshirganmiz.
