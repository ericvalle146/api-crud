<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\DTOs\Common\PaginationDTO;
use App\Models\User;
use App\Support\Pagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;

class FetchUserList
{
    public function handle(PaginationDTO $dto): LengthAwarePaginator|Collection
    {
        $query = User::query();

        return Pagination::apply($query, $dto);
    }
}
