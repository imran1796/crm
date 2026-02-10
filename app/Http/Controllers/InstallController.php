<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class InstallController extends Controller
{
    public function index()
    {
        if (File::exists(storage_path('installed'))) {
            return redirect('/');
        }

        $token = File::exists(storage_path('installer_token.txt'))
            ? trim(File::get(storage_path('installer_token.txt')))
            : null;

        return view('install.index', compact('token'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string',
            'app_url'  => 'required|url',
            'db_host'  => 'required|string',
            'db_port'  => 'required|string',
            'db_name'  => 'required|string',
            'db_user'  => 'required|string',
        ]);

        // 1️⃣ Update .env file
        $envPath = base_path('.env');
        $env = File::get($envPath);

        $env = preg_replace('/^APP_NAME=.*/m', 'APP_NAME="'.$request->app_name.'"', $env);
        $env = preg_replace('/^APP_URL=.*/m', 'APP_URL='.$request->app_url, $env);
        $env = preg_replace('/^DB_HOST=.*/m', 'DB_HOST='.$request->db_host, $env);
        $env = preg_replace('/^DB_PORT=.*/m', 'DB_PORT='.$request->db_port, $env);
        $env = preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE='.$request->db_name, $env);
        $env = preg_replace('/^DB_USERNAME=.*/m', 'DB_USERNAME='.$request->db_user, $env);
        $env = preg_replace('/^DB_PASSWORD=.*/m', 'DB_PASSWORD='.$request->db_pass, $env);
        $env = preg_replace('/^SESSION_DRIVER=.*/m', 'SESSION_DRIVER=database', $env);

        File::put($envPath, $env);

        // 2️⃣ Cache clear to reload env
        Artisan::call('config:clear');

        try {
            // 3️⃣ Run migrations (try/catch safety)
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            return back()->withErrors(['db' => 'Database connection failed: ' . $e->getMessage()]);
        }

        // 4️⃣ Mark installed
        File::put(storage_path('installed'), now()->toDateTimeString());

        // 5️⃣ Clear caches again
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');


        return redirect('/')->with('success', 'Installation complete!');
    }
}
