<?php

namespace App\Services;

use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class MailTestService
{
    public function sendTestEmail($email)
    {
        Mail::to($email)->send(new TestEmail());
        return true;
    }
}
