<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    protected $service;

    public function __construct(RoleService $service)
    {

        $this->middleware('auth:sanctum');
        $this->middleware('role:Admin|system-admin')->only('store');
        $this->service = $service;
    }

    public function index()
    {
        return response()->json(['success' => true, 'data' => $this->service->list()]);
    }

    public function store(RoleStoreRequest $request)
    {
        try {
            $role = $this->service->create($request->validated());
            return response()->json(['success' => true, 'message' => 'Role created', 'data' => $role]);
        } catch (\Exception $e) {
            Log::error('RoleController@store error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error creating role'], 500);
        }
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        try {
            $role = $this->service->update($id, $request->validated());
            return response()->json(['success' => true, 'message' => 'Role updated', 'data' => $role]);
        } catch (\Exception $e) {
            Log::error('RoleController@update error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating role'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'Role deleted']);
        } catch (\Exception $e) {
            Log::error('RoleController@destroy error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting role'], 500);
        }
    }
}
