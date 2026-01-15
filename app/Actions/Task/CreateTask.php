<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\DTOs\Task\CreateTaskDTO;
use App\Models\Task;

class CreateTask
{
    public function execute(CreateTaskDTO $dto): Task
    {

        return Task::create($dto->toArray());
    }
}
