<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnsubscriptionConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $unsubscribeUrl = url('/confirm-unsubscription?email=' . $this->email);

        return $this->subject('Xác nhận hủy đăng ký')
            ->view('emails.unsubscription-confirmation', ['unsubscribeUrl' => $unsubscribeUrl]);
    }
}
