<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Get user profile with complete information.
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            $user->load(['addresses' => function($q) {
                $q->orderBy('is_primary', 'desc')->orderBy('created_at', 'desc');
            }]);

            // Get user statistics
            $statistics = [
                'total_bids' => $user->bids()->count(),
                'active_bids' => $user->bids()->whereIn('status', ['active', 'winning'])->count(),
                'successful_bids' => $user->bids()->where('status', 'successful')->count(),
                'total_spent' => $user->bids()->where('status', 'successful')->sum('bid_amount_rm'),
                'account_age_days' => $user->created_at->diffInDays(now()),
                'last_bid_date' => $user->bids()->max('bid_time'),
            ];

            // Get primary address
            $primaryAddress = $user->addresses->where('is_primary', true)->first();

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'date_of_birth' => $user->date_of_birth,
                        'gender' => $user->gender,
                        'nationality' => $user->nationality,
                        'occupation' => $user->occupation,
                        'role' => $user->role,
                        'status' => $user->status,
                        'avatar_url' => $user->avatar_url,
                        'email_verified_at' => $user->email_verified_at,
                        'phone_verified_at' => $user->phone_verified_at,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ],
                    'addresses' => $user->addresses,
                    'primary_address' => $primaryAddress,
                    'statistics' => $statistics,
                    'profile_completion' => $this->calculateProfileCompletion($user)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'username' => [
                'sometimes',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone_number' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id)
            ],
            'date_of_birth' => 'sometimes|date|before:today',
            'gender' => 'sometimes|in:male,female,other',
            'nationality' => 'sometimes|string|max:100',
            'occupation' => 'sometimes|string|max:100',
        ]);

        try {
            $updateData = $request->only([
                'full_name', 'username', 'email', 'phone_number',
                'date_of_birth', 'gender', 'nationality', 'occupation'
            ]);

            // Track what fields were updated
            $updatedFields = [];
            foreach ($updateData as $field => $value) {
                if ($user->$field !== $value) {
                    $updatedFields[] = $field;
                }
            }

            $user->update($updateData);

            // If email was updated, reset email verification
            if (in_array('email', $updatedFields)) {
                $user->update(['email_verified_at' => null]);
            }

            // If phone was updated, reset phone verification
            if (in_array('phone_number', $updatedFields)) {
                $user->update(['phone_verified_at' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'data' => [
                    'user' => $user->fresh(),
                    'updated_fields' => $updatedFields,
                    'profile_completion' => $this->calculateProfileCompletion($user->fresh())
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.',
                    'error_code' => 'INVALID_CURRENT_PASSWORD'
                ], 400);
            }

            // Update password
            $user->update([
                'password_hash' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload and update user avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        try {
            $user = Auth::user();

            // Delete old avatar if exists
            if ($user->avatar_url) {
                $oldPath = str_replace('/storage/', '', $user->avatar_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $avatarUrl = '/storage/' . $avatarPath;

            $user->update(['avatar_url' => $avatarUrl]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully.',
                'data' => [
                    'avatar_url' => $avatarUrl,
                    'user' => $user->fresh()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update avatar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove user avatar.
     */
    public function removeAvatar()
    {
        try {
            $user = Auth::user();

            if ($user->avatar_url) {
                $oldPath = str_replace('/storage/', '', $user->avatar_url);
                Storage::disk('public')->delete($oldPath);
                
                $user->update(['avatar_url' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Avatar removed successfully.',
                'data' => [
                    'user' => $user->fresh()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove avatar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user preferences and settings.
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_notifications' => 'sometimes|boolean',
            'sms_notifications' => 'sometimes|boolean',
            'push_notifications' => 'sometimes|boolean',
            'bid_notifications' => 'sometimes|boolean',
            'auction_reminders' => 'sometimes|boolean',
            'marketing_emails' => 'sometimes|boolean',
            'language' => 'sometimes|string|in:en,ms',
            'timezone' => 'sometimes|string|max:50',
            'currency_preference' => 'sometimes|string|in:MYR,USD',
        ]);

        try {
            $user = Auth::user();
            
            // Get current preferences or create default
            $preferences = $user->preferences ?? [
                'email_notifications' => true,
                'sms_notifications' => true,
                'push_notifications' => true,
                'bid_notifications' => true,
                'auction_reminders' => true,
                'marketing_emails' => false,
                'language' => 'en',
                'timezone' => 'Asia/Kuala_Lumpur',
                'currency_preference' => 'MYR',
            ];

            // Update preferences with provided values
            $updatedPreferences = array_merge($preferences, $request->only([
                'email_notifications', 'sms_notifications', 'push_notifications',
                'bid_notifications', 'auction_reminders', 'marketing_emails',
                'language', 'timezone', 'currency_preference'
            ]));

            $user->update(['preferences' => $updatedPreferences]);

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully.',
                'data' => [
                    'preferences' => $updatedPreferences
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's bidding activity summary.
     */
    public function biddingActivity(Request $request)
    {
        try {
            $user = Auth::user();
            $period = $request->get('period', '30'); // days

            $startDate = now()->subDays($period);

            // Get bidding statistics
            $stats = [
                'total_bids' => $user->bids()->where('bid_time', '>=', $startDate)->count(),
                'unique_auctions' => $user->bids()
                    ->where('bid_time', '>=', $startDate)
                    ->distinct('collateral_id')
                    ->count('collateral_id'),
                'total_amount_bid' => $user->bids()->where('bid_time', '>=', $startDate)->sum('bid_amount_rm'),
                'average_bid' => $user->bids()->where('bid_time', '>=', $startDate)->avg('bid_amount_rm'),
                'winning_bids' => $user->bids()
                    ->where('bid_time', '>=', $startDate)
                    ->where('status', 'winning')
                    ->count(),
                'successful_bids' => $user->bids()
                    ->where('bid_time', '>=', $startDate)
                    ->where('status', 'successful')
                    ->count(),
            ];

            // Get daily activity
            $dailyActivity = $user->bids()
                ->selectRaw('DATE(bid_time) as date, COUNT(*) as bid_count, SUM(bid_amount_rm) as total_amount')
                ->where('bid_time', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Get recent bids
            $recentBids = $user->bids()
                ->with(['collateral.auction', 'collateral.images'])
                ->where('bid_time', '>=', $startDate)
                ->orderBy('bid_time', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Bidding activity retrieved successfully.',
                'data' => [
                    'period' => $period . ' days',
                    'statistics' => $stats,
                    'daily_activity' => $dailyActivity,
                    'recent_bids' => $recentBids
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bidding activity.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's watchlist (favorite auctions).
     */
    public function watchlist()
    {
        try {
            $user = Auth::user();
            
            // This would require a watchlist/favorites table
            // For now, return user's recent bid collaterals as watchlist
            $watchlist = $user->bids()
                ->with(['collateral.auction', 'collateral.images'])
                ->whereHas('collateral.auction', function($q) {
                    $q->where('status', 'active');
                })
                ->distinct('collateral_id')
                ->orderBy('bid_time', 'desc')
                ->limit(20)
                ->get()
                ->map(function($bid) {
                    return [
                        'collateral' => $bid->collateral,
                        'user_highest_bid' => $bid->bid_amount_rm,
                        'current_highest_bid' => $bid->collateral->current_highest_bid_rm,
                        'is_winning' => $bid->collateral->highest_bidder_user_id === Auth::id(),
                        'time_remaining' => $bid->collateral->auction->end_datetime->diffInSeconds(now()),
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Watchlist retrieved successfully.',
                'data' => [
                    'watchlist' => $watchlist,
                    'total_items' => $watchlist->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve watchlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user account.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|string|in:DELETE_MY_ACCOUNT',
        ]);

        try {
            $user = Auth::user();

            // Verify password
            if (!Hash::check($request->password, $user->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password is incorrect.',
                    'error_code' => 'INVALID_PASSWORD'
                ], 400);
            }

            // Check for active bids
            $activeBids = $user->bids()->whereIn('status', ['active', 'winning'])->count();
            if ($activeBids > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete account with active bids. Please wait for auctions to complete.',
                    'error_code' => 'HAS_ACTIVE_BIDS',
                    'data' => ['active_bids_count' => $activeBids]
                ], 400);
            }

            // Delete avatar if exists
            if ($user->avatar_url) {
                $avatarPath = str_replace('/storage/', '', $user->avatar_url);
                Storage::disk('public')->delete($avatarPath);
            }

            // Soft delete or anonymize user data
            $user->update([
                'status' => 'deleted',
                'email' => 'deleted_' . $user->id . '@deleted.com',
                'username' => 'deleted_' . $user->id,
                'full_name' => 'Deleted User',
                'phone_number' => null,
                'avatar_url' => null,
                'deleted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateProfileCompletion(User $user): array
    {
        $fields = [
            'full_name' => !empty($user->full_name),
            'email' => !empty($user->email),
            'phone_number' => !empty($user->phone_number),
            'date_of_birth' => !empty($user->date_of_birth),
            'gender' => !empty($user->gender),
            'nationality' => !empty($user->nationality),
            'occupation' => !empty($user->occupation),
            'avatar' => !empty($user->avatar_url),
            'email_verified' => !empty($user->email_verified_at),
            'phone_verified' => !empty($user->phone_verified_at),
            'primary_address' => $user->addresses()->where('is_primary', true)->exists(),
        ];

        $completed = array_sum($fields);
        $total = count($fields);
        $percentage = round(($completed / $total) * 100);

        return [
            'percentage' => $percentage,
            'completed_fields' => $completed,
            'total_fields' => $total,
            'missing_fields' => array_keys(array_filter($fields, function($value) {
                return !$value;
            }))
        ];
    }
} 