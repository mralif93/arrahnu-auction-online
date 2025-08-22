<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Show the user profile edit form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('public.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Verify current password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password_hash)) {
                return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Update basic info
        $updateData = [
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password_hash'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show user profile (read-only view).
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('public.profile.show', compact('user'));
    }

    /**
     * Update user avatar/profile picture.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Store the avatar file
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Delete old avatar if exists
            if ($user->avatar_path && \Storage::disk('public')->exists($user->avatar_path)) {
                \Storage::disk('public')->delete($user->avatar_path);
            }

            $user->update(['avatar_path' => $avatarPath]);

            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload profile picture.');
    }

    /**
     * Delete the user's account.
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'required',
        ]);

        // Verify password
        if (!Hash::check($request->password, $user->password_hash)) {
            return redirect()->back()->withErrors(['password' => 'The password is incorrect.']);
        }

        // Prevent admin from deleting their own account if they're the only admin
        if ($user->is_admin) {
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Cannot delete account. You are the only administrator.');
            }
        }

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }

    /**
     * Show user settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('public.profile.settings', compact('user'));
    }

    /**
     * Update user preferences/settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
        ]);

        // Update user preferences (you might want to create a separate preferences table)
        $preferences = [
            'email_notifications' => $request->boolean('email_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
            'marketing_emails' => $request->boolean('marketing_emails'),
            'timezone' => $request->timezone,
            'language' => $request->language,
        ];

        // For now, we'll store preferences as JSON in a field
        // In production, consider creating a separate user_preferences table
        $user->update(['preferences' => json_encode($preferences)]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
