<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Safe Installer Mode (no .env or missing APP_KEY) — DISABLED
|--------------------------------------------------------------------------
|
| Before Laravel boots, we check whether `.env` exists and has APP_KEY.
| If not, we display the installer view manually (no encryption, no session).
| Install process commented out so app always boots normally.
|
*/
// $basePath = __DIR__ . '/../';
// $envPath  = $basePath . '.env';
// $envExists = file_exists($envPath);
// $appKey    = null;
// if ($envExists) {
//     $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//     foreach ($lines as $line) {
//         if (strpos($line, 'APP_KEY=') === 0) {
//             $appKey = trim(explode('=', $line, 2)[1] ?? '');
//             break;
//         }
//     }
// }
// if (!$envExists || !$appKey) {
//     require $basePath . 'vendor/autoload.php';
//     $installerView = $basePath . 'resources/views/install/index.blade.php';
//     if (file_exists($installerView)) {
//         $content = file_get_contents($installerView);
//         echo str_replace('{{ $token }}', 'installer-mode', $content);
//         exit;
//     }
//     echo <<<HTML
//         <h1> Installer Mode</h1>
//         <p>No <code>.env</code> or <code>APP_KEY</code> found.</p>
//         <p>Please visit <a href="/install">/install</a> to configure your application.</p>
//     HTML;
//     exit;
// }

/*
|--------------------------------------------------------------------------
| Continue Normal Laravel Boot
|--------------------------------------------------------------------------
*/

// Maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap the Laravel app (Laravel 12 style)
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle the HTTP request
$app->handleRequest(Request::capture());
