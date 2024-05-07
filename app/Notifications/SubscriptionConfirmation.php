<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionConfirmation extends Notification implements ShouldQueue
{
    use Queueable;
    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $confirmationUrl = url('/confirm-subscription?email=' . $this->email);
        return (new MailMessage)
            ->subject('Subscription Confirmation')
            ->line('Thank you for subscribing to our newsletter.')
            ->line('Your email address is: ' . $this->email)
            ->line('Please click the link below to confirm your subscription')
            ->action('Confirm Subscription', $confirmationUrl)
            ->line('You can unsubscribe at any time.');
    }
    
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
