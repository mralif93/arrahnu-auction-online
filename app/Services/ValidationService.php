<?php

namespace App\Services;

use Illuminate\Validation\Rule;

class ValidationService
{
    /**
     * Get user validation rules.
     */
    public static function getUserRules(?string $userId = null): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($userId)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('users')->ignore($userId)
            ],
            'full_name' => 'required|string|max:100',
            'phone_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($userId)
            ],
            'role' => 'required|in:admin,maker,checker,bidder',
            'password' => $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_staff' => 'boolean',
            'submit_action' => 'required|in:draft,submit_for_approval',
        ];
    }

    /**
     * Get branch validation rules.
     */
    public static function getBranchRules(?string $branchId = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('branches')->ignore($branchId)
            ],
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'submit_action' => 'required|in:draft,submit_for_approval',
        ];
    }

    /**
     * Get account validation rules.
     */
    public static function getAccountRules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'account_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'submit_action' => 'sometimes|in:draft,submit_for_approval',
        ];
    }

    /**
     * Get auction validation rules.
     */
    public static function getAuctionRules(): array
    {
        return [
            'auction_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'submit_action' => 'required|in:draft,submit_for_approval',
        ];
    }

    /**
     * Get collateral validation rules.
     */
    public static function getCollateralRules(): array
    {
        return [
            'account_id' => 'required|exists:accounts,id',
            'auction_id' => 'required|exists:auctions,id',
            'item_type' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'weight_grams' => 'nullable|numeric|min:0',
            'purity' => 'nullable|string|max:20',
            'estimated_value_rm' => 'nullable|numeric|min:0',
            'starting_bid_rm' => 'required|numeric|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'submit_action' => 'required|in:draft,submit_for_approval',
        ];
    }

    /**
     * Get address validation rules.
     */
    public static function getAddressRules(): array
    {
        return [
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ];
    }

    /**
     * Get workflow validation rules.
     */
    public static function getWorkflowRules(): array
    {
        return [
            'submit_action' => 'required|in:draft,submit_for_approval,update',
        ];
    }

    /**
     * Get status update validation rules.
     */
    public static function getStatusUpdateRules(): array
    {
        return [
            'status' => 'required|in:active,inactive,pending_approval,rejected,draft',
        ];
    }

    /**
     * Get bulk action validation rules.
     */
    public static function getBulkActionRules(string $entityType): array
    {
        $actions = match ($entityType) {
            'users' => 'approve,reject,delete,activate,deactivate',
            'branches' => 'approve,reject,delete,activate,deactivate',
            'accounts' => 'approve,reject,delete,activate,deactivate',
            'auctions' => 'approve,reject,delete,cancel,schedule',
            'collaterals' => 'approve,reject,delete,activate,deactivate',
            default => 'approve,reject,delete',
        };

        return [
            'action' => "required|in:{$actions}",
            'entity_ids' => 'required|array|min:1',
            'entity_ids.*' => "exists:{$entityType},id",
        ];
    }

    /**
     * Get image upload validation rules.
     */
    public static function getImageUploadRules(int $maxFiles = 5, int $maxSize = 2048): array
    {
        return [
            'images' => "nullable|array|max:{$maxFiles}",
            'images.*' => "image|mimes:jpeg,png,jpg,gif|max:{$maxSize}",
        ];
    }

    /**
     * Get pagination validation rules.
     */
    public static function getPaginationRules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort_by' => 'nullable|string',
            'sort_direction' => 'nullable|in:asc,desc',
        ];
    }

    /**
     * Get search validation rules.
     */
    public static function getSearchRules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public static function getCustomMessages(): array
    {
        return [
            'name.unique' => 'A record with this name already exists.',
            'email.unique' => 'This email address is already registered.',
            'username.unique' => 'This username is already taken.',
            'phone_number.unique' => 'This phone number is already registered.',
            'end_datetime.after' => 'The end date must be after the start date.',
            'start_datetime.after' => 'The start date must be in the future.',
            'images.max' => 'You can upload a maximum of :max images.',
            'images.*.max' => 'Each image must not exceed :max KB.',
            'submit_action.required' => 'Please select an action (Save as Draft or Submit for Approval).',
        ];
    }

    /**
     * Get validation rules for specific update action.
     */
    public static function getUpdateActionRules(string $currentStatus): array
    {
        $allowedActions = match ($currentStatus) {
            'draft' => 'draft,submit_for_approval',
            'rejected' => 'draft,submit_for_approval',
            'pending_approval' => 'update',
            default => 'update',
        };

        return [
            'submit_action' => "sometimes|in:{$allowedActions}",
        ];
    }
}
