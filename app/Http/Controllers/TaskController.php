<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Task\CreateTask;
use App\Actions\Task\DeleteTask;
use App\Actions\Task\FetchTask;
use App\Actions\Task\FetchTasksList;
use App\Actions\Task\UpdateTask;
use App\DTOs\Task\CreateTaskDTO;
use App\DTOs\Task\UpdateTaskDTO;
use App\Http\Resources\TaskResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\NoContentResponse;

class TaskController extends Controller
{
    public function index(FetchTasksList $action): ApiSuccessResponse
    {
        $tasks = $action->execute();

        return new ApiSuccessResponse(TaskResource::collection($tasks));

    }

    public function show(FetchTask $action, $id): ApiSuccessResponse
    {
        $task = $action->execute($id);

        return new ApiSuccessResponse(new TaskResource($task));

    }

    public function store(CreateTaskDTO $dto, CreateTask $action): ApiSuccessResponse
    {
        $task = $action->execute($dto);

        return new ApiSuccessResponse(new TaskResource($task));

    }

    public function update(string $id, UpdateTaskDTO $dto, UpdateTask $action): ApiSuccessResponse
    {
        $updated = $action->execute($id, $dto);

        return new ApiSuccessResponse(new TaskResource($updated));

    }

    public function destroy(string $id, DeleteTask $action): NoContentResponse
    {
        $action->execute($id);

        return new NoContentResponse();
    }
}
