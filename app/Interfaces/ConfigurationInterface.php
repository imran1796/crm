<?php

namespace App\Interfaces;

interface ConfigurationInterface
{
    public function getAllConfigurations();
    public function getConfigurationById($id);
    public function getConfigurationKey($key);
    public function createConfiguration(array $configuration);
    public function deleteConfiguration();
    public function updateConfiguration(array $configuration, $id);
}
