<?php

namespace App\Http\Middleware;

use App\Support\Area;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage: ->middleware('area:admin')  or  ->middleware('area:customer')
 */
class EnsureUserArea
{
    public function handle(Request $request, Closure $next, string $area): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        Area::heal($user);

        if ($user->hasRole($area)) {
            return $next($request);
        }

        // Wrong area: send them to their own home instead of showing the page.
        return redirect(Area::home($user))->with('error', 'You do not have access to that page.');
    }
}
