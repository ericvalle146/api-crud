<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\DTOs\User\CreateUserDTO;
use App\Models\User;

class CreateUser
{
    public function handle(CreateUserDTO $dto)
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
        ]);

    }
}
