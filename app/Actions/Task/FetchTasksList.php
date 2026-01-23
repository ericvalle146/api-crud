<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\DTOs\Task\FetchTaskListDTO;
use App\Models\Task;
use App\Support\Pagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;

class FetchTasksList
{
    public function handle(FetchTaskListDTO $dto): LengthAwarePaginator|Collection
    {
        $query = Task::query();

        if ($dto->status) {
            $query->where('status', $dto->status->value);
        }

        return Pagination::apply($query, $dto);
    }
}
