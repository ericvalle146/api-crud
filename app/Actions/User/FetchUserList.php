<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\DTOs\Common\PaginationDTO;
use App\Models\User;
use App\Support\Pagination;

class FetchUserList
{
    public function handle(PaginationDTO $dto)
    {
        $query = User::query();

        return Pagination::apply($query, $dto);
    }
}
