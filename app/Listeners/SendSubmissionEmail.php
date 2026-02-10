<?php

namespace App\Listeners;

use App\Events\FormSubmittedEvent;
use App\Jobs\SendSubmissionEmailJob;
use Illuminate\Support\Facades\Log;

class SendSubmissionEmail
{
    public function handle(FormSubmittedEvent $event)
    {
        try {
            SendSubmissionEmailJob::dispatch($event->submission);
        } catch (\Exception $e) {
            Log::error("Submission Email Listener Error: ".$e->getMessage());
        }
    }
}
