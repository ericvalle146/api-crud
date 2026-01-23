<?php

declare(strict_types=1);

namespace Tests\Feature\Helpers;

use App\Models\User;
use Database\Seeders\Users\CreateUserPermissions;
use Database\Seeders\Users\CreateUserRoles;

class UserHelpers
{
    public static function createTestUser()
    {
        return User::factory()->create();
    }

    public static function createTestAdminUser()
    {
        (new CreateUserPermissions())->run();
        (new CreateUserRoles())->run();

        return User::factory()->create()->assignRole('admin');
    }

    public static function createAdminToken()
    {
        $admin = self::createTestAdminUser();

        return $admin->createToken('test_token')->plainTextToken;
    }

    public static function createFakeTestUser()
    {
        return User::factory()->make();
    }

    public static function createTestAdminUserAuthenticated(): string
    {
        return self::createAdminToken();
    }
}
