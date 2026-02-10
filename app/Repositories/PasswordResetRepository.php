<?php
namespace App\Repositories;

use App\Interfaces\PasswordResetRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    protected $table = 'password_reset_tokens';

    public function createToken(string $email, string $tokenHash)
    {
        try {
            DB::beginTransaction();

            // delete old tokens for safety
            DB::table($this->table)->where('email', $email)->delete();

            DB::table($this->table)->insert([
                'email' => $email,
                'token' => $tokenHash,
                'created_at' => Carbon::now()
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PasswordReset createToken Error: '.$e->getMessage());
            throw $e;
        }
    }

    public function findByEmail(string $email)
    {
        return DB::table($this->table)->where('email', $email)->first();
    }

    public function deleteByEmail(string $email)
    {
        try {
            DB::beginTransaction();
            DB::table($this->table)->where('email', $email)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PasswordReset deleteByEmail Error: '.$e->getMessage());
            throw $e;
        }
    }
}
