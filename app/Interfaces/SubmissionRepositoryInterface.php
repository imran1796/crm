<?php

namespace App\Interfaces;

interface SubmissionRepositoryInterface
{
    public function create(array $data);
    public function all();
    public function markRead($id);

    public function countAll();
    public function countUnread();
    public function getRecent($limit = 5);
}
