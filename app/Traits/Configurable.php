<?php

namespace App\Http\Traits;

use App\Models\Configuration;
use Illuminate\Support\Facades\Cache;

trait Configurable
{
    public function getConfiguration($key, $default = null)
    {
        // not cashed
        $configurations = Configuration::pluck('value', 'key')->toArray();
        return $configurations[$key] ?? $default;
    }


    // if cached 
    // public function clearSettingsCache()
    // {
    //     Cache::forget('configurations');
    // }
}
