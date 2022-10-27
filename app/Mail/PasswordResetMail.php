<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
        $this->details['title'] = __('email.password_reset.title', ['appName' => config('app.name')]);
    }

    public function build()
    {
        return $this->subject($this->details['title'])
            ->view('emails.passwordReset', [
                'title' => $this->details['title'],
                'email' => $this->details['email'],
                'url' => $this->details['url'],
            ]);
    }
}
