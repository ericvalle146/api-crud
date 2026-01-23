<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Actions\Task\CreateTask;
use App\Actions\Task\DeleteTask;
use App\Actions\Task\FetchTask;
use App\Actions\Task\FetchTasksList;
use App\Actions\Task\UpdateTask;
use App\DTOs\Task\CreateTaskDTO;
use App\DTOs\Task\FetchTaskListDTO;
use App\DTOs\Task\UpdateTaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\NoContentResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index(FetchTaskListDTO $dto, FetchTasksList $action): JsonResponse
    {
        return TaskResource::collection($action->handle($dto))->response();
    }

    public function show(FetchTask $action, $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new TaskResource($action->handle($id)));
    }

    public function store(CreateTaskDTO $dto, CreateTask $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new TaskResource($action->handle($dto)), Response::HTTP_CREATED);
    }

    public function update(string $id, UpdateTaskDTO $dto, UpdateTask $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new TaskResource($action->handle($id, $dto)));
    }

    public function destroy(string $id, DeleteTask $action): NoContentResponse
    {
        $action->handle($id);

        return new NoContentResponse();
    }
}
