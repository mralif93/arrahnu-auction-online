<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
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
    public function index()
    {
        $user = Auth::user();
        $addresses = $this->addressService->getUserAddresses($user);
        
        return view('public.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        return view('public.addresses.create');
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $request->validate(ValidationService::getAddressRules(), ValidationService::getCustomMessages());

        $user = Auth::user();
        $address = $this->addressService->createAddress($user, $request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Address created successfully.',
                'address' => $address
            ], 201);
        }

        return redirect()->route('addresses.index')->with('success', 'Address created successfully.');
    }

    /**
     * Display the specified address.
     */
    public function show(Address $address)
    {
        // Ensure user can only view their own addresses
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to address.');
        }

        return view('public.addresses.show', compact('address'));
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        // Ensure user can only edit their own addresses
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to address.');
        }

        return view('public.addresses.edit', compact('address'));
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, Address $address)
    {
        // Ensure user can only update their own addresses
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to address.');
        }

        $request->validate(ValidationService::getAddressRules(), ValidationService::getCustomMessages());

        $updatedAddress = $this->addressService->updateAddress($address, $request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully.',
                'address' => $updatedAddress
            ]);
        }

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully.');
    }

    /**
     * Set the specified address as primary.
     */
    public function setPrimary(Address $address)
    {
        // Ensure user can only modify their own addresses
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to address.');
        }

        $user = Auth::user();
        $this->addressService->setPrimaryAddress($user, $address);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Primary address updated successfully.',
                'address' => $address->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'Primary address updated successfully.');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        // Ensure user can only delete their own addresses
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to address.');
        }

        $result = $this->addressService->deleteAddress($address);

        if (!$result['success']) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            return redirect()->back()->with('error', $result['message']);
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return redirect()->route('addresses.index')->with('success', $result['message']);
    }

    /**
     * Get Malaysian states for dropdown.
     */
    public function getStates()
    {
        $states = $this->addressService->getMalaysianStates();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'states' => $states
            ]);
        }

        return $states;
    }
} 