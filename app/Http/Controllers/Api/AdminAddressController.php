<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use App\Services\AddressService;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class AdminAddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display a listing of all addresses with advanced filtering.
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'state' => $request->get('state'),
                'is_primary' => $request->has('is_primary') ? $request->boolean('is_primary') : null,
                'user_id' => $request->get('user_id'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'order_by' => $request->get('order_by', 'created_at'),
                'order_direction' => $request->get('order_direction', 'desc'),
            ];

            $addresses = $this->addressService->searchAddresses($filters);
            $statistics = $this->addressService->getGlobalStatistics();

            // Paginate results if requested
            if ($request->has('per_page')) {
                $perPage = min($request->get('per_page', 15), 100);
                $page = $request->get('page', 1);
                $total = $addresses->count();
                $addresses = $addresses->slice(($page - 1) * $perPage, $perPage)->values();
                
                $pagination = [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage),
                    'from' => (($page - 1) * $perPage) + 1,
                    'to' => min($page * $perPage, $total),
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Addresses retrieved successfully.',
                'data' => [
                    'addresses' => $addresses,
                    'statistics' => $statistics,
                    'pagination' => $pagination ?? null,
                    'filters_applied' => array_filter($filters)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve addresses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created address for any user.
     */
    public function store(Request $request)
    {
        try {
            $rules = ValidationService::getAddressRules();
            $rules['user_id'] = 'required|exists:users,id';
            
            $request->validate($rules, ValidationService::getCustomMessages());

            $user = User::findOrFail($request->user_id);
            $address = $this->addressService->createAddress($user, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address created successfully.',
                'data' => [
                    'address' => $address->load('user'),
                    'is_primary' => $address->is_primary
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified address with full details.
     */
    public function show(Address $address)
    {
        try {
            $address->load('user');
            
            // Get user's other addresses
            $otherAddresses = $address->user->addresses()
                                          ->where('id', '!=', $address->id)
                                          ->get();

            return response()->json([
                'success' => true,
                'message' => 'Address retrieved successfully.',
                'data' => [
                    'address' => $address,
                    'user' => $address->user,
                    'other_addresses' => $otherAddresses,
                    'formatted_addresses' => [
                        'short' => $this->addressService->formatAddress($address, 'short'),
                        'single_line' => $this->addressService->formatAddress($address, 'single_line'),
                        'full' => $this->addressService->formatAddress($address, 'full'),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, Address $address)
    {
        try {
            $rules = ValidationService::getAddressRules();
            $rules['user_id'] = 'required|exists:users,id';
            
            $request->validate($rules, ValidationService::getCustomMessages());

            // Handle user transfer if needed
            if ($request->user_id != $address->user_id) {
                $newUser = User::findOrFail($request->user_id);
                $address->update(['user_id' => $newUser->id]);
                $address = $address->fresh();
            }

            $updatedAddress = $this->addressService->updateAddress($address, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully.',
                'data' => [
                    'address' => $updatedAddress->load('user'),
                    'is_primary' => $updatedAddress->is_primary
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        try {
            $result = $this->addressService->deleteAddress($address);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'error_code' => $result['error_code'] ?? null
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'deleted_address_id' => $result['deleted_address_id'],
                    'new_primary_address_id' => $result['new_primary_address_id']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk operations on addresses.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,set_primary,unset_primary',
            'address_ids' => 'required|array|min:1',
            'address_ids.*' => 'exists:addresses,id',
            'user_id' => 'required_if:action,set_primary|exists:users,id'
        ]);

        try {
            $result = $this->addressService->bulkOperation(
                $request->address_ids,
                $request->action,
                ['user_id' => $request->user_id]
            );

            return response()->json([
                'success' => true,
                'message' => "Bulk {$request->action} operation completed.",
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set address as primary.
     */
    public function setPrimary(Address $address)
    {
        try {
            $this->addressService->setPrimaryAddress($address->user, $address);

            return response()->json([
                'success' => true,
                'message' => 'Primary address updated successfully.',
                'data' => [
                    'address' => $address->fresh()->load('user'),
                    'primary_address_id' => $address->id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary address.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get global address statistics.
     */
    public function getStatistics()
    {
        try {
            $statistics = $this->addressService->getGlobalStatistics();

            return response()->json([
                'success' => true,
                'message' => 'Global address statistics retrieved successfully.',
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's address information.
     */
    public function getUserAddresses(User $user)
    {
        try {
            $addresses = $this->addressService->getUserAddresses($user);
            $statistics = $this->addressService->getUserAddressStatistics($user);

            return response()->json([
                'success' => true,
                'message' => 'User addresses retrieved successfully.',
                'data' => [
                    'user' => $user,
                    'addresses' => $addresses,
                    'statistics' => $statistics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user addresses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export all addresses to array.
     */
    public function export(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'state' => $request->get('state'),
                'is_primary' => $request->has('is_primary') ? $request->boolean('is_primary') : null,
                'user_id' => $request->get('user_id'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
            ];

            $addresses = $this->addressService->searchAddresses($filters);
            
            $exportData = $addresses->map(function ($address) {
                return [
                    'id' => $address->id,
                    'user_id' => $address->user_id,
                    'user_name' => $address->user->full_name,
                    'user_email' => $address->user->email,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postcode' => $address->postcode,
                    'country' => $address->country,
                    'is_primary' => $address->is_primary,
                    'full_address' => $address->full_address,
                    'created_at' => $address->created_at->toISOString(),
                    'updated_at' => $address->updated_at->toISOString(),
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Addresses exported successfully.',
                'data' => [
                    'addresses' => $exportData,
                    'total' => count($exportData),
                    'exported_at' => now()->toISOString(),
                    'filters_applied' => array_filter($filters)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export addresses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get filter options for admin interface.
     */
    public function getFilterOptions()
    {
        try {
            $states = $this->addressService->getMalaysianStates();
            $users = User::select('id', 'full_name', 'username', 'email')
                        ->whereHas('addresses')
                        ->orderBy('full_name')
                        ->get();

            return response()->json([
                'success' => true,
                'message' => 'Filter options retrieved successfully.',
                'data' => [
                    'states' => $states,
                    'users' => $users,
                    'primary_options' => [
                        ['value' => true, 'label' => 'Primary'],
                        ['value' => false, 'label' => 'Secondary']
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get filter options.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 