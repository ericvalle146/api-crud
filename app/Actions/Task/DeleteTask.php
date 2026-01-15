<?php

declare(strict_types=1);

namespace App\Actions\Task;

class DeleteTask
{
    public function __construct(
        private FetchTask $fetchTask
    ) {}

    public function execute(string $id): void
    {
        $task = $this->fetchTask->execute($id);
        $task->delete();

    }
}
