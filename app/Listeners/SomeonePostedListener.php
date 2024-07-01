<?php

namespace App\Listeners;

use App\Events\SomeonePostedEvent;
use App\Notifications\SomeonePosted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SomeonePostedListener
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
     * @param  \App\Events\SomeonePostedEvent  $event
     * @return void
     */
    public function handle(SomeonePostedEvent $event)
    {
        //
        $event->user->notify(new SomeonePosted(auth()->user()));
    }
}
