<?php

declare(strict_types=1);

namespace App\DTOs\User;

use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class UpdateUserDTO extends ValidatedDTO
{
    public ?string $name;

    public ?string $email;

    public ?string $password;

    protected function rules(): array
    {
        return [
            'name' => ['sometimes', 'nullable', 'string'],
            'email' => ['sometimes', 'nullable', 'email', 'unique:users,email,' . request()->route('user')],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
        ];
    }

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'name' => new StringCast(),
            'email' => new StringCast(),
            'password' => new StringCast(),
        ];
    }
}
