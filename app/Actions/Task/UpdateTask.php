<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\DTOs\Task\UpdateTaskDTO;

class UpdateTask
{
    public function __construct(
        private FetchTask $fetchTask
    ) {}

    public function handle(string $id, UpdateTaskDTO $dto)
    {
        $task = $this->fetchTask->handle($id);

        $task->update($dto->toArray());

        return $task;
    }
}
