<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\DTOs\User\CreateUserDTO;
use App\Events\User\UserCreated;
use App\Models\User;

class CreateUser
{
    public function handle(CreateUserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);
        $user->assignRole('user');

        UserCreated::dispatch($user);

        return $user;
    }
}
