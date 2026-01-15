<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

// Listar todas as tasks
Route::get('/tasks', [TaskController::class, 'index']);

// Listar uma task
Route::get('/tasks/{id}', [TaskController::class, 'show']);

// Criar nova task
Route::post('/tasks', [TaskController::class, 'store']);

// Atualizar uma task
Route::put('/tasks/{id}', [TaskController::class, 'update']);

// Excluir uma task
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
