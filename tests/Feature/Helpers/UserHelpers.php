<?php

declare(strict_types=1);

namespace Tests\Feature\Helpers;

use App\Models\User;

class UserHelpers
{
    public static function createTestUser()
    {
        return User::factory()->create();
    }

    public static function createFakeTestUser()
    {
        return User::factory()->make();
    }
}
