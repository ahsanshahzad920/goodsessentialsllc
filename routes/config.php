<?php

use App\Http\Controllers\Configs\ConfigRouteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Super Admin'])
    ->controller(ConfigRouteController::class)
    ->group(function () {
        // ************** Migration Related Routes  **************
        Route::get('artisan/migrate', 'migrate');
        Route::get('artisan/migrate-fresh-seed', 'migrateFreshSeed');
        Route::get('artisan/rollback-step-one', 'rollbackStepOne');
        Route::get('artisan/migrate-status', 'migrateStatus');
        Route::get('artisan/rollback',  'rollback');

        // ************** Optimizing and Cache Related Routes  **************
        Route::get('artisan/optimize', 'optimize');
        Route::get('artisan/optimize-clear',  'optimizeClear');

        // ************** Storage Related Routes  **************
        Route::get('artisan/storage-link', 'storageLink');

        // ************** Queue Related Routes  **************
        Route::get('artisan/queue-work', 'queueWork');

        // ************** Route List Related Routes  **************
        Route::get('artisan/route/list', 'routeList');
    });
