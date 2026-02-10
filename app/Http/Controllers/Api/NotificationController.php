<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->list(auth()->id())
        ]);
    }

    public function markRead($id)
    {
        try {
            $this->service->markRead(auth()->id(), $id);
            return response()->json(['success' => true, 'message' => 'Notification marked as read']);
        } catch (\Exception $e) {
            Log::error("Notification Mark Read Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to mark notification as read'], 500);
        }
    }

    public function markAllRead()
    {
        try {
            $this->service->markAllRead(auth()->id());
            return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
        } catch (\Exception $e) {
            Log::error("Notification MarkAll Read Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to mark all notifications as read'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete(auth()->id(), $id);
            return response()->json(['success' => true, 'message' => 'Notification deleted']);
        } catch (\Exception $e) {
            Log::error("Notification Delete Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to delete notification'], 500);
        }
    }
}
