<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnsubscriptionConfirmation extends Notification implements ShouldQueue
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
        $unsubscribeUrl = url('/confirm-unsubscription?email=' . $this->email);

        return (new MailMessage)
            ->subject('Unsubscription Confirmation')
            ->line('Your email address is: ' . $this->email)
            ->line('Please click the link below to confirm your unsubscription')
            ->action('Confirm Subscription', $unsubscribeUrl)
            ->line('You can subscribe again at any time.');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
