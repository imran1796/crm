<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TestEmail extends Mailable
{
    public function build()
    {
        return $this->subject("SMTP Test Successful")
            ->view('emails.smtp_test');
    }
}
