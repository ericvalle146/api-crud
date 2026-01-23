<?php

declare(strict_types=1);

namespace Database\Seeders\Users;

use App\Enums\User\UserPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CreateUserPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (UserPermissions::all() as $permission) {
            Permission::create([
                'name' => $permission->value,
                'guard_name' => 'sanctum',
            ]);
        }
    }
}
