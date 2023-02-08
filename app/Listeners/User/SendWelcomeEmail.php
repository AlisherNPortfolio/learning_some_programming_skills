<?php

namespace App\Listeners;

use App\Events\User\UserCreated;

class SendWelcomeEmail
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        // User pochtasiga xabar yuborish kodi...
        dump("You ($event->user->email) have created an account in http://learn.loc");
    }
}
