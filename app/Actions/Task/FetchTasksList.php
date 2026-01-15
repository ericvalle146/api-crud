<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class FetchTasksList
{
    public function execute(): Collection
    {
        return Task::all();
    }
}
