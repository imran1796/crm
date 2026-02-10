<?php
namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    protected $roleService;
    protected $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;

        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = $this->roleService->paginate(20);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $grouped = $this->permissionService->groupedPermissions();
        return view('roles.create', ['groupedPermission' => $grouped]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array'
        ]);

        try {
            $data = ['name' => $request->name, 'permissions' => $request->permission];
            $this->roleService->create($data);
            return redirect()->route('roles.index')->with('success', 'Role created');
        } catch (\Exception $e) {
            Log::error('RoleController@store web - ' . $e->getMessage());
            return redirect()->back()->withErrors('Unable to create role');
        }
    }

    public function show($id)
    {
        $role = $this->roleService->find($id);
        $rolePermissions = $this->roleService->getRolePermissions($id);
        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $role = $this->roleService->find($id);
        $grouped = $this->permissionService->groupedPermissions();
        $rolePermissions = $this->roleService->getRolePermissions($id);
        return view('roles.edit', compact('role', 'grouped', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'required|array'
        ]);

        try {
            $data = ['name' => $request->name, 'permissions' => $request->permission];
            $this->roleService->update($id, $data);
            return redirect()->route('roles.index')->with('success', 'Role updated');
        } catch (\Exception $e) {
            Log::error('RoleController@update web - ' . $e->getMessage());
            return redirect()->back()->withErrors('Unable to update role');
        }
    }

    public function destroy($id)
    {
        try {
            $this->roleService->delete($id);
            return redirect()->route('roles.index')->with('success', 'Role deleted');
        } catch (\Exception $e) {
            Log::error('RoleController@destroy web - ' . $e->getMessage());
            return redirect()->back()->withErrors('Unable to delete role');
        }
    }
}
