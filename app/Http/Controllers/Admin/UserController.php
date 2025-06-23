<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\ValidationService;
use App\Services\EmailVerificationService;
use App\Services\AdminApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends BaseAdminController
{
    protected string $modelClass = User::class;
    protected string $routePrefix = 'admin.users';
    protected string $entityName = 'User';

    protected $emailVerificationService;
    protected $adminApprovalService;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        EmailVerificationService $emailVerificationService,
        AdminApprovalService $adminApprovalService
    ) {
        $this->emailVerificationService = $emailVerificationService;
        $this->adminApprovalService = $adminApprovalService;
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
     * Manually verify user's email address.
     */
    public function verifyEmail(User $user, Request $request)
    {
        try {
            // Check if user email is already verified
            if ($user->isEmailVerified()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email is already verified.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Email is already verified.');
            }

            // Mark email as verified by admin
            $user->markEmailAsVerified();

            // Log the action
            \Log::info('Email verified by admin', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'admin_id' => Auth::id(),
                'admin_email' => Auth::user()->email,
            ]);

            $message = "Email verified successfully for {$user->full_name}.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'user' => $user->fresh(),
                        'email_verified_at' => $user->email_verified_at,
                        'can_login' => $user->canLogin(),
                    ]
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Failed to verify email by admin', [
                'user_id' => $user->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            $message = 'Failed to verify email. Please try again.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Send verification email to user.
     */
    public function sendVerificationEmail(User $user, Request $request)
    {
        try {
            // Check if user email is already verified
            if ($user->isEmailVerified()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email is already verified.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Email is already verified.');
            }

            // Send verification email
            $result = $this->emailVerificationService->sendVerificationEmail($user);

            if ($result) {
                // Log the action
                \Log::info('Verification email sent by admin', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'admin_id' => Auth::id(),
                    'admin_email' => Auth::user()->email,
                ]);

                $message = "Verification email sent successfully to {$user->email}.";

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'data' => [
                            'verification_sent_at' => $user->fresh()->email_verification_sent_at,
                            'verification_expires_at' => $user->fresh()->verification_token_expires_at,
                        ]
                    ]);
                }

                return redirect()->back()->with('success', $message);
            } else {
                $message = 'Failed to send verification email. Please check email configuration.';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 500);
                }

                return redirect()->back()->with('error', $message);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send verification email by admin', [
                'user_id' => $user->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            $message = 'Failed to send verification email. Please try again.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Reset email verification status (unverify email).
     */
    public function resetEmailVerification(User $user, Request $request)
    {
        try {
            // Check if user email is not verified
            if (!$user->isEmailVerified()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email is not verified yet.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Email is not verified yet.');
            }

            // Reset email verification
            $user->update([
                'email_verified_at' => null,
                'is_email_verified' => false,
                'email_verification_token' => null,
                'verification_token_expires_at' => null,
                'failed_verification_attempts' => 0,
            ]);

            // Log the action
            \Log::info('Email verification reset by admin', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'admin_id' => Auth::id(),
                'admin_email' => Auth::user()->email,
            ]);

            $message = "Email verification reset for {$user->full_name}. User will need to verify email again.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'user' => $user->fresh(),
                        'requires_verification' => $user->fresh()->requiresEmailVerification(),
                        'can_login' => $user->fresh()->canLogin(),
                    ]
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Failed to reset email verification by admin', [
                'user_id' => $user->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            $message = 'Failed to reset email verification. Please try again.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Get user verification and approval status.
     */
    public function getVerificationStatus(User $user, Request $request)
    {
        $verificationStatus = $this->emailVerificationService->getVerificationStatus($user);
        $approvalStatus = $this->adminApprovalService->getApprovalStatus($user);

        $data = [
            'user_id' => $user->id,
            'email' => $user->email,
            'full_name' => $user->full_name,
            'verification_status' => $verificationStatus,
            'approval_status' => $approvalStatus,
            'can_login' => $user->canLogin(),
            'login_tracking' => [
                'last_login_at' => $user->last_login_at,
                'last_login_source' => $user->last_login_source,
                'last_api_login_at' => $user->last_api_login_at,
                'last_web_login_at' => $user->last_web_login_at,
                'login_attempts' => $user->login_attempts,
                'account_locked_until' => $user->account_locked_until,
            ]
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('admin.users.verification-status', compact('user', 'data'));
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
