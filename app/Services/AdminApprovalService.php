<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;

class AdminApprovalService
{
    /**
     * Get users pending approval.
     */
    public function getPendingUsers(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::where('requires_admin_approval', true)
                   ->whereNull('approved_at')
                   ->whereNull('rejected_at')
                   ->where('status', User::STATUS_PENDING_APPROVAL)
                   ->with(['createdBy', 'approvedBy'])
                   ->orderBy('created_at', 'desc')
                   ->paginate($perPage);
    }

    /**
     * Get users that need email verification.
     */
    public function getUsersNeedingVerification(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::where('email_verification_required', true)
                   ->whereNull('email_verified_at')
                   ->with(['createdBy'])
                   ->orderBy('created_at', 'desc')
                   ->paginate($perPage);
    }

    /**
     * Approve user account.
     */
    public function approveUser(User $user, User $adminUser, string $notes = null): array
    {
        try {
            // Check if admin has permission
            if (!$adminUser->isAdmin()) {
                return [
                    'success' => false,
                    'message' => 'Only administrators can approve user accounts.',
                    'error_code' => 'INSUFFICIENT_PERMISSIONS'
                ];
            }

            // Check if user is already approved
            if ($user->isApprovedByAdmin()) {
                return [
                    'success' => false,
                    'message' => 'User account is already approved.',
                    'error_code' => 'ALREADY_APPROVED'
                ];
            }

            // Check if email verification is required and completed
            if ($user->requiresEmailVerification()) {
                return [
                    'success' => false,
                    'message' => 'User must verify their email address before approval.',
                    'error_code' => 'EMAIL_NOT_VERIFIED'
                ];
            }

            // Approve the user
            $user->approveAccount($adminUser, $notes);

            // Send approval notification email
            $this->sendApprovalNotification($user);

            // Log the approval
            \Log::info('User account approved', [
                'user_id' => $user->id,
                'approved_by' => $adminUser->id,
                'notes' => $notes
            ]);

            return [
                'success' => true,
                'message' => 'User account approved successfully.',
                'user' => $user->fresh()
            ];

        } catch (\Exception $e) {
            \Log::error('Failed to approve user account', [
                'user_id' => $user->id,
                'admin_id' => $adminUser->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to approve user account. Please try again.',
                'error_code' => 'APPROVAL_FAILED'
            ];
        }
    }

    /**
     * Reject user account.
     */
    public function rejectUser(User $user, User $adminUser, string $notes = null): array
    {
        try {
            // Check if admin has permission
            if (!$adminUser->isAdmin()) {
                return [
                    'success' => false,
                    'message' => 'Only administrators can reject user accounts.',
                    'error_code' => 'INSUFFICIENT_PERMISSIONS'
                ];
            }

            // Check if user is already rejected
            if ($user->isRejectedByAdmin()) {
                return [
                    'success' => false,
                    'message' => 'User account is already rejected.',
                    'error_code' => 'ALREADY_REJECTED'
                ];
            }

            // Reject the user
            $user->rejectAccount($adminUser, $notes);

            // Send rejection notification email
            $this->sendRejectionNotification($user, $notes);

            // Log the rejection
            \Log::info('User account rejected', [
                'user_id' => $user->id,
                'rejected_by' => $adminUser->id,
                'notes' => $notes
            ]);

            return [
                'success' => true,
                'message' => 'User account rejected successfully.',
                'user' => $user->fresh()
            ];

        } catch (\Exception $e) {
            \Log::error('Failed to reject user account', [
                'user_id' => $user->id,
                'admin_id' => $adminUser->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to reject user account. Please try again.',
                'error_code' => 'REJECTION_FAILED'
            ];
        }
    }

    /**
     * Bulk approve users.
     */
    public function bulkApproveUsers(array $userIds, User $adminUser, string $notes = null): array
    {
        $results = [
            'approved' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user) {
                $results['failed']++;
                $results['errors'][] = "User {$userId} not found";
                continue;
            }

            $result = $this->approveUser($user, $adminUser, $notes);
            if ($result['success']) {
                $results['approved']++;
            } else {
                $results['failed']++;
                $results['errors'][] = "User {$userId}: " . $result['message'];
            }
        }

        return [
            'success' => true,
            'message' => "Approved {$results['approved']} users, {$results['failed']} failed.",
            'results' => $results
        ];
    }

    /**
     * Get approval statistics.
     */
    public function getApprovalStatistics(): array
    {
        return [
            'pending_approval' => User::where('requires_admin_approval', true)
                                     ->whereNull('approved_at')
                                     ->whereNull('rejected_at')
                                     ->count(),
            'pending_verification' => User::where('email_verification_required', true)
                                         ->whereNull('email_verified_at')
                                         ->count(),
            'approved_today' => User::whereNotNull('approved_at')
                                  ->whereDate('approved_at', today())
                                  ->count(),
            'rejected_today' => User::whereNotNull('rejected_at')
                                  ->whereDate('rejected_at', today())
                                  ->count(),
            'total_approved' => User::whereNotNull('approved_at')->count(),
            'total_rejected' => User::whereNotNull('rejected_at')->count(),
        ];
    }

    /**
     * Send approval notification email.
     */
    private function sendApprovalNotification(User $user): void
    {
        try {
            Mail::send('emails.account-approved', [
                'user' => $user,
                'loginUrl' => url('/login'),
            ], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                        ->subject('Account Approved - ArRahnu Auction');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send approval notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send rejection notification email.
     */
    private function sendRejectionNotification(User $user, string $notes = null): void
    {
        try {
            Mail::send('emails.account-rejected', [
                'user' => $user,
                'notes' => $notes,
                'supportEmail' => config('mail.support_email', 'support@arrahnu.com'),
            ], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                        ->subject('Account Application Update - ArRahnu Auction');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if user needs admin approval.
     */
    public function requiresApproval(User $user): bool
    {
        return $user->requiresAdminApproval();
    }

    /**
     * Get approval workflow status for user.
     */
    public function getApprovalStatus(User $user): array
    {
        return [
            'requires_approval' => $user->requires_admin_approval,
            'is_approved' => $user->isApprovedByAdmin(),
            'is_rejected' => $user->isRejectedByAdmin(),
            'approved_at' => $user->approved_at,
            'rejected_at' => $user->rejected_at,
            'approved_by' => $user->approvedBy,
            'approval_notes' => $user->approval_notes,
            'can_login' => $user->canLogin(),
        ];
    }

    /**
     * Reset user approval status (for re-evaluation).
     */
    public function resetApprovalStatus(User $user, User $adminUser): array
    {
        try {
            if (!$adminUser->isAdmin()) {
                return [
                    'success' => false,
                    'message' => 'Only administrators can reset approval status.',
                    'error_code' => 'INSUFFICIENT_PERMISSIONS'
                ];
            }

            $user->update([
                'status' => User::STATUS_PENDING_APPROVAL,
                'approved_at' => null,
                'rejected_at' => null,
                'approval_notes' => null,
            ]);

            \Log::info('User approval status reset', [
                'user_id' => $user->id,
                'reset_by' => $adminUser->id
            ]);

            return [
                'success' => true,
                'message' => 'User approval status reset successfully.',
                'user' => $user->fresh()
            ];

        } catch (\Exception $e) {
            \Log::error('Failed to reset approval status', [
                'user_id' => $user->id,
                'admin_id' => $adminUser->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to reset approval status.',
                'error_code' => 'RESET_FAILED'
            ];
        }
    }
} 