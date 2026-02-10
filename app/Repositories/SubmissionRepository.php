<?php

namespace App\Repositories;

use App\Models\Submission;
use App\Interfaces\SubmissionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubmissionRepository implements SubmissionRepositoryInterface
{
    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $submission = Submission::create($data);
            DB::commit();
            return $submission;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Submission Create Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function all()
    {
        return Submission::with('form')->latest()->get();
    }

    public function markRead($id)
    {
        try {
            DB::beginTransaction();

            $submission = Submission::findOrFail($id);
            $submission->update(['is_read' => true]);

            DB::commit();
            return $submission;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Submission Mark Read Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function countAll()
    {
        return Submission::count();
    }

    public function countUnread()
    {
        return Submission::where('is_read', false)->count();
    }

    public function getRecent($limit = 5)
    {
        return Submission::with('form')->latest()->take($limit)->get();
    }
}
