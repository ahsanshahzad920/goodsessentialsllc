<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('customer', 'web');

        // Everyone without a role becomes a customer.
        // Run `php artisan shop:make-admin you@example.com` BEFORE this seeder.
        User::whereDoesntHave('roles')->each(fn (User $user) => $user->assignRole('customer'));
    }
}
