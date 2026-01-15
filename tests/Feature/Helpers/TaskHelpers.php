<?php

declare(strict_types=1);

namespace Tests\Feature\Helpers;

use App\Models\Task;

class TaskHelpers
{
    public static function createTestTask(): Task
    {

        return Task::factory()->create();

    }

    public static function createFakeTestTask(): Task
    {

        return Task::factory()->make();

    }
}
