<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SubscriptionUnsubscribed;
use App\Notifications\UnsubscriptionConfirmation;

class SendUnsubscriptionConfirmation
{
    public function __construct()
    {
        //
    }

    public function handle(SubscriptionUnsubscribed $event)
    {
        $subscription = $event->subscription;
        $subscription->notify(new UnsubscriptionConfirmation($subscription->email));
    }
}
