<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MakeAdmin extends Command
{
    protected $signature = 'shop:make-admin {email : Email of an existing user}';

    protected $description = 'Give an existing user the admin role (and remove other roles).';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No user found with that email.');
            return self::FAILURE;
        }

        Role::findOrCreate('admin', 'web');
        $user->syncRoles(['admin']);

        $this->info("{$user->email} is now an admin.");

        return self::SUCCESS;
    }
}
