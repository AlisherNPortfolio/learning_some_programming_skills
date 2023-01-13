# Laravel examples

### Laravel event listeners

Eventlar laravelda observer pattern-ning ishlatilishi hisoblanadi. Bunda biror bir event yuz berganda uni tinglab/kuzatib turgan listener o'ziga berilgan biror vazifani bajaradi.

Eventlar project-dagi turli xil vazifalarni alohida ko'rinishda ajratib turishga yordam beradi. Chunki, bitta event-ni bir biriga bog'liq bo'lmagan bir qancha listenerlar kuzatib turishi mumkin.

Misol uchun, har safar mahsulot yetkazib berishga chiqarib yuborilganini mijozga bildirish uchun unga Slack yordamida xabar yuborishni olaylik. Mana shu qismda buyurtmani yetkazib berishga chiqarish kodi bilan Slack yordamida xabar yuborish kodini aralash qilib yozmaslik uchun `App\Events\OrderShipped` eventini yaratib, uni listenerga berib yuboramiz va o'z navbatida listener o'zidagi biror vazifani ishga tushiradi.

Event va listenerlar `App\Providers\EventServiceProvider` provider-idagi `listen` xususiyati yordamida register qilinadi. Misol uchun `OrderShipped` eventini register qilaylik:

```
use App\Events\OrderShipped;
use App\Listeners\SendShipmentNotification;
 
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    OrderShipped::class => [
        SendShipmentNotification::class,
    ],
];
```

Ko'rinib turganidek `OrderShipped` eventini unga berilgan massivdagi listenerlar kuzatib turishi mumkin.

**Event & Listener-ni generatsiya qilish**

```
php artisan make:event PodcastProcessed
 
php artisan make:listener SendPodcastNotification --event=PodcastProcessed
```

**Event-larni qo'lda register qilish**

Odatda event-lar `EventServiceProvider`dagi `listen` xususiyatida register qilinadi. Lekin klass yoki closure-ga asoslangan event/listener-larni `EventServiceProvider`ning `boot` metodida ham register qilish mumkin:

```
use App\Events\PodcastProcessed;
use App\Listeners\SendPodcastNotification;
use Illuminate\Support\Facades\Event;
 
/**
 * Register any other events for your application.
 *
 * @return void
 */
public function boot()
{
    Event::listen(
        PodcastProcessed::class,
        [SendPodcastNotification::class, 'handle']
    );
 
    Event::listen(function (PodcastProcessed $event) {
        //
    });
}
```

**Queueable anonymus event listenerlar**

Closure-ga asoslangan event listener-larni ularni queue yordamida ishga tushirish paytida `Illuminate\Events\queueable` funksiyasi ichida berib ham register qilish mumkin:

```
use App\Events\PodcastProcessed;
use function Illuminate\Events\queueable;
use Illuminate\Support\Facades\Event;
 
/**
 * Register any other events for your application.
 *
 * @return void
 */
public function boot()
{
    Event::listen(queueable(function (PodcastProcessed $event) {
        //
    }));
}
```

Queue bilan ishlaydigan job-larga o'xshab, queue bilan ishlaydigan listenerlarni ishlashini o'zgartirish uchun `onConnection`, `onQueue` va `delay` metodlaridan foydalanish mumkin:

```
Event::listen(queueable(function (PodcastProcessed $event) {
    //
})->onConnection('redis')->onQueue('podcasts')->delay(now()->addSeconds(10)));
```

Agar anonymus queued listener-lardagi xatoliklar bilan ishlash kerak bo'lsa, `queueable`-ning `catch` metodidan foydalansa bo'ladi. Bu metod event va listenerdagi xatolikka sabab bo'lgan `Throwable` obyektlarini qabul qiladi:

```
use App\Events\PodcastProcessed;
use function Illuminate\Events\queueable;
use Illuminate\Support\Facades\Event;
use Throwable;
 
Event::listen(queueable(function (PodcastProcessed $event) {
    //
})->catch(function (PodcastProcessed $event, Throwable $e) {
    // The queued listener failed...
}));
```

**Wildcard event listenerlar**

Bir nechta event-ni bitta listener kuzatib turishini ta'minlash uchun listener-larni `*` wildcard-i bilan register qilish mumkin:

```
Event::listen('event.*', function ($eventName, array $data) {
    //
});
```

**Event-ni e'lon qilish**

Event klasi aslida event-ga bog'liq bo'lgan ma'lumotlarni saqlab turuvchi konteyner hisoblanadi. Misol uchun, `App\Events\OrderShipped` eventi Eloquent ORM obyektni qabul qiladi:

```
<?php
 
namespace App\Events;
 
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
 
class OrderShipped
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
    public $order;
 
    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct(Order $order) // <-- $order modelini qabul qilib o'zida saqlab turadi
    {
        $this->order = $order;
    }
}
```

Ko'rinib turganidek, event klasida hech qanday biror boshqa ish bajarilmayapti. U faqat `App\Models\Order` obyektini o'zida saqlab turibdi. `SerializesModels` trait-i `queued listener`larni ishlatgandagi kabi event obyekti PHP serialize bilan serialize qilinganda, Eloquent modelni ham serialize qilib beradi.

**Listenerni e'lon qilish**

Event listenerlar event obyektini `handle` metodida qabul qilib oladi. `event:generate` va `make:listener` artisan buyruqlari kerakli event klasni `handle` metodida type hint qilib beradi. `handle` metodi ichida event sodir bo'lganda qilinishi kerak bo'lgan xohlagan vazifaning kodini yozish mumkin:

```
<?php
 
namespace App\Listeners;
 
use App\Events\OrderShipped;
 
class SendShipmentNotification
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
     * @param  \App\Events\OrderShipped  $event
     * @return void
     */
    public function handle(OrderShipped $event)
    {
        // Access the order using $event->order...
    }
}
```

Eslatma: event listener-lar yana contructorida o'ziga kerak bo'lgan boshqa har qanday dependency-ni type hint qilib qabul qilishi mumkin. Barcha listenerlar Laravelning service container-i orqali ishlatilgani uchun dependency-lar avtomatik inject qilinadi.

**Event-ni ishlashini to'xtatish**

Agar biror event-ning ishlashini boshqa listener-lar uchun to'xtatish kerak bo'lsa `handle` metodidan `false` qiymatni qaytarish kifoya.

**Queued Event Listener-lar**

Queueing listener-lar agar listener email jo'natish yoki HTTP so'rov yaratish kabi sekin ishlaydigan vazifalarni ishga tushirish kerak bo'lsa, foydali hisoblanadi. Queued listener-larni ishlatishdan oldin, queue sozlamalarini to'g'rilab olish va serverda yoki virtual serverda (masalan, xamp yoki open server) queue worker-ni ishga tushirish kerak bo'ladi.

Listener-ni queued bilan ishlashi uchun listener klasiga `ShouldQueue` interface-ini qo'shish kerak bo'ladi:

```
<?php
 
namespace App\Listeners;
 
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class SendShipmentNotification implements ShouldQueue
{
    //
}
```

Shu yetarl! Endi, biror event-ni shu listener qabul qilib ishga tushishi bilan, listener o'z-o'zidan Laravel queue tizimi yordamida event dispatcher orqali queued bo'ladi. Agar listener queue tomonidan ishga tushirilganida biror xatolik yuz bermasa, queue job jarayon tugashi bilan o'z-o'zidan o'chib ketadi.

**Queue connection & Queue name-larni o'zgartirish**

Queue connection, queue name, or queue delay vaqtlarini o'zgartirish kerak bo'lsa, `$connection`, `$queue`, or `$delay` xususiyatlaridan foydalaniladi:

```
<?php
 
namespace App\Listeners;
 
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class SendShipmentNotification implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'sqs';
 
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';
 
    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 60;
}
```

Agar listener-ning queue connection yoki queue name-larini runtime vaqtida o'zgartirmoqchi bo'lsak, `viaConnection` va `viaQueue` metodlarini listener klasi ichida e'lon qilishimiz kerak bo'ladi:

```
/**
 * Get the name of the listener's queue connection.
 *
 * @return string
 */
public function viaConnection()
{
    return 'sqs';
}
 
/**
 * Get the name of the listener's queue.
 *
 * @return string
 */
public function viaQueue()
{
    return 'listeners';
}
```

**Listener-larni shart asosida queue bilan ishlatish**

Ba'zan, kelgan ma'lumotga qarab runtime paytida listener queued qilinishi yoki qilinmasligini belgilash kerak bo'ladi. Buning uchun, `shouldQueue` metodini listenerga qo'shish kerak bo'ladi. Agar `shouldQueue` metodi false qaytarsa, listener ishga tushirilmaydi:

```
<?php
 
namespace App\Listeners;
 
use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class RewardGiftCard implements ShouldQueue
{
    /**
     * Reward a gift card to the customer.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        //
    }
 
    /**
     * Determine whether the listener should be queued.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return bool
     */
    public function shouldQueue(OrderCreated $event)
    {
        return $event->order->subtotal >= 5000;
    }
}
```

**Qo'lda queue bilan ishlash**

Agar listener-dagi queue job-ning delete va release metodlarini qo'lda ishlatish kerak bo'ladigan bo'lsa, `Illuminate\Queue\InteractsWithQueue` trait-i yordamida buni bajarish mumkin:

```
<?php
 
namespace App\Listeners;
 
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
 
class SendShipmentNotification implements ShouldQueue
{
    use InteractsWithQueue;
 
    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderShipped  $event
     * @return void
     */
    public function handle(OrderShipped $event)
    {
        if (true) {
            $this->release(30);
        }
    }
}
```

**Queued Event Listener-lar va Database Transaction-lar**

Queued listenerlar database transaction-lar ichida ishga tushirilsa, ular database transaction commit bo'lishidan oldin ishlab ketishi mumkin. Bunday holat yuz berganda, model yoki database recordda qilingan har qanday o'zgarishni database transaction database-ga to'liq yozib tugatmasligi mumkin. Bundan tashqari, database transaction ichida yaratilgan har qanday model yoki database record database-da mavjud bo'lmasligi mumkin. Agar listener shu modellarga bog'liq bo'lib ishlasa, queued listener-ni ishga tushiradigan job ishlatilgan paytda kutimagan xatoliklar yuz berishi mumkin.

Agar queue connection-ning after_commit sozlamasiga false qiymati berilsa, queued listener-lar barcha ochiq database transaction-lar commit qilinganidan keyin ishlatiladi:

```
<?php
 
namespace App\Listeners;
 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
 
class SendShipmentNotification implements ShouldQueue
{
    use InteractsWithQueue;
 
    public $afterCommit = true;
}
```

Bu haqida to'liq [queued jobs va database transation-lar](https://laravel.com/docs/9.x/queues#jobs-and-database-transactions) mavzusidan o'qib olish mumkin.

**Muvaffaqiyatsiz bajarilgan joblar bilan ishlash**

Ba'zan queued event listener-lar muvaffaqiyatsiz bajarilishi mumkin (ya'ni xato bilan). Agar queued listener queue worker-da belgilangan bajarishga urinishlar (attempts) sonidan oshib ketsa, listener `failed` metodini ishga tushiradi:

```
<?php
 
namespace App\Listeners;
 
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
 
class SendShipmentNotification implements ShouldQueue
{
    use InteractsWithQueue;
 
    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderShipped  $event
     * @return void
     */
    public function handle(OrderShipped $event)
    {
        //
    }
 
    /**
     * Handle a job failure.
     *
     * @param  \App\Events\OrderShipped  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(OrderShipped $event, $exception)
    {
        //
    }
}
```

**Queued listenerlarda maksimum urinishlar sonini berish**

Agar birorta queued listenerda xatolik yuz bersa, uni cheksiz qayta ishga tushirish kerak bo'lmay qoladi. Shuning uchun, laravel listenerni necha marotabagacha qayta ishga tushirish mumkinligini belgilab berishga imkon beradi.

Listenerning `$tries` xususiyati yordamida listenerda xatolik chiqqanda necha marotaba qayta urinib ko'rish mumkinligini belgilash mumkin:

```
<?php
 
namespace App\Listeners;
 
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
 
class SendShipmentNotification implements ShouldQueue
{
    use InteractsWithQueue;
 
    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 5;
}
```

Bundan tashqari listenerda xatolik yuz berganda u qancha vaqt davomida qayta ishlashi mumkinligini ham belgilash mumkin. Buning uchun retryUntil metodini listener klasida e'lon qilish kerak bo'ladi:

```
/**
 * Determine the time at which the listener should timeout.
 *
 * @return \DateTime
 */
public function retryUntil()
{
    return now()->addMinutes(5);
}
```

**Eventlarni ishga tushirish**

Eventni ishga tushirish uchun dispatch statik metodidan foydalaniladi. Bu metodni ishlatish uchun `Illuminate\Foundation\Events\Dispatchable` trait-idan foydalanish kerak. dispatch metodiga berilgan argumentlar event-ning constructor-iga uzatiladi:

```
<?php
 
namespace App\Http\Controllers;
 
use App\Events\OrderShipped;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
 
class OrderShipmentController extends Controller
{
    /**
     * Ship the given order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
 
        // Order shipment logic...
 
        OrderShipped::dispatch($order);
    }
}
```

Agar event-ni shart asosida ishlatish kerak bo'lsa, `dispatchIf` va `dispatchUnless` metodlari ishlatiladi:

```
OrderShipped::dispatchIf($condition, $order);
 
OrderShipped::dispatchUnless($condition, $order);
```

**Event subscriber-lar**
