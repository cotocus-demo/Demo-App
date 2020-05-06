<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class TestEmailSender extends Mailable
{
    use Queueable, SerializesModels;

    private $emailParams;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->emailParams = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        log::info("I am inside Mail/UserActivationLinkMail->build()");

        $this->from(Config::get('app.senderEmail'),Config::get('app.senderName'))
        ->subject($this->emailParams->subject)
        ->view('mail.TestEmail')
        ->with(['emailParams' => $this->emailParams]);
    }
}
