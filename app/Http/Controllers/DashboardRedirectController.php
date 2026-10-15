<?php

namespace App\Http\Controllers;

use App\Support\Area;
use Illuminate\Http\Request;

/**
 * Keeps route('dashboard') working everywhere (login redirect, header link):
 * admins go to the admin panel, customers go to their account.
 */
class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        return redirect(Area::home($request->user()));
    }
}
