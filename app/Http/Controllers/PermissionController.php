<?php
namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    protected $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;

        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = $this->service->list();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        try {
            $this->service->create($request->only('name'));
            return redirect()->route('permissions.index')->with('success', 'Permission created');
        } catch (\Exception $e) {
            Log::error('PermissionController@store web - ' . $e->getMessage());
            return redirect()->back()->withErrors('Unable to create permission');
        }
    }

    public function edit($id)
    {
        $permission = $this->service->find($id);
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        try {
            $this->service->update($id, $request->only('name'));
            return redirect()->route('permissions.index')->with('success', 'Permission updated');
        } catch (\Exception $e) {
            Log::error('PermissionController@update web - ' . $e->getMessage());
            return redirect()->back()->withErrors('Unable to update permission');
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('permissions.index')->with('success', 'Permission deleted');
        } catch (\Exception $e) {
            Log::error('PermissionController@destroy web - ' . $e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
