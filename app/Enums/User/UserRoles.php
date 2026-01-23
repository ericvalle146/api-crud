<?php

declare(strict_types=1);

namespace App\Enums\User;

enum UserRoles: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function all(): array
    {
        return [
            self::ADMIN,
            self::USER,
        ];

    }

    public function description(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador do sistema',
            self::USER => 'Usuário do sistema',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => UserPermissions::all(),
            self::USER => [],
        };
    }
}
