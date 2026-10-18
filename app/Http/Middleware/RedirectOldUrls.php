<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RedirectOldUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('get')) {
            $redirect = DB::table('redirects')->where('old_path', $request->path())->first();

            if ($redirect) {
                return redirect('/' . ltrim($redirect->new_path, '/'), $redirect->status_code);
            }
        }

        return $next($request);
    }
}
