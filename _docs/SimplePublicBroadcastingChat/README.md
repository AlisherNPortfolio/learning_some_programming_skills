# Steps

1. `app/Events/SimpleChatEvent.php`:

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SimpleChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;
    public $message;

    public function __construct($username, $message)
    {
        $this->username = $username;
        $this->message  = $message;
    }


    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastAs()
    {
        return 'message';
    }
}

```

2. `.env` file (from pusher settings):

```apache
BROADCAST_DRIVER=pusher
#...
PUSHER_APP_ID=1544444
PUSHER_APP_KEY=a4a44a4444a44a444444
PUSHER_APP_SECRET=44a444444a444aa4aa44
#...
PUSHER_APP_CLUSTER=ap2
```

3. Pusher config in `config/broadcasting.php` file:

```php
//...
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
                'useTLS' => true //env('PUSHER_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
      ],
 ],
//...
```

4. Uncomment `App\Providers\BroadcastServiceProvider::class,` in `config/app.php`'s `providers` array:

```php
'providers' => [

        //...
        App\Providers\BroadcastServiceProvider::class,
        //...

    ],
```

5. Laravel echo and pusher script codes in client side (`resources/js/laravel_echo.js` file):

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

```

6. Simple Chat script codes in client side (`resources/js/simple_chat.js` file):

```javascript
$(document).ready(function (){

    $(document).on('click','#sent_message' ,function (e){

        e.preventDefault()

        let username = $('#username').val();
        let message = $('#message').val();

        if (username == '' || message == ''){
            alert('Plz, enter both username and message')
            return false;
        }

        $.ajax({
            method: "POST",
            url: "/send-message",
            data:{username:username, message:message},
            success: function (response){

            }

        });

    });
});

window.Echo.channel('chat')
.listen('.message', (e)=>{
    $('#messages').append('<p><strong>'+e.username+'</strong>'+ ':' + e.message+'</p>');
    $('#message').val('');
})

```

7. In `resources/js/bootstrap.js` file import `resources/js/laravel_echo.js` file:

```javascript
//...
import './laravel_echo';
//...
```

8. In `resources/js/app.js` file import `resources/js/bootstrap.js` and `resources/js/simple_chat.js` file:

```javascript
import './bootstrap';
import './simple_chat'
//...
```

9. `routes/web.php` file:

```php
//...
Route::get('simple-chat', function () {
    return view('broadcasting.simple-chat');
});
Route::post('/send-message', function (Request $request) {
    event(new SimpleChatEvent($request->username, $request->message));
    return ['success' => true];
});
//...
```

10. `resources/views/broadcasting/simple-chat.blade.php`:

```html
<!doctype html>
<html lang="en">
<head>
    <title>Laravel Ajax</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.all.min.js"></script>

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!--Button icon-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
    @vite(['resources/css/app.css ', 'resources/js/app.js'])

</head>
<body>
<div style="padding: 30px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3 py-2">
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Enter a user................" />
            </div>

            <div class="col-sm-6 offset-sm-3">
                <div class="box box-primary  direct-chat direct-chat-primary">
                    <div class="box-body">
                        <div class="direct-chat-messages" id="messages"></div>
                    </div>

                    <div class="box-footer">
                        <form action="" method="post" id="message_form">
                            <div class="input-group">
                                <input type="text" id="message" class="form-control" name="message" placeholder="Type a message..." />
                                <span>
                                    <button type="submit" id="sent_message" class="btn btn-primary btn-flat"><i
                                        class="fa fa-paper-plane"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!--JS-->
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


</script>

</body>
</html>

```

# How it works?

1. `composer install`
2. Create and update `.env` file with necessary configurations written above.
3. `php artisan key:generate`
4. `npm install & npm run dev`
5. `php artisan serve`
6. Go to `http://localhost:8080/simple-chat` URL
