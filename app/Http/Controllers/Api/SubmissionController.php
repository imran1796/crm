<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SubmissionService;

class SubmissionController extends Controller
{
    protected $service;

    public function __construct(SubmissionService $service)
    {
        $this->middleware(['auth:sanctum', 'role:system-admin|Manager']);
        $this->service = $service;
    }

    // External submission endpoint
    public function submit($slug, Request $request)
    {
        $submission = $this->service->submit($slug, $request);
        return response()->json([
            'message' => 'Submission received successfully',
            'data' => $submission
        ]);
    }

    // Admin view
    public function index()
    {
        return $this->service->list();
    }

    // Mark read
    public function markRead($id)
    {
        return $this->service->markAsRead($id);
    }
}
