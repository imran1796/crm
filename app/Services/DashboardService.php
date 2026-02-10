<?php
namespace App\Services;

use App\Interfaces\ClientRepositoryInterface;
use App\Interfaces\SubmissionRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    protected $submissionRepo;
    protected $clientRepo;
    protected $taskRepo;

    public function __construct(
        SubmissionRepositoryInterface $submissionRepo,
        ClientRepositoryInterface $clientRepo,
        TaskRepositoryInterface $taskRepo,

    ) {
        $this->submissionRepo = $submissionRepo;
        $this->clientRepo = $clientRepo;
        $this->taskRepo = $taskRepo;

    }

    public function getStats()
    {
        try {
            return Cache::remember('dashboard_stats', 30, function () {

                return [
                    'total_submissions' => $this->submissionRepo->countAll(),
                    'unread_submissions' => $this->submissionRepo->countUnread(),
                    'total_clients' => $this->clientRepo->countAll(),
                    'tasks_today' => $this->taskRepo->countTasksForToday(),
                    'recent_submissions' => $this->submissionRepo->getRecent(5)
                ];
            });

        } catch (\Exception $e) {
            Log::error("Dashboard Stats Error: " . $e->getMessage());
            throw $e;
        }
    }
}
