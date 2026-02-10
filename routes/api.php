<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // API Auth
    Route::post('/login', [\App\Http\Controllers\Api\AuthApiController::class, 'login']);
    Route::post('/forms/{slug}/submit', [\App\Http\Controllers\Api\SubmissionController::class, 'submit']);
    Route::post('/forgot-password', [\App\Http\Controllers\Api\PasswordResetController::class, 'sendResetLink']);

    // Protected API Routes
    Route::middleware(['auth:sanctum'])->group(function () {

        /*Dashboard */


        Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'stats']);
        Route::post('/logout', [\App\Http\Controllers\Api\AuthApiController::class, 'logout']);

        /** User management API */
        //  Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);


        Route::middleware(['role:Admin|system-admin'])->group(function () {

               Route::prefix('roles')->group(function () {
                   Route::get('/', [\App\Http\Controllers\Api\RoleController::class, 'index']);
                   Route::post('/', [\App\Http\Controllers\Api\RoleController::class, 'store']);
                   Route::put('/{id}', [\App\Http\Controllers\Api\RoleController::class, 'update']);
                   Route::delete('/{id}', [\App\Http\Controllers\Api\RoleController::class, 'destroy']);
               });

               Route::prefix('permissions')->group(function () {
                   Route::get('/', [\App\Http\Controllers\Api\PermissionController::class, 'index']);
                   Route::post('/', [\App\Http\Controllers\Api\PermissionController::class, 'store']);
                   Route::put('/{id}', [\App\Http\Controllers\Api\PermissionController::class, 'update']);
                   Route::delete('/{id}', [\App\Http\Controllers\Api\PermissionController::class, 'destroy']);


               });
        });

        /* Forms */
        Route::prefix('forms')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\FormController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\FormController::class, 'store']);
            Route::put('/{id}/toggle', [\App\Http\Controllers\Api\FormController::class, 'toggle']);
            Route::delete('/{id}',[\App\Http\Controllers\Api\FormController::class, 'destroy']);

            /*Submission */
        });

        Route::prefix('submissions')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\SubmissionController::class, 'index']);
            Route::put('/{id}/read', [\App\Http\Controllers\Api\SubmissionController::class, 'markRead']);
        });

        /* Clients */

        Route::prefix('clients')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\ClientController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\ClientController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\ClientController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\ClientController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\ClientController::class, 'destroy']);
            Route::get('/search', [\App\Http\Controllers\Api\ClientController::class, 'search']);
        });
        Route::prefix('kanban')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\KanbanBoardController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\KanbanBoardController::class, 'store']);
            Route::post('/{id}/columns', [\App\Http\Controllers\Api\KanbanBoardController::class, 'addColumn']);
           // Route::post('/{id}/columns/reorder', [\App\Http\Controllers\Api\KanbanBoardController::class, 'reorderColumns']);
            Route::put('/{boardId}/columns/{columnId}', [\App\Http\Controllers\Api\KanbanBoardController::class, 'renameColumn']);
            Route::get('/{id}', [\App\Http\Controllers\Api\KanbanBoardController::class, 'show']);
            Route::get('/{id}/tasks', [\App\Http\Controllers\Api\TaskController::class, 'boardTasks']);
            Route::post('/{id}/tasks', [\App\Http\Controllers\Api\TaskController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\KanbanBoardController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\KanbanBoardController::class, 'destroy']);
            Route::delete('/{boardId}/columns/{columnId}', [\App\Http\Controllers\Api\KanbanBoardController::class, 'deleteColumn']);
        });

        Route::prefix('tasks')->group(function () {
            Route::post('/', [\App\Http\Controllers\Api\TaskController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\TaskController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\TaskController::class, 'destroy']);
            //Route::post('/reorder', [\App\Http\Controllers\Api\TaskController::class, 'reorder']);
            Route::get('/filter', [\App\Http\Controllers\Api\TaskController::class, 'filter']);
            Route::post('/move', [\App\Http\Controllers\Api\TaskController::class, 'move']);
            Route::put('/{id}/move', [\App\Http\Controllers\Api\TaskController::class,'moveToBoard']);
        });

        // Settings
           Route::prefix('settings')->group(function () {
               Route::get('/', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
               Route::post('/', [\App\Http\Controllers\Api\SettingsController::class, 'update']);
               Route::put('/', [\App\Http\Controllers\Api\SettingsController::class, 'update']);
               Route::get('/notifications', [\App\Http\Controllers\Api\SettingsController::class, 'getNotifications']);
               Route::put('/notifications', [\App\Http\Controllers\Api\SettingsController::class, 'updateNotifications']);
               Route::post('/test-mail', [\App\Http\Controllers\Api\SettingsController::class, 'testMail']);
              // Route::post('/test-email', [\App\Http\Controllers\Api\SettingsController::class, 'testMail']);
           });


        //Notifications
          Route::prefix('notifications')->group(function () {
               Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
               Route::put('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markRead']);
               Route::put('/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllRead']);
               Route::delete('/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'destroy']);
           });

        // SMTP Test


        //Users
         
            Route::prefix('users')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\UserController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Api\UserController::class, 'store']);
                Route::get('/profile', [App\Http\Controllers\Api\UserController::class, 'profile']);
                Route::put('/profile/{id}', [App\Http\Controllers\Api\UserController::class, 'update']);
              //  Route::put('/{id}', [App\Http\Controllers\Api\UserController::class, 'update']);
                Route::post('/{id}/reset-password', [\App\Http\Controllers\Api\PasswordResetController::class, 'sendResetLink']); 
                Route::delete('/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
            });

        //Password Reset
             Route::prefix('password')->group(function () {
                 Route::post('/reset', [\App\Http\Controllers\Api\PasswordResetController::class, 'sendResetLink']);
                 Route::post('/update', [\App\Http\Controllers\Api\PasswordResetController::class, 'reset']);
             });


    });
});
