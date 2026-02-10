<?php

namespace App\Providers;

use App\Interfaces\BranchInterface;
use App\Interfaces\ClientRepositoryInterface;
use App\Interfaces\ConfigurationInterface;
use App\Interfaces\FormRepositoryInterface;
use App\Interfaces\KanbanBoardRepositoryInterface;
use App\Interfaces\KanbanColumnRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;
use App\Interfaces\PasswordResetRepositoryInterface;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\SettingRepositoryInterface;
use App\Interfaces\SubmissionRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Repositories\FormRepository;
use App\Repositories\KanbanBoardRepository;
use App\Repositories\KanbanColumnRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PasswordResetRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SubmissionRepository;
use App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserInterface;
use App\Interfaces\RoleInterface;
use App\Interfaces\PermissionInterface;
use App\Repositories\ConfigurationRepository;
use App\Repositories\UserRepository;
use App\Repositories\PermissionR;
use App\Repositories\RoleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ConfigurationInterface::class, ConfigurationRepository::class);
        $this->app->bind(FormRepositoryInterface::class, FormRepository::class);
        $this->app->bind(SubmissionRepositoryInterface::class, SubmissionRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(KanbanBoardRepositoryInterface::class, KanbanBoardRepository::class);
        $this->app->bind(KanbanColumnRepositoryInterface::class, KanbanColumnRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(PasswordResetRepositoryInterface::class, PasswordResetRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
