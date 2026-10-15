<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // The two roles the site uses
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('customer', 'web');

        // Admin user (safe to run more than once)
        $admin = User::firstOrNew(['email' => 'admin@gmail.com']);
        $admin->forceFill([
            'name'              => 'Admin',
            'email_verified_at' => now(),
            'password'          => '12345678', // hashed automatically by the model cast
        ])->save();

        $admin->syncRoles(['admin']);

        // Anyone else without a role becomes a customer
        User::whereDoesntHave('roles')->each(fn (User $user) => $user->assignRole('customer'));
    }
}