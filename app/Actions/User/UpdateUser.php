<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\DTOs\User\UpdateUserDTO;
use App\Models\User;

class UpdateUser
{
    public function __construct(
        private FetchUser $fetchUser
    ) {}

    public function handle(string $id, UpdateUserDTO $dto): User
    {
        $user = $this->fetchUser->handle($id);
        $user->fill($dto->toArray());
        $user->save();

        return $user;
    }
}
