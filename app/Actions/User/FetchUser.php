<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

class FetchUser
{
    public function handle(string $id)
    {
        return User::findOrFail($id);
    }
}
