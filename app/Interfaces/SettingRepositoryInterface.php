<?php

namespace App\Interfaces;

interface SettingRepositoryInterface
{
    public function getAll();
    public function updateMany(array $data);
    public function getByKey($key);
    public function storeOrUpdate($key, $value);
}
