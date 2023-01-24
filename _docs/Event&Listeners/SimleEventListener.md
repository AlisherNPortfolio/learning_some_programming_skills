### Laravel event listener misol

##### Tavsifi

Laravel event listener observer pattern-ning laravelda qo'llanilishi hisoblanadi. Bu patternga ko'ra, biz biror hodisa yuz berganda event-ni ishga tushiramiz. Event esa o'z navbatida o'zini kuzatib turgan listener-ni ishga tushiradi. Listener esa o'ziga berilgan biror vazifani bajaradi. Observer pattern tizimdagi komponentlarni alohida ko'rinishda yozishga imkon beradi. Aks holda kodlar o'zaro qattiq bog'lanib qolgan bo'lar edi.

Misol uchun, agar biror kishi saytga login qilganida, bu haqida bu haqida uning pochtasiga yoki telefoniga xabar yubormoqchi bo'lsak bundan foydalanamiz.

##### Laravel event listenerlar

Laravelda barcha event va listener-larni tizimga biriktirish uchun ularni `app\Providers\EventServiceProvider.php` faylidagi `EventServiceProvider` klasining `$listen` xususiyatiga massiv elementlari ko'rinishida berib chiqishimiz kerak:

```php
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [ // <-- shu xususiyatga
        Registered::class => [ // <== bu event
            SendEmailVerificationNotification::class, // <== bu eventni kuzatib turuvchi listener
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}

```

Endi o'zimiz yangi event va listener qo'shaylik. Faraz qilaylik, qandaydir holat yuz berganda berilgan kalitlar bo'yicha keshlarni tozalab tashlashimiz kerak. Bunda `CacheClear` event-ini ishga tushiramiz. Natijada uni kuzatib turgan `CacheClear` listeneri berilgan kalitli keshlarni tozalab tashlaydi.

1-qadam.

Avval, EventServiceProvider klasda event va listener-ni biriktirib qo'yamiz (hali event va listener klaslari yaratilmagan bo'lsa ham shunday qilish mumkin. nomi bo'yicha bu klaslarni Laravelning o'zi yaratib oladi):

```php
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\WarmUpCache; // <== Listener klas
use App\Events\ClearCache; // <== Event klas

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ClearCache::class => [ // <==
            WarmUpCache::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}

```

2-qadam.

`php artisan event:generate` buyrug'ini ishga tushiramiz. Bu buyruq `EventServiceProvider` klasdagi hali yaratilmagan event va listenerlarni yaratib beradi.

`app/Events/ClearCache.php` fayli:

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClearCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cache_keys = []; // <== tozalanadigan keshlar uchun xususiyat

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $cache_keys)
    {
        $this->cache_keys = $cache_keys; // <== -||-
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

```

Yuqoridagi kodda ko'rib turganingizdek, event klasi asosan ma'lumot uchun konteyner vazifasini o'taydi. Ya'ni u hodisa sodir bo'lgan joydagi ma'lumotni listener klasga yetkazib beradi.

3-qadam.

`app/Listeners/WarmUpCache.php` fayli. Bajariladigan vazifaning kodi `handle` metodida beriladi:

```php
<?php

namespace App\Listeners;

use App\Events\ClearCache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WarmUpCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ClearCache  $event
     * @return void
     */
    public function handle(ClearCache $event)
    {
        if (isset($event->cache_keys) && count($event->cache_keys)) {
            foreach ($event->cache_keys as $cache_key) { // <== kalit bo'yicha barcha keshlar tozalanadi
                // $cache_key kalit bo'yicha keshlarni tozalash kodi
            }
        }
    }
}

```

Listener ishga tushgan paytda uning `handle` metodi ishlaydi.

4-qadam.

`app/Http/Controllers/EventListener/EventController` kontrolleri:

```php
<?php

namespace App\Http\Controllers\EventListener;

use App\Events\ClearCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // ...
        $arr_caches = ['categories', 'products'];

        event(new ClearCache($arr_caches)); // <== CacheClear eventini ishga tushirish
	// ClearCache::dispatch($arr_caches); // shu ko'rinishda ham ishga tushirish mumkin
    }
}

```

Yuqoridagilarni umumiy qilib tushuntiradigan bo'lsak, `EventController`-ning index metodi ichida biror amal bajariladi (masalan, login qilish yoki bazadan biror ma'lumotni olish) va bu haqida `ClearCache` eventiga xabar beriladi (event helper funksiyasi orqali `ClearCache` klasi ishga tushirilib, unga kesh kalitlari massivi uzatiladi). Uni kuzatib turgan `WarmUpCache` listeneri esa event orqali olgan kesh kalitlari yordamida keshlarni tozalab chiqadi. `WarmUpCache` listeneri `ClearCache` eventini kuzatishi uchun ular `EventServiceProvider`-ning `$listen` xususiyatida ro'yxatdan o'tkazilib, listener event-ga bog'lab qo'yiladi.


##### Qo'shimcha

Eventni ishga tushirish uchun `event()` funksiyasidan tashqari event klasning `dispatch()` metodidan foydalanish ham mumkin:

```php
<?php

namespace App\Http\Controllers\EventListener;

use App\Events\ClearCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // ...
        $arr_caches = ['categories', 'products'];

        ClearCache::dispatch($arr_caches); // <== CacheClear eventini ishga tushirish
    }
}
```

Bundan tashqari, event-ni biror shartga ko'ra ishga tushirishni ham amalga oshirsa bo'ladi:

```php
ClearCache::dispatchIf($shart, $arr_caches);

ClearCache::dispatchUnless($shart, $arr_caches);
```
