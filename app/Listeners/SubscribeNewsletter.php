<?php

namespace App\Listeners;

use App\Events\SignUp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeNewsletter
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
     * @param  SignUp  $event
     * @return void
     */
    public function handle(SignUp $event)
    {
        $event->user->subscribeNewsletter();
    }
}
