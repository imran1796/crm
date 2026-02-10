<?php

namespace App\Interfaces;

interface PasswordResetRepositoryInterface
{
    public function createToken(string $email, string $tokenHash);
    public function findByEmail(string $email);
    public function deleteByEmail(string $email);
}
