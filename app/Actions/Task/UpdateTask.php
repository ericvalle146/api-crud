<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\DTOs\Task\UpdateTaskDTO;
use App\Enums\Task\TaskStatus;
use App\Events\Task\TaskCompleted;
use App\Models\Task;

class UpdateTask
{
    public function __construct(
        private FetchTask $fetchTask
    ) {}

    public function handle(string $id, UpdateTaskDTO $dto): Task
    {
        $updateData = $dto->toArray();
        $task = $this->fetchTask->handle($id);

        if (
            isset($updateData['status']) &&
            $updateData['status'] === TaskStatus::COMPLETED->value &&
            $task->status !== TaskStatus::COMPLETED->value
        ) {
            TaskCompleted::dispatch($task);
        }

        $task->update($updateData);

        return $task;
    }
}
