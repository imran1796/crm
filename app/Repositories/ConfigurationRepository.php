<?php

namespace App\Repositories;

use App\Interfaces\ConfigurationInterface;
use App\Models\Configuration;
use Illuminate\Support\Facades\Log;

class ConfigurationRepository implements ConfigurationInterface
{
    public function getAllConfigurations()
    {
        // TODO: Implement getAllConfiguration() method.
        return Configuration::all();
    }

    public function getConfigurationById($id){
        return Configuration::findOrFail($id);
    }

    public function getConfigurationKey($key){
        return Configuration::where('key',$key)->get();
    }

    public function createConfiguration(array $configuration)
    {
        \DB::beginTransaction();
        try {
            Configuration::create($configuration);

            \DB::commit();

            return response()->json(['success' => 'Successfully Added New Config'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error Creating Configuration: ' . $e->getMessage());
            return response()->json(['error' => "An Error Occurred"], 500);
        }
    }

    public function deleteConfiguration()
    {
        // TODO: Implement deleteConfiguration() method.
    }

    public function updateConfiguration(array $configuration, $id)
    {

        \DB::beginTransaction();
        try {
            $configurationToUpdate = Configuration::findOrFail($id);
            $configurationToUpdate->update($configuration);

            \DB::commit();

            return response()->json([
                'success' => 'Successfully Updated Configuration'
            ], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error updating Configuration: ' . $e->getMessage());
            return response()->json([
                'error' => 'An Error Occurred'
            ], 500);
        }
    }
}
