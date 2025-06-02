<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends BaseAdminController
{
    protected string $modelClass = User::class;
    protected string $routePrefix = 'admin.users';
    protected string $entityName = 'User';

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
        // Calculate statistics from all users
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();
        $regularUsers = User::where('is_admin', false)->count();
        $activeUsers = User::where('status', User::STATUS_ACTIVE)->count();
        $pendingUsers = User::where('status', User::STATUS_PENDING_APPROVAL)->count();
        $verifiedUsers = User::where('is_email_verified', true)->count();

        // Get paginated users
        $users = User::with(['creator', 'approvedBy', 'primaryAddress'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'adminUsers',
            'regularUsers',
            'activeUsers',
            'pendingUsers',
            'verifiedUsers'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = User::getRoles();
        $statuses = User::getStatuses();

        return view('admin.users.create', compact('roles', 'statuses'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        return $this->handleAction(function () use ($request) {
            $validationRules = ValidationService::getUserRules();
            $request->validate($validationRules, ValidationService::getCustomMessages());

            $status = $request->submit_action === 'submit_for_approval' ? 'pending_approval' : 'draft';

            $user = User::create([
                'id' => Str::uuid(),
                'username' => $request->username,
                'email' => $request->email,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'role' => $request->role,
                'password_hash' => Hash::make($request->password),
                'is_admin' => $request->boolean('is_admin'),
                'is_staff' => $request->boolean('is_staff'),
                'status' => $status,
                'created_by_user_id' => Auth::id(),
            ]);

            $message = $status === 'pending_approval'
                ? "User {$user->full_name} has been submitted for approval."
                : "User {$user->full_name} has been saved as draft.";

            if ($request->expectsJson()) {
                return $user->load(['creator', 'approvedBy']);
            }

            return $this->redirectToIndex('admin.users.index', $message);
        }, $request);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['creator', 'approvedBy', 'primaryAddress', 'addresses']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = User::getRoles();
        $statuses = User::getStatuses();

        return view('admin.users.edit', compact('user', 'roles', 'statuses'));
    }

    /**
     * Update the specified user.
     */
    public function update(User $user, Request $request)
    {
        return $this->handleAction(function () use ($user, $request) {
            $validationRules = ValidationService::getUserRules($user->id);
            $request->validate($validationRules, ValidationService::getCustomMessages());

            // Prevent admin from removing their own admin status
            if ($user->id === Auth::id() && $user->isAdmin() && !$request->boolean('is_admin')) {
                throw new \Exception('You cannot remove your own admin privileges.');
            }

            $updateData = [
                'username' => $request->username,
                'email' => $request->email,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'role' => $request->role,
                'is_admin' => $request->boolean('is_admin'),
                'is_staff' => $request->boolean('is_staff'),
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password_hash'] = Hash::make($request->password);
            }

            // Handle status updates based on submit action
            if ($request->has('submit_action')) {
                if ($request->submit_action === 'draft') {
                    $updateData['status'] = User::STATUS_DRAFT;
                } elseif ($request->submit_action === 'submit_for_approval' && $user->status === User::STATUS_DRAFT) {
                    $updateData['status'] = User::STATUS_PENDING_APPROVAL;
                }
            }

            $user->update($updateData);

            $message = isset($updateData['status']) && $updateData['status'] === User::STATUS_PENDING_APPROVAL
                ? "User {$user->full_name} has been submitted for approval."
                : "User {$user->full_name} has been updated successfully.";

            if ($request->expectsJson()) {
                return $user->fresh(['creator', 'approvedBy']);
            }

            return $this->redirectToIndex('admin.users.index', $message);
        }, $request);
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

    /**
     * Approve a user (Checker function).
     */
    public function approve(User $user, Request $request)
    {
        return $this->approveEntity($request, $user);
    }

    /**
     * Reject a user (Checker function).
     */
    public function reject(User $user, Request $request)
    {
        return $this->rejectEntity($request, $user);
    }

    /**
     * Get entity display name for messages.
     */
    protected function getEntityDisplayName($entity): string
    {
        return $entity->full_name ?? $entity->username;
    }

    /**
     * Get default relationships to load.
     */
    protected function getDefaultRelationships(): array
    {
        return ['creator', 'approvedBy', 'addresses'];
    }
}
