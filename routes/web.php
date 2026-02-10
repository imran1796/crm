<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
require __DIR__ . '/install.php';



Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    return 'Configuration cache cleared!';
});
Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
    // Route::post('/get_data', 'App\Http\Controllers\HomeController@getData')->name('dashboard.get_data');

    Route::get('profile', 'App\Http\Controllers\ProfileController@profile')->name('profile');
    Route::patch('profile', 'App\Http\Controllers\ProfileController@update')->name('profile.update');
    Route::patch('profile/password', 'App\Http\Controllers\ProfileController@password')->name('profile.password');

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::resource('configurations', App\Http\Controllers\ConfigurationController::class);

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::post('users/verify/{id}', [App\Http\Controllers\UserController::class, 'verifyUser'])->name('users.verify');
    Route::get('users/permission/{id}', [App\Http\Controllers\UserController::class, 'userPermission'])->name('users.permission');
    Route::post('users/permission', [App\Http\Controllers\UserController::class, 'updateUserPermission'])->name('users.permission.update');

    Route::group(['prefix' => 'departments'], function () {
        Route::get('/index', [\App\Http\Controllers\UserController::class, 'indexDepartment'])->name('departments.index');
        Route::post('/store', [\App\Http\Controllers\UserController::class, 'storeDepartment'])->name('departments.store');
        Route::put('/update/{id}', [\App\Http\Controllers\UserController::class, 'updateDepartment'])->name('departments.update');
        Route::delete('/delete/{id}', [\App\Http\Controllers\UserController::class, 'deleteDepartment'])->name('departments.delete');
    });

    Route::group(['prefix' => 'designations'], function () {
        Route::get('/index', [\App\Http\Controllers\UserController::class, 'indexDesignation'])->name('designations.index');
        Route::post('/store', [\App\Http\Controllers\UserController::class, 'storeDesignation'])->name('designations.store');
        Route::put('/update/{id}', [\App\Http\Controllers\UserController::class, 'updateDesignation'])->name('designations.update');
        Route::delete('/delete/{id}', [\App\Http\Controllers\UserController::class, 'deleteDesignation'])->name('designations.delete');
    });

    Route::get('test-page', [App\Http\Controllers\HomeController::class, 'testPage'])->name('test.page');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);

/* Installer Install*/


});
