<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::with('roles')->latest()->get();
    }

    public function find($id)
    {
        return User::with('roles')->findOrFail($id); 
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if (!empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("User Create Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            if (!empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("User Update Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("User Delete Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function resetPassword($email, $token)
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function updatePasswordByEmail(string $email, string $password)
    {
        try {
            DB::beginTransaction();
            $user = User::where('email', $email)->firstOrFail();
            $user->password = Hash::make($password);
            $user->save();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("User updatePasswordByEmail Error: ".$e->getMessage());
            throw $e;
        }
    }

}
