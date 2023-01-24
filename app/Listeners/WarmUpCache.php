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
            foreach ($event->cache_keys as $cache_key) {
                // $cache_key kalit bo'yicha keshlarni tozalash kodi
            }
        }
    }
}
