<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SearchTerm;
use App\Support\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('backend.settings.edit', [
            'settings'    => Settings::all(),
            'topSearches' => SearchTerm::orderByDesc('hits')->take(15)->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'free_delivery_threshold' => ['nullable', 'numeric', 'min:1', 'max:1000000'],
            'announcement_enabled'    => ['nullable', 'boolean'],
        ]);

        Settings::set([
            'free_delivery_threshold' => $data['free_delivery_threshold'] ?? null,
            'announcement_enabled'    => $request->boolean('announcement_enabled') ? '1' : '0',
        ]);

        return redirect()->route('admin.settings.edit')->with('success', 'Settings saved. They are live on the store now.');
    }
}
