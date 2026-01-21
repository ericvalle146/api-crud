<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Models\Task;

class FetchTask
{
    public function handle($id)
    {
        return Task::findOrFail($id);
    }
}
