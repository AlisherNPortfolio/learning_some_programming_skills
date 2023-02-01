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
                'host' => env('PUSHER_HOST') ?: 'api-' . env('PUSHER_APP_CLUSTER', 'mt1') . '.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => true
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
use Illuminate\Broadcasting\InteractsWithSockets;
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
        return new PrivateChannel('chat.' . $this->message->to); // <== PrivateChannel-da yuboramiz
    }

    /**
     * Agar bu metod e'lon qilinmagan bo'lsa,
     * Echo.private(...).listen('.my-chat') o'rniga
     * Echo.private(...).listen('NewMessageNotification') yoziladi
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'my-chat';
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

Broadcast::channel('chat.{toUserId}', function (User $user, $toUserId) {
    return (int) $user->id == (int) $toUserId;
});
```

O'zimizning private kanalimiz uchun `user.{toUserId}` nomli route yaratdik. Route-ning closure qismidagi birinchi argument bu - tizimga kirgan foydalanuvchi modeli ma'lumotlari bo'lsa, ikkinchi argument kanal orqali ma'lumot xabar jo'natgan foydalanuvchining ID-si. Bu ID kanal nomidan chiqarib olingan.

Foydalanuvchi `user.{USER_ID}` private kanalga subscribe qilishmoqchi bo'lganida, Laravel Echo-ning o'zi orqa fonda XMLHttpRequest yordamida tizimga kirishni amalga oshiradi.

Shu yergacha broadcastingni sozlash va o'rnatishni tugatdik. Endi uni test qilib ko'ramiz.

`routes\web.php` faylidagi route-lar:

```php
<?php

use App\Http\Controllers\Broadcasting\MessageController;
use Illuminate\Support\Facades\Route;

Route::post('/pusher/auth', [MessageController::class, 'authPusher'])
    ->middleware('auth'); // <== bu route orqali kanalga ulanish paytida pusher auth-ni amalga oshiradi
Route::post('/send-private', [MessageController::class, 'send']);
Route::get('message/index', [MessageController::class, 'index']);
```

# Controller yaratish

`MessageController` controllerini yaratamiz:

```php
<?php

namespace App\Http\Controllers\Broadcasting;

use App\Events\NewMessageNotification;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::user()->id;
        $users = User::query()->get();
        $messages = Message::query()->get()->toArray();
        $data = ['user_id' => $userId, 'users' => $users, 'messages' => $messages];

        return view('broadcasting.index', $data);
    }

    public function send(Request $request)
    {
        $request->validate([
            'userId' => 'required|integer|min:0',
            'message' => 'required|string|min:1|max:255',
            'whom' => 'required|integer|min:0'
        ]);

        try {
            $message = new Message();
            $message->from = $request->userId;
            $message->to = $request->whom;
            $message->message = $request->message;
            $message->save();

            event(new NewMessageNotification($message));

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Pusher auth qilishi uchun.
    public function authPusher(Request $request)
    {
        $user = auth()->user();
        $socket_id = $request['socket_id'];
        $channel_name = $request['channel_name'];
        $key = getenv('PUSHER_APP_KEY');
        $secret = getenv('PUSHER_APP_SECRET');
        $app_id = getenv('PUSHER_APP_ID');

        if ($user) {

            $pusher = new Pusher($key, $secret, $app_id);
            $auth = $pusher->authorizeChannel($channel_name, $socket_id);

            return response($auth, 200);
        } else {
            header('', true, 403);
            echo "Forbidden";
            return;
        }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    @vite(['resources/css/chat.css', 'resources/js/init_private_broadcasting.js'])
</head>

<body>
    <div class="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @if (Auth::guest())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit()"
                                            class="dropdown-item">Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none">
                                            @csrf
                                        </form>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="m-b-md">
                <div class="row">
                    <div class="col-12">
                        <div class="page-content page-container" id="page-content">
                            <div class="padding">
                                <div class="row container d-flex justify-content-center">
                                    <div class="col-md-6">
                                        <div class="card card-bordered">
                                            <div class="card-header">
                                                <h4 class="card-title"><strong>Chat</strong></h4>
                                                <a class="btn btn-xs btn-secondary" href="#" data-abc="true">Let's
                                                    Chat App</a>
                                            </div>


                                            <div class="ps-container ps-theme-default ps-active-y"
                                                style="overflow-y: scroll !important; height:400px !important;">
                                                {{-- <div class="media media-meta-day">Today</div> --}}
                                                <div id="chat-content">
                                                    @foreach ($messages as $message)
                                                        <div class="media media-chat {{ $user_id == $message['from'] ? 'media-chat-reverse' : '' }}">
                                                            @if ($user_id != $message['from'])
                                                            <img class="avatar"
                                                            src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                            alt="...">
                                                            @endif
                                                            <div class="media-body">
                                                                <p>{{ $message['message'] }}</p>
                                                                <p class="meta">
                                                                    <time datetime="{{ date('Y') }}">{{ \Carbon\Carbon::parse($message['created_at'])->format('h:i') }}</time>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                                                    <div class="ps-scrollbar-x" tabindex="0"
                                                        style="left: 0px; width: 0px;"></div>
                                                </div>
                                                <div class="ps-scrollbar-y-rail"
                                                    style="top: 0px; height: 0px; right: 2px;">
                                                    <div class="ps-scrollbar-y" tabindex="0"
                                                        style="top: 0px; height: 2px;"></div>
                                                </div>
                                            </div>

                                            <div class="publisher bt-1 border-light">
                                                <img class="avatar avatar-xs"
                                                    src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                    alt="...">
                                                <input class="publisher-input" type="text"
                                                    placeholder="Write something">
                                                <span class="publisher-btn file-group">
                                                    <i class="fa fa-paperclip file-browser"></i>
                                                    <input type="file">
                                                </span>
                                                <a class="publisher-btn" href="#" data-abc="true"><i
                                                        class="fa fa-smile"></i></a>
                                                <a class="publisher-btn text-info" href="#" data-abc="true"><i
                                                        class="fa fa-paper-plane"></i></a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="chat-header col-12 mb-3">
                        <p> You: <span class="badge bg-secondary">{{ auth()->user()->name }}</span> </p>
                        <select id="usersList" class="form-select">
                            <option>Select chat</option>
                            @foreach ($users as $user)
                                @if ($user->id != $user_id)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="chat-body col-12">
                        <div id="messages"></div>
                        <textarea class="form-control mb-3" id="chatInput" cols="30" rows="10"></textarea>
                        <button type="button" class="btn btn-primary" id="sendBtn">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    <script>
        const userID = '{{ $user_id ? $user_id : null }}';//

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            let selectedUser = null;

            $(document).on('change', '#usersList', function(e) {
                selectedUser = +e.target.value;
            });

            $(document).on('click', '#sendBtn', function(e) {

                e.preventDefault()
                let message = $('#chatInput').val();

                if (message == '' || selectedUser === null) {
                    alert('Plz, enter both chat and message')
                    return false;
                }

                const data = {
                        userId: userID,
                        message: message,
                        whom: selectedUser
                    };

                $.ajax({
                    method: "POST",
                    url: "/send-private",
                    data: data,
                    success: function(response) {
                        console.log('%c', 'color:red;background:#ccc', response);
                        $('#chat-content').append(
                            generateChatItem({
                                message: data.message,
                                created_at: new Date()
                            }, true));
                    }

                });

            });

            listen(userID);

        });
    </script>

</body>

</html>

```

# Asosiy JavaScript kodlari

`resources/js/init_private_broadcasting.js` fayli:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/pusher/auth',
    csrfToken: $('meta[name="csrf-token"]').attr('content'),
});


window.listen = (id) => { // <== index.blade.php faylida chaqiriladi
    window.Echo.private('chat.' + id )
    .listen('.my-chat', (e) => {
        $('#chat-content').append(window.generateChatItem(e.message))

        console.log('%c' + e.message.message, 'background: blue; color: yellow;font-size:24px;')
    });
}


window.generateChatItem = (data, isSender = false) => {
    let messageItem = '<div class="media media-chat '+ (isSender ? 'media-chat-reverse' : '') +'">';
    if (!isSender) {
        messageItem += `<img class="avatar" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="...">`;
    }

    messageItem += '<div class="media-body">';
    messageItem += '<p>'+ data.message +'</p>'
    messageItem += '<p class="meta">';
    const year = (new Date()).getFullYear();
    const messageTime = typeof data.created_at == 'string' ? new Date(data.created_at): data.created_at;
    const hour = messageTime.getHours();
    const minute = messageTime.getMinutes();
    messageItem += '<time datetime="'+ year +'">'+ `${+hour >= 10 ? hour : '0'+hour}:${+minute >= 10 ? minute : '0'+minute}` +'</time>';
    messageItem += '</p></div></div>';

    return messageItem;
}

```

# Yuqoridagi barcha kodlarning umumiy ishlash jarayoni


# Ishlatib ko'rish
