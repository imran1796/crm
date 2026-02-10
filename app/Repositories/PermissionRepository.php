<?php
namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function all()
    {
        return Permission::orderBy('name')->get();
    }

    public function find($id)
    {
        return Permission::findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::create(['name' => $data['name']]);
            DB::commit();
            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PermissionRepository::create error - " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);
            $permission->name = $data['name'];
            $permission->save();
            DB::commit();
            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PermissionRepository::update error - " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);
            // Prevent deletion if attached to roles
            if ($permission->roles()->count() > 0) {
                throw new \Exception('Permission is assigned to one or more roles');
            }
            $permission->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PermissionRepository::delete error - " . $e->getMessage());
            throw $e;
        }
    }

    public function groupedByPrefix()
    {
        // Group permissions by prefix before hyphen, e.g. 'forms.create' => 'forms'
        $permissions = $this->all();
        $grouped = [];
        foreach ($permissions as $p) {
            $parts = preg_split('/[\.\-_]/', $p->name);
            $prefix = $parts[0] ?? 'other';
            $grouped[$prefix][] = $p;
        }
        return $grouped;
    }
}
