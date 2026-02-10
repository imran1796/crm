<?php

namespace App\Repositories;

use App\Interfaces\NotificationRepositoryInterface;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class NotificationRepository implements NotificationRepositoryInterface
{
    public function listForUser($userId)
    {
        return auth()->user()->notifications()->latest()->get();
    }
    public function markRead($userId, $notificationId)
    {
        try {
            DB::beginTransaction();

            DB::table('notifications')
                ->where('id', $notificationId)
                ->where('notifiable_id', $userId)
                ->update(['read_at' => now()]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Notification markRead error: " . $e->getMessage());
            throw $e;
        }
    }

    public function markAllRead($userId)
    {
        try {
            DB::beginTransaction();

            DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $userId)
                ->update(['read_at' => now()]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Notification markAllRead error: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($userId, $notificationId)
    {
        try {
            DB::beginTransaction();

            DB::table('notifications')
                ->where('id', $notificationId)
                ->where('notifiable_id', $userId)
                ->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Notification Delete Error: ".$e->getMessage());
            throw $e;
        }
    }

}
