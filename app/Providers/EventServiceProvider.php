<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\WarmUpCache;
use App\Events\ClearCache;
use App\Events\User\UserCreated;
use App\Listeners\GroupableEventSubscriber;
use App\Listeners\User\SendWelcomeEmail;
use App\Listeners\User\UserSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [ // event listener-larni alohida bir biriga biriktirish uchun
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        // ClearCache::class => [
        //     WarmUpCache::class,
        // ]

        UserCreated::class => [
            SendWelcomeEmail::class
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */

    public $subscribe = [
        GroupableEventSubscriber::class,
        UserSubscriber::class
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
