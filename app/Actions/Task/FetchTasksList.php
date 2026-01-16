<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\DTOs\Task\FetchTaskListDTO;
use App\Models\Task;

class FetchTasksList
{
    public function execute(FetchTaskListDTO $dto)
    {
        $query = Task::query();

        if ($dto->status) {
            $query->where('status', $dto->status->value);
        }

        return $query->paginate(
            $dto->per_page,
            ['*'],
            'page',
            $dto->page
        );
    }
}
