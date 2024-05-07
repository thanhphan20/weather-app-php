<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SubscriptionCreated;
use App\Notifications\SubscriptionConfirmation;

class SendSubscriptionConfirmation
{

    public function __construct()
    {
        //
    }

    public function handle(SubscriptionCreated $event)
    {
        $subscription = $event->subscription;
        $subscription->notify(new SubscriptionConfirmation($subscription->email));
    }
}
