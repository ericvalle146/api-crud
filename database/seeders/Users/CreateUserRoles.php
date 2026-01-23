<?php

declare(strict_types=1);

namespace Database\Seeders\Users;

use App\Enums\User\UserPermissions;
use App\Enums\User\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CreateUserRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (UserRoles::all() as $roles) {
            Role::create([
                'name' => $roles->value,
                'guard_name' => 'sanctum',
            ]);
        }

        $admin = Role::where('name', 'admin')->where('guard_name', 'sanctum')->first();

        foreach (UserPermissions::all() as $permission) {
            $admin->givePermissionTo($permission->value);
        }
    }
}
