<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CheckInstallation
{
    public function handle(Request $request, Closure $next)
    {
        // Allow installer access if app not installed yet
        if (!File::exists(storage_path('installed'))) {
            if ($request->is('install*')) {
                return $next($request);
            }
            return redirect('/install');
        }

        // App already installed — prevent going back to /install
        if ($request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
}
