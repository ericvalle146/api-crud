<?php

declare(strict_types=1);

use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('tasks', TaskController::class);

Route::prefix('auth')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [UserController::class, 'index'])
        ->can('users.list');

    Route::get('users/{user}', [UserController::class, 'show'])
        ->can('users.view');

    Route::post('users', [UserController::class, 'store'])
        ->can('users.create');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->can('users.update');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->can('users.delete');
});
