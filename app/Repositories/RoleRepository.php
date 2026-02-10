<?php
namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleRepository implements RoleRepositoryInterface
{
    public function all()
    {
        return Role::with('permissions')->get();
    }

    public function paginate($perPage = 20)
    {
        return Role::with('permissions')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function find($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            $role = Role::create(['name' => $data['name']]);

            if (!empty($data['permissions']) && is_array($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("RoleRepository::create error - " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);
            $role->name = $data['name'];
            $role->save();

            // If permissions key present, sync (allow empty array to clear)
            if (array_key_exists('permissions', $data)) {
                $role->syncPermissions($data['permissions'] ?? []);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("RoleRepository::update error - " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);
            $role->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("RoleRepository::delete error - " . $e->getMessage());
            throw $e;
        }
    }

    public function getRolePermissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return $role->permissions->pluck('name')->toArray();
    }
}
