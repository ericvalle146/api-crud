<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('tasks', TaskController::class);

Route::prefix('auth')->group(function () {
    Route::resource('users', UserController::class);
});
