<?php

namespace App\Listeners;

use App\Events\FormSubmittedEvent;
use App\Models\User;
use App\Notifications\SubmissionNotification;
use Illuminate\Support\Facades\Log;

class SendSubmissionNotification
{
    public function handle(FormSubmittedEvent $event)
    {
        try {
            // Notify all admins/system admins
            $admins = User::role(['Admin', 'system-admin'])->get();

            foreach ($admins as $admin) {
                $admin->notify(new SubmissionNotification($event->submission));
            }

        } catch (\Exception $e) {
            Log::error("Submission Notification Error: " . $e->getMessage());
        }
    }
}
