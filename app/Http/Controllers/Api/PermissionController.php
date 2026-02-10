<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    protected $service;

    public function __construct(PermissionService $service)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Admin|system-admin');
        $this->service = $service;
    }

    public function index()
    {
        return response()->json(['success' => true, 'data' => $this->service->list()]);
    }

    public function store(PermissionStoreRequest $request)
    {
        try {
            $p = $this->service->create($request->validated());
            return response()->json(['success' => true, 'message' => 'Permission created', 'data' => $p]);
        } catch (\Exception $e) {
            Log::error('PermissionController@store error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating permission'], 500);
        }
    }

    public function update(PermissionUpdateRequest $request, $id)
    {
        try {
            $p = $this->service->update($id, $request->validated());
            return response()->json(['success' => true, 'message' => 'Permission updated', 'data' => $p]);
        } catch (\Exception $e) {
            Log::error('PermissionController@update error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating permission'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'Permission deleted']);
        } catch (\Exception $e) {
            Log::error('PermissionController@destroy error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting permission'], 500);
        }
    }
}
