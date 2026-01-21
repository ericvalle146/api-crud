<?php

declare(strict_types=1);

namespace App\Actions\User;

class DeleteUser
{
    public function __construct(
        private FetchUser $fetchUser
    ) {}

    public function handle(string $id): void
    {
        $user = $this->fetchUser->handle($id)->delete();
    }
}
