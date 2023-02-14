<?php

namespace App\Listeners\User;

use App\Events\User\UserCreated;
use App\Listeners\User\SendWelcomeEmail;
use Illuminate\Events\Dispatcher;

class UserSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserCreated::class, SendWelcomeEmail::class);
    }
}
