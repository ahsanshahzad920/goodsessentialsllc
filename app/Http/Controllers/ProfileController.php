<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    public function showProfile()
    {
        // Get the authenticated user
        $user = Auth::user();

        return view('backend.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        // Validate input data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // Ignore current user's email
            'phone' => 'nullable|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        // Update user data
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'] ?? $user->phone;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image

            $user->image = $request->file('image')->store('users', 'public');
        }

        $user->save(); // Save the user profile

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function edit()
    {
        return view('profile', [
            'user' => auth()->user(),
        ]);
    }
    public function updateProfileImage(Request $request)
    {
        $user = auth()->user();

        if ($request->boolean('remove_image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
                $user->image = null;
                $user->save();
            }

            return redirect()
                ->route('profile')
                ->with('success', 'Profile picture removed successfully.');
        }

        $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $path = $request->file('image')->store('profiles', 'public');

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->image = $path;
        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profile picture updated successfully.');
    }

    public function updateUserProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            // Profile information
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'phone' => ['nullable', 'string', 'max:30'],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // Password fields are optional unless a new password is entered
            'current_password' => [
                'nullable',
                'required_with:password',
                'current_password',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'required_with:current_password',
            ],
        ]);

        // Update profile information
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        // Update profile image only when a new one is uploaded
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $user->image = $request->file('image')
                ->store('profile-images', 'public');
        }

        // Update password only when a new password is provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('user.profile.show')
            ->with('success', 'Your profile has been updated successfully.');
    }
}
