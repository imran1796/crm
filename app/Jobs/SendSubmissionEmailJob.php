<?php

namespace App\Jobs;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSubmissionEmailJob implements ShouldQueue
{
    use Queueable;

    public $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function handle()
    {
        try {
            $formName = $this->submission->form->name;

            Mail::raw("New submission received for form: {$formName}", function ($msg) {
                $msg->to(config('mail.admin_email'))
                    ->subject('New Form Submission');
            });

        } catch (\Exception $e) {
            Log::error("Submission Email Job Error: ".$e->getMessage());
        }
    }
}
