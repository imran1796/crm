<?php

namespace App\Services;

use App\Interfaces\NotificationRepositoryInterface;

class NotificationService
{
    protected $repo;

    public function __construct(NotificationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function list($userId)
    {
        return $this->repo->listForUser($userId);
    }

    public function markRead($userId, $id)
    {
        return $this->repo->markRead($userId, $id);
    }

    public function markAllRead($userId)
    {
        return $this->repo->markAllRead($userId);
    }

    public function delete($userId, $id)
    {
        return $this->repo->delete($userId, $id);
    }
}
