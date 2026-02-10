<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserWithRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'user-list','department'=>'general']);
        Permission::create(['name' => 'user-create','department'=>'general']);
        Permission::create(['name' => 'user-edit','department'=>'general']);
        Permission::create(['name' => 'user-delete','department'=>'general']);
        Permission::create(['name' => 'role-list','department'=>'general']);
        Permission::create(['name' => 'role-create','department'=>'general']);
        Permission::create(['name' => 'role-edit','department'=>'general']);
        Permission::create(['name' => 'role-delete','department'=>'general']);
        Permission::create(['name' => 'permission-list','department'=>'general']);
        Permission::create(['name' => 'permission-create','department'=>'general']);
        Permission::create(['name' => 'permission-edit','department'=>'general']);
        Permission::create(['name' => 'permission-delete','department'=>'general']);

        Permission::create(['name' => 'configuration','department'=>'general']);
        Permission::create(['name' => 'user-permission','department'=>'general']);
        Permission::create(['name'=>'setting','department'=>'general']);


        Permission::create(['name' => 'branch-list','department'=>'general']);
        Permission::create(['name' => 'branch-create','department'=>'general']);
        Permission::create(['name' => 'branch-edit','department'=>'general']);
        Permission::create(['name' => 'branch-delete','department'=>'general']);


        $adminRole = Role::create(['name' => 'system-admin']);
        $adminRole->givePermissionTo([
            'setting','user-permission','configuration','user-list','user-create','user-edit','user-delete',
            'role-list','role-create','role-edit','role-delete',
            'permission-list','permission-create','permission-edit','permission-delete',
            'branch-list','branch-create','branch-edit','branch-delete',
        ]);

        $systemAdmin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@globelink.com',
            'password' => Hash::make('123456')
        ]);

        $systemAdmin->assignRole('system-admin');
    }
}
