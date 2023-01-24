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
