<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;

class InstallerService
{
    protected $userService;

    // TTL in seconds to wait before retrying DB check (not needed normally)
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Main install orchestration.
     *
     * Steps (safe):
     *  - prepare minimal .env if missing
     *  - write .env with posted values (backup existing)
     *  - generate key
     *  - test DB connection
     *  - run migrations and optional seeders
     *  - create admin user via UserService (explicit installer flag)
     *  - write install lock
     *  - return summary
     */
    public function install(array $data)
    {
        $summary = [];

        // 0. Optional installer token validation (if you included installer token in ZIP)
        if (!empty($data['installer_token'])) {
            $this->validateInstallerToken($data['installer_token']);
        }

        // 1. ensure minimal env exists for app bootstrap (if .env missing copy .env.example.install)
        $this->ensureBootstrapEnv();

        // 2. backup existing .env if exists
        $this->backupEnvIfExists();

        // 3. write new .env with provided data
        $this->writeEnv($data);
        $summary['env_written'] = true;

        // 4. generate app key
        Artisan::call('key:generate', ['--force' => true]);
        $summary['app_key_generated'] = true;

        // 5. test DB connection
        $this->testDatabaseConnection();
        $summary['db_test'] = true;

        // 6. run migrations (force)
        try {
            Artisan::call('migrate', ['--force' => true]);
            $summary['migrated'] = true;
        } catch (\Exception $e) {
            Log::error('Installer migration error: ' . $e->getMessage());
            $this->restoreEnvBackup();
            throw new Exception('Database migration failed: ' . $e->getMessage());
        }

        // 7. optional seeder
        try {
            if (class_exists(\Database\Seeders\DatabaseSeeder::class)) {
                Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
                $summary['seeded'] = true;
            } else {
                $summary['seeded'] = false;
            }
        } catch (\Exception $e) {
            Log::warning('Installer seeder error: ' . $e->getMessage());
            $summary['seeded'] = false;
        }

        // 8. create admin user via UserService (installer mode)
        try {
            $adminData = [
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => $data['admin_password'],
            ];

            // pass installer flag true so user service assigns admin role without needing auth
            $admin = $this->userService->createUserFromInstaller($adminData);
            $summary['admin_created'] = $admin->id ?? null;
        } catch (\Exception $e) {
            Log::error('Installer admin creation failed: ' . $e->getMessage());
            $this->restoreEnvBackup();
            throw new Exception('Admin user creation failed: ' . $e->getMessage());
        }

        // 9. write install lock
        $this->createInstallLock();
        $summary['locked'] = true;

        // 10. cache config, clear views
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            Log::warning('Installer cache commands warning: ' . $e->getMessage());
        }

        return $summary;
    }

    protected function ensureBootstrapEnv()
    {
        $envPath = base_path('.env');
        $examplePath = base_path('.env.example.install');
        if (!File::exists($envPath)) {
            if (File::exists($examplePath)) {
                File::copy($examplePath, $envPath);
            } else {
                // fallback, create minimal env to allow bootstrap
                $minimal = "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n";
                File::put($envPath, $minimal);
            }
        }
    }

    protected function envPath()
    {
        return base_path('.env');
    }

    protected function backupPath()
    {
        return storage_path('installed_env_backup_' . now()->format('Ymd_His') . '.env');
    }

    protected function backupEnvIfExists()
    {
        $env = $this->envPath();
        if (File::exists($env)) {
            try {
                File::copy($env, $this->backupPath());
            } catch (\Exception $e) {
                Log::warning('Env backup failed: ' . $e->getMessage());
            }
        }
    }

    protected function restoreEnvBackup()
    {
        $files = glob(storage_path('installed_env_backup_*.env'));
        if (!empty($files)) {
            rsort($files);
            try {
                File::copy($files[0], $this->envPath());
            } catch (\Exception $e) {
                Log::error('Env restore failed: ' . $e->getMessage());
            }
        }
    }

    protected function writeEnv(array $data)
    {
        // Build env array
        $env = [];
        $env['APP_NAME'] = $this->escapeEnv($data['app_name'] ?? 'Laravel');
        $env['APP_ENV'] = 'production';
        $env['APP_DEBUG'] = 'false';
        $env['APP_URL'] = $data['app_url'] ?? url('/') ?? 'http://localhost';

        $env['DB_CONNECTION'] = $data['db_connection'] ?? 'mysql';
        $env['DB_HOST'] = $data['db_host'] ?? '127.0.0.1';
        $env['DB_PORT'] = $data['db_port'] ?? 3306;
        $env['DB_DATABASE'] = $data['db_database'] ?? 'forge';
        $env['DB_USERNAME'] = $data['db_username'] ?? 'forge';
        $env['DB_PASSWORD'] = $data['db_password'] ?? '';

        // mail
        if (!empty($data['mail_driver'])) {
            $env['MAIL_MAILER'] = $data['mail_driver'];
            $env['MAIL_HOST'] = $data['mail_host'] ?? '';
            $env['MAIL_PORT'] = $data['mail_port'] ?? '';
            $env['MAIL_USERNAME'] = $data['mail_username'] ?? '';
            $env['MAIL_PASSWORD'] = $data['mail_password'] ?? '';
            $env['MAIL_FROM_ADDRESS'] = $data['mail_from_address'] ?? '';
            $env['MAIL_FROM_NAME'] = $this->escapeEnv($data['mail_from_name'] ?? $data['app_name'] ?? 'Laravel');
        }

        // APP_KEY placeholder; key:generate will set it
        $env['APP_KEY'] = '';

        // build text
        $contents = '';
        foreach ($env as $k => $v) {
            // properly handle empty values and quotes
            $contents .= $k . '="' . str_replace('"', '\"', (string)$v) . "\"\n";
        }

        try {
            File::put($this->envPath(), $contents);
            @chmod($this->envPath(), 0644);
        } catch (\Exception $e) {
            Log::error('Installer writeEnv failed: ' . $e->getMessage());
            throw new Exception('Failed to write .env: ' . $e->getMessage());
        }
    }

    protected function escapeEnv($value)
    {
        return str_replace('"', '\"', (string)$value);
    }

    protected function testDatabaseConnection()
    {
        try {
            // flush config so new env values are used
            Artisan::call('config:clear');
            // attempt to get PDO
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            Log::error('Installer DB connection test failed: ' . $e->getMessage());
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    protected function createInstallLock()
    {
        $path = storage_path('installed');
        try {
            File::put($path, "Installed on " . now()->toDateTimeString());
            @chmod($path, 0644);
        } catch (\Exception $e) {
            Log::error('Installer lock creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Optional: validate installer token file located at storage/installer_token.txt
     * If you included an installer token in the ZIP, verify it.
     */
    protected function validateInstallerToken($token)
    {
        $path = storage_path('installer_token.txt');
        if (!File::exists($path)) {
            throw new Exception('Installer token missing on server.');
        }
        $serverToken = trim(File::get($path));
        if (!hash_equals($serverToken, $token)) {
            throw new Exception('Invalid installer token.');
        }
        // optionally delete token after use
        File::delete($path);
    }
}
