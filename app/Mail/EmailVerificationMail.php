<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
        $this->details['title'] = __('email.email_verification.title', ['appName' => config('app.name')]);
    }

    public function build()
    {
        return $this->subject($this->details['title'])
            ->view('emails.verifyEmail', [
                'title' => $this->details['title'],
                'email' => $this->details['email'],
                'first_name' => $this->details['first_name'],
                'last_name' => $this->details['last_name'],
                'url' => $this->details['url'],
            ]);
    }
}

