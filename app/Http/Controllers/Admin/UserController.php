<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    /**
     * Update the specified user.
     */
    public function update(User $user, Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
        ]);

        // Prevent admin from removing their own admin status
        if ($user->id === Auth::id() && $user->isAdmin() && !$request->has('is_admin')) {
            return redirect()->back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->back()->with('success', "User {$user->full_name} has been updated successfully.");
    }

    /**
     * Toggle admin status for the specified user.
     */
    public function toggleAdmin(User $user)
    {
        // Prevent admin from removing their own admin status
        if ($user->id === Auth::id() && $user->isAdmin()) {
            return redirect()->back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $action = $user->is_admin ? 'granted' : 'removed';
        $message = "Admin privileges {$action} for {$user->full_name}.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting their own account
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->full_name;
        $user->delete();

        return redirect()->back()->with('success', "User {$userName} has been deleted.");
    }
}
