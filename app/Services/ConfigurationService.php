<?php

namespace App\Services;

use App\Interfaces\ConfigurationInterface;
use App\Repositories\DatabaseRepository;

class ConfigurationService
{
    protected $configurationRepository;
    protected $databaseRepository;

    public function __construct(ConfigurationInterface $configurationRepository, DatabaseRepository $databaseRepository)
    {
        $this->configurationRepository = $configurationRepository;
        $this->databaseRepository = $databaseRepository;
    }

    public function getData($model, $where = null)
    {
        if ($where) {
            return $this->databaseRepository->getDataWhere($model, [], $where);
        } else {
            return $this->databaseRepository->getAllRecords($model, []);
        }
    }

    public function getAllConfigurations()
    {
        return $this->configurationRepository->getAllConfigurations();
    }

    public function getConfigurationById($id)
    {
        return $this->configurationRepository->getConfigurationById($id);
    }

    public function getConfigurationKey($key)
    {
        return $this->configurationRepository->getConfigurationKey($key);
    }

    public function createConfiguration(array $configuration)
    {
        $configuration['key'] = strtolower(trim($configuration['key']));
        $configuration['key'] = preg_replace('/[\s\-]+/', '_', $configuration['key']);

        return $this->configurationRepository->createConfiguration($configuration);
    }

    public function updateConfiguration(array $configuration, $id)
    {
        $configuration['key'] = strtolower(trim($configuration['key']));
        $configuration['key'] = preg_replace('/[\s\-]+/', '_', $configuration['key']);

        return $this->configurationRepository->updateConfiguration($configuration, $id);
    }

    public function deleteConfiguration($id)
    {
        return $this->configurationRepository->deleteConfiguration($id);
    }
}
