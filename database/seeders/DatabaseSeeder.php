<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Users\CreateUserPermissions;
use Database\Seeders\Users\CreateUserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(CreateUserPermissions::class);
        $this->call(CreateUserRoles::class);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user->assignRole('admin');
    }
}
