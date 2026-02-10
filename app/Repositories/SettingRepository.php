<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Interfaces\SettingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingRepository implements SettingRepositoryInterface
{
    public function getAll()
    {
        $settings = Setting::pluck('value', 'key');
        
        // Decode JSON values and convert boolean strings back to booleans
        $processed = $settings->map(function ($value, $key) {
            // Handle boolean strings first (only if value is exactly '1' or '0')
            if ($value === '1') {
                return true;
            }
            if ($value === '0') {
                return false;
            }
            
            // Try to decode JSON (for any remaining JSON-encoded values)
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
            
            // Return original value if not JSON
            return $value;
        })->toArray();
        
        // Reconstruct nested structures from flattened keys
        return $this->unflattenArray($processed);
    }

    /**
     * Reconstruct nested arrays from dot-notation keys
     * Example: ['email_new_submission' => true] becomes ['email' => ['new_submission' => true]]
     */
    private function unflattenArray(array $array)
    {
        $result = [];
        
        foreach ($array as $key => $value) {
            // Check if key contains underscore (flattened key)
            if (strpos($key, '_') !== false) {
                $keys = explode('_', $key);
                $current = &$result;
                
                // Navigate/create nested structure
                for ($i = 0; $i < count($keys) - 1; $i++) {
                    if (!isset($current[$keys[$i]])) {
                        $current[$keys[$i]] = [];
                    }
                    $current = &$current[$keys[$i]];
                }
                
                // Set the final value
                $current[end($keys)] = $value;
            } else {
                // Simple key without underscore, keep as is
                $result[$key] = $value;
            }
        }
        
        return $result;
    }

    public function getByKey($key)
    {
        return Setting::where('key', $key)->first();
    }

    public function storeOrUpdate($key, $value)
    {
        try {
            DB::beginTransaction();

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Setting storeOrUpdate error: ".$e->getMessage());
            throw $e;
        }
    }

    public function updateMany(array $data)
    {
        try {
            DB::beginTransaction();

            $flattened = $this->flattenArray($data);
            
            foreach ($flattened as $key => $value) {
                // Handle null values
                if ($value === null) {
                    $value = '';
                }
                // Handle nested arrays/objects (shouldn't happen after flattening, but keep as fallback)
                elseif (is_array($value) || is_object($value)) {
                    // Store nested structures as JSON (fallback for unflattened data)
                    $value = json_encode($value);
                }
                // Handle boolean values (check this before integer/string checks)
                elseif (is_bool($value)) {
                    // Convert boolean to string representation
                    $value = $value ? '1' : '0';
                }
                // Handle integer 1/0 as booleans
                elseif ($value === 1 || $value === 0 || $value === '1' || $value === '0') {
                    $value = ($value === 1 || $value === '1') ? '1' : '0';
                }
                // Handle string representations of booleans
                elseif (is_string($value)) {
                    $lowerValue = strtolower(trim($value));
                    if (in_array($lowerValue, ['true', 'false', 'yes', 'no', 'on', 'off'], true)) {
                        $value = in_array($lowerValue, ['true', 'yes', 'on'], true) ? '1' : '0';
                    } else {
                        // Regular string value
                        $value = (string) $value;
                    }
                } else {
                    // Convert to string for other types
                    $value = (string) $value;
                }

                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Setting update error: ".$e->getMessage());
            throw $e;
        }
    }

    /**
     * Flatten nested arrays into dot-notation keys
     * Example: ['email' => ['new_submission' => true]] becomes ['email_new_submission' => true]
     * Example: ['smtp' => ['host' => 'smtp.example.com']] becomes ['smtp_host' => 'smtp.example.com']
     */
    private function flattenArray(array $array, $prefix = '', $preserveKeys = [])
    {
        $result = [];
        
        foreach ($array as $key => $value) {
            $newKey = $prefix ? $prefix . '_' . $key : $key;
            
            // Preserve certain keys as JSON (if needed in future)
            if (!empty($preserveKeys) && in_array($key, $preserveKeys) && (is_array($value) || is_object($value))) {
                $result[$key] = $value; // Keep original key for preserved items
            }
            // Flatten nested arrays/objects
            elseif (is_array($value) || is_object($value)) {
                $result = array_merge($result, $this->flattenArray((array)$value, $newKey, $preserveKeys));
            }
            // Simple values
            else {
                $result[$newKey] = $value;
            }
        }
        
        return $result;
    }
}
