<?php

namespace App\Interfaces;

interface NotificationRepositoryInterface
{
    public function listForUser($userId);
    public function markRead($userId, $notificationId);
    public function markAllRead($userId);
    public function delete($userId, $notificationId);
}
