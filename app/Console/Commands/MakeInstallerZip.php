<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class MakeInstallerZip extends Command
{
    protected $signature = 'make:installer-zip {--include-vendor=0}';
    protected $description = '📦 Create a ready-to-install Laravel ZIP (includes .env.example, storage dirs even if empty)';

    public function handle(): int
    {
        $this->info('🔧 Preparing installer zip...');

        $timestamp  = now()->format('Ymd_His');
        $releaseDir = storage_path('app/releases');
        File::ensureDirectoryExists($releaseDir);

        // Prepare local storage folders
        $this->prepareStorageDirs();

        // ✅ Generate temporary .env for ZIP
        $this->generateInstallerEnv();

        $zipFile = "{$releaseDir}/release-{$timestamp}.zip";
        $includeVendor = (bool) $this->option('include-vendor');

        $excludes = [
            '.env.local',
            'storage/logs',
            'storage/installed',
            'node_modules',
            '.git',
            '.github',
            'bootstrap/cache/config.php',
            'bootstrap/cache/routes.php',
        ];

        $rootPath = base_path();
        $zip = new ZipArchive;

        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error("❌ Could not create zip at: {$zipFile}");
            return 1;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relative = ltrim(str_replace($rootPath, '', $filePath), DIRECTORY_SEPARATOR);

            foreach ($excludes as $ex) {
                if (str_starts_with($relative, trim($ex, '/'))) {
                    continue 2;
                }
            }

            if (!$includeVendor && str_starts_with($relative, 'vendor' . DIRECTORY_SEPARATOR)) {
                continue;
            }

            if (str_starts_with($relative, 'storage' . DIRECTORY_SEPARATOR)
                && !str_starts_with($relative, 'storage/app/public')
                && !str_starts_with($relative, 'storage/framework')
                && !str_contains($relative, 'storage/installer_token.txt')) {
                continue;
            }

            $zip->addFile($filePath, $relative);
        }

        // Ensure important empty dirs are inside
        foreach ([
                     'storage/framework/cache',
                     'storage/framework/views',
                     'storage/framework/sessions',
                     'storage/framework/testing',
                     'storage/app/public'
                 ] as $dir) {
            File::ensureDirectoryExists(base_path($dir), 0755, true);
            $zip->addEmptyDir(str_replace(DIRECTORY_SEPARATOR, '/', ltrim($dir, '/')));
        }

        // ✅ Explicitly include generated .env
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $zip->addFile($envPath, '.env');
            $this->info('✅ Included fresh .env with APP_KEY');
        }

        // Include installer token
        $tokenPath = storage_path('installer_token.txt');
        if (!File::exists($tokenPath)) {
            File::put($tokenPath, bin2hex(random_bytes(32)));
        }
        $zip->addFile($tokenPath, 'storage/installer_token.txt');

        $zip->close();

        // Clean up temporary .env
        if (File::exists($envPath)) {
            File::delete($envPath);
        }

        $this->info("🎉 Installer ZIP created successfully at: {$zipFile}");
        return 0;
    }


    /**
     * Ensure required storage directories exist locally before zipping.
     */
    private function prepareStorageDirs(): void
    {
        $dirs = [
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('framework/testing'),
            storage_path('app/public'),
        ];

        foreach ($dirs as $dir) {
            File::ensureDirectoryExists($dir, 0755, true);
        }

        // Optionally add a .gitkeep to keep dirs visible when checked into VCS.
        foreach ($dirs as $dir) {
            $gitkeep = $dir . DIRECTORY_SEPARATOR . '.gitkeep';
            if (!File::exists($gitkeep)) {
                // Create a small placeholder — optional. You may remove this to keep dirs empty locally.
                File::put($gitkeep, '');
            }
        }

        $this->info('✅ Verified storage/framework directories (cache, sessions, views, testing, app/public)');
    }

    /**
     * Generate a temporary .env file with a new APP_KEY
     */
    private function generateInstallerEnv(): void
    {
        $examplePath = base_path('.env.example');
        $envPath     = base_path('.env');

        if (!File::exists($examplePath)) {
            $this->warn('⚠️  No .env.example found, skipping .env generation.');
            return;
        }

        // Copy .env.example -> .env
        File::copy($examplePath, $envPath);

        // Add new app key
        $key = 'base64:' . base64_encode(random_bytes(32));

        // Replace APP_KEY line
        $envContent = File::get($envPath);
        $envContent = preg_replace('/^APP_KEY=.*/m', "APP_KEY={$key}", $envContent);

        File::put($envPath, $envContent);

        $this->info('🔑 Generated fresh APP_KEY for new .env');
    }


}
