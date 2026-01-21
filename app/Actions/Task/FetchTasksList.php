<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\DTOs\Task\FetchTaskListDTO;
use App\Models\Task;
use App\Support\Pagination;

class FetchTasksList
{
    public function handle(FetchTaskListDTO $dto)
    {
        $query = Task::query();

        if ($dto->status) {
            $query->where('status', $dto->status->value);
        }

        return Pagination::apply($query, $dto);
    }
}
