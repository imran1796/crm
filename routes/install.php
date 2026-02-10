<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;

Route::get('/install', [InstallController::class, 'index'])->name('install.index');
Route::post('/install/store', [InstallController::class, 'store'])->name('install.store');
