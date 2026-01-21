<?php

declare(strict_types=1);

namespace App\Actions\Task;

class DeleteTask
{
    public function __construct(
        private FetchTask $fetchTask
    ) {}

    public function handle(string $id): void
    {
        $this->fetchTask->handle($id)->delete();
    }
}
