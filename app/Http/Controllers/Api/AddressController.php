<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\AddressService;
use App\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display a listing of the user's addresses.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get query parameters for filtering and ordering
            $options = [
                'order_by' => $request->get('order_by', 'is_primary'),
                'order_direction' => $request->get('order_direction', 'desc'),
                'state' => $request->get('state'),
                'is_primary' => $request->has('is_primary') ? $request->boolean('is_primary') : null,
            ];

            $addresses = $this->addressService->getUserAddresses($user, $options);
            $statistics = $this->addressService->getUserAddressStatistics($user);

            return response()->json([
                'success' => true,
                'message' => 'Addresses retrieved successfully.',
                'data' => [
                    'addresses' => $addresses,
                    'statistics' => $statistics,
                    'primary_address' => $statistics['primary_address']
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
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(ValidationService::getAddressRules(), ValidationService::getCustomMessages());

            $user = Auth::user();
            $address = $this->addressService->createAddress($user, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address created successfully.',
                'data' => [
                    'address' => $address,
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
     * Display the specified address.
     */
    public function show(Address $address)
    {
        // Ensure user can only view their own addresses
        if ($address->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to address.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Address retrieved successfully.',
            'data' => [
                'address' => $address,
                'full_address' => $address->full_address,
                'formatted_addresses' => [
                    'short' => $this->addressService->formatAddress($address, 'short'),
                    'single_line' => $this->addressService->formatAddress($address, 'single_line'),
                    'full' => $this->addressService->formatAddress($address, 'full'),
                ]
            ]
        ]);
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, Address $address)
    {
        // Ensure user can only update their own addresses
        if ($address->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to address.'
            ], 403);
        }

        try {
            $request->validate(ValidationService::getAddressRules(), ValidationService::getCustomMessages());

            $updatedAddress = $this->addressService->updateAddress($address, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully.',
                'data' => [
                    'address' => $updatedAddress,
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
     * Set the specified address as primary.
     */
    public function setPrimary(Address $address)
    {
        // Ensure user can only modify their own addresses
        if ($address->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to address.'
            ], 403);
        }

        try {
            $user = Auth::user();
            $this->addressService->setPrimaryAddress($user, $address);

            return response()->json([
                'success' => true,
                'message' => 'Primary address updated successfully.',
                'data' => [
                    'address' => $address->fresh(),
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
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        // Ensure user can only delete their own addresses
        if ($address->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to address.'
            ], 403);
        }

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
     * Get Malaysian states for dropdown.
     */
    public function getStates()
    {
        $states = $this->addressService->getMalaysianStates();

        return response()->json([
            'success' => true,
            'message' => 'Malaysian states retrieved successfully.',
            'data' => [
                'states' => $states,
                'total' => count($states)
            ]
        ]);
    }

    /**
     * Get address validation rules for frontend.
     */
    public function getValidationRules()
    {
        return response()->json([
            'success' => true,
            'message' => 'Address validation rules retrieved successfully.',
            'data' => [
                'rules' => ValidationService::getAddressRules(),
                'messages' => ValidationService::getCustomMessages()
            ]
        ]);
    }

    /**
     * Get address suggestions based on query.
     */
    public function getSuggestions(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
            'limit' => 'nullable|integer|min:1|max:10'
        ]);

        try {
            $user = Auth::user();
            $suggestions = $this->addressService->getAddressSuggestions(
                $user, 
                $request->query, 
                $request->get('limit', 5)
            );

            return response()->json([
                'success' => true,
                'message' => 'Address suggestions retrieved successfully.',
                'data' => [
                    'suggestions' => $suggestions,
                    'total' => $suggestions->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get address suggestions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export user addresses.
     */
    public function export()
    {
        try {
            $user = Auth::user();
            $addresses = $this->addressService->exportUserAddresses($user);

            return response()->json([
                'success' => true,
                'message' => 'Addresses exported successfully.',
                'data' => [
                    'addresses' => $addresses,
                    'total' => count($addresses),
                    'exported_at' => now()->toISOString()
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
     * Validate postcode format.
     */
    public function validatePostcode(Request $request)
    {
        $request->validate([
            'postcode' => 'required|string',
            'country' => 'nullable|string'
        ]);

        $isValid = $this->addressService->validatePostcode(
            $request->postcode, 
            $request->get('country', 'Malaysia')
        );

        return response()->json([
            'success' => true,
            'message' => 'Postcode validation completed.',
            'data' => [
                'postcode' => $request->postcode,
                'country' => $request->get('country', 'Malaysia'),
                'is_valid' => $isValid
            ]
        ]);
    }

    /**
     * Get user address statistics.
     */
    public function getStatistics()
    {
        try {
            $user = Auth::user();
            $statistics = $this->addressService->getUserAddressStatistics($user);

            return response()->json([
                'success' => true,
                'message' => 'Address statistics retrieved successfully.',
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get address statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 