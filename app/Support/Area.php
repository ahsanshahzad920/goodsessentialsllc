<?php

namespace App\Support;

use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * Decides which "area" a user belongs to: admin or customer.
 */
final class Area
{
    /** Users with no role at all (older accounts, normal sign-ups) are customers. */
    public static function heal(User $user): void
    {
        if ($user->roles()->doesntExist()) {
            Role::findOrCreate('customer', 'web');
            $user->assignRole('customer');
            $user->unsetRelation('roles');
        }
    }

    /** Where this user should land. */
    public static function home(User $user): string
    {
        self::heal($user);

        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }

        if ($user->hasRole('customer')) {
            return route('account.orders.index');
        }

        return route('index'); // any other role: public home (also prevents redirect loops)
    }
}
