<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BadgeUnlockedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event)
    {
        // Associate the badge with the user
        $event->user->badges()->attach($event->badge);

        // Dispatch the BadgeUnlocked event
        event(new \App\Events\BadgeUnlocked($event->badge, $event->user));
    }

}
