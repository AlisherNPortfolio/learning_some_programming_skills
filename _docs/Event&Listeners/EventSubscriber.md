### Laravel Event Subscriber

##### Laravel event subscriber nima?

Laravel event subscriber bitta joyda (klasda) turib bir nechta event listener-larni subscribe qilishga imkon beradi. Masalan, event listener-larni mantiqan guruhlashni yoki son jihatdan ko'payib ketayotgan event-larni bir joyga jamlashni xohlasak event subscriber-dan foydalanamiz.

Misol uchun, login qilgan user-ning pochtasiga xabar yuborish va yuqoridagi keshni tozalash event listenerlarini subscriber ichida yozmoqchi bo'lsak, ularni `app/Listeners` papkasidagi subscriber klasi ichida quyidagicha yozamiz:

```php
<?php

namespace App\Listeners;

use App\Events\ClearCache;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GroupableEventSubscriber
{
    /**
     * Handle user login events.
     * Login qilingandan keyin xabar yuborishni amalga oshiradi
     * Xuddi listener klasiga o'xshaydi.
     * Farqi klas o'rnida metod ishlaydi
     */

    public function sendEmailNotification($event)
    {
        // login qilgan user ma'lumotlarini olish
        $email = $event->user->email;
        $username = $event->user->name;

        // login qilingani haqida ma'lumot jo'natish kodi ...
    }

    /**
     * Handle user logout events.
     * Logout qilingandan keyin ayrim keshlarni o'chirishni amalga oshiradi
     * Xuddi listener klasiga o'xshaydi.
     * Farqi klas o'rnida metod ishlaydi
     */
    public function warmUpCache(ClearCache $event)
    {
        if (isset($event->cache_keys) && count($event->cache_keys)) {
            foreach ($event->cache_keys as $cache_key) {
                // keshni $cache_key yordamida tozalab tashlash
            }
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     * Eventni EventSubscriber ichidagi mos metodga bog'lab qo'yish
     * ya'ni, EventSubscriber ichidagi listener metodni
     * kerakli eventni kuzatishi uchun shu eventga bog'lab qo'yish
     */
    public function subscribe($events)
    {
        $events->listen(
            Login::class, // <== event klasi
            [GroupableEventSubscriber::class, 'sendEmailNotification'] // <== subscriberdagi listener metod
        );

        $events->listen(
            ClearCache::class,
            [GroupableEventSubscriber::class, 'warmUpCache']
        );
    }
}

```

Oxrida bu event subscriber klasini `EventServiceProvider` ichida ro'yxatdan o'tkizib qo'yishimiz kerak bo'ladi:

```php
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\WarmUpCache;
use App\Events\ClearCache;
use App\Listeners\GroupableEventSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */

    public $subscribe = [
        GroupableEventSubscriber::class // <== event subscriberni biriktirish
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
