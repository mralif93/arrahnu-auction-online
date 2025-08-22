<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use App\Services\AddressService;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display a listing of all addresses.
     */
    public function index(Request $request)
    {
        $query = Address::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('address_line_1', 'like', "%{$search}%")
                  ->orWhere('address_line_2', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhere('postcode', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by state
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Filter by primary status
        if ($request->filled('is_primary')) {
            $query->where('is_primary', $request->is_primary === '1');
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $addresses = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $states = Address::distinct()->pluck('state')->sort();
        $users = User::select('id', 'full_name', 'username', 'email')
                    ->orderBy('full_name')
                    ->get();

        // Statistics
        $stats = [
            'total' => Address::count(),
            'primary' => Address::where('is_primary', true)->count(),
            'by_state' => Address::selectRaw('state, COUNT(*) as count')
                                ->groupBy('state')
                                ->orderBy('count', 'desc')
                                ->limit(5)
                                ->get(),
            'recent' => Address::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.addresses.index', compact('addresses', 'states', 'users', 'stats'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        $users = User::select('id', 'full_name', 'username', 'email')
                    ->orderBy('full_name')
                    ->get();

        return view('admin.addresses.create', compact('users'));
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $rules = ValidationService::getAddressRules();
        $rules['user_id'] = 'required|exists:users,id';

        $validated = $request->validate($rules);

        // Handle primary address logic
        if ($validated['is_primary'] ?? false) {
            // Unset other primary addresses for this user
            Address::where('user_id', $validated['user_id'])
                   ->where('is_primary', true)
                   ->update(['is_primary' => false]);
        } else {
            // If this is the user's first address, make it primary
            $userAddressCount = Address::where('user_id', $validated['user_id'])->count();
            if ($userAddressCount === 0) {
                $validated['is_primary'] = true;
            }
        }

        $address = Address::create($validated);

        return redirect()->route('admin.addresses.index')
                        ->with('success', 'Address created successfully.');
    }

    /**
     * Display the specified address.
     */
    public function show(Address $address)
    {
        $address->load('user');
        
        // Get user's other addresses
        $otherAddresses = Address::where('user_id', $address->user_id)
                                ->where('id', '!=', $address->id)
                                ->get();

        return view('admin.addresses.show', compact('address', 'otherAddresses'));
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        $address->load('user');
        
        $users = User::select('id', 'full_name', 'username', 'email')
                    ->orderBy('full_name')
                    ->get();

        return view('admin.addresses.edit', compact('address', 'users'));
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, Address $address)
    {
        $rules = ValidationService::getAddressRules();
        $rules['user_id'] = 'required|exists:users,id';

        $validated = $request->validate($rules);

        // Handle primary address logic
        if ($validated['is_primary'] ?? false) {
            // If changing to primary, unset other primary addresses for this user
            if (!$address->is_primary) {
                Address::where('user_id', $validated['user_id'])
                       ->where('is_primary', true)
                       ->update(['is_primary' => false]);
            }
        } else {
            // If unsetting primary, ensure user has at least one primary address
            if ($address->is_primary) {
                $userAddressCount = Address::where('user_id', $validated['user_id'])->count();
                if ($userAddressCount === 1) {
                    return redirect()->back()
                                   ->withErrors(['is_primary' => 'User must have at least one primary address.']);
                }
                
                // Set another address as primary
                $nextPrimary = Address::where('user_id', $validated['user_id'])
                                    ->where('id', '!=', $address->id)
                                    ->first();
                if ($nextPrimary) {
                    $nextPrimary->update(['is_primary' => true]);
                }
            }
        }

        $address->update($validated);

        return redirect()->route('admin.addresses.index')
                        ->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        $userId = $address->user_id;
        $isPrimary = $address->is_primary;

        // Check if this is the user's only address
        $userAddressCount = Address::where('user_id', $userId)->count();
        if ($userAddressCount === 1) {
            return redirect()->back()
                           ->with('error', 'Cannot delete the user\'s only address.');
        }

        // If deleting primary address, set another as primary
        if ($isPrimary) {
            $nextPrimary = Address::where('user_id', $userId)
                                 ->where('id', '!=', $address->id)
                                 ->first();
            if ($nextPrimary) {
                $nextPrimary->update(['is_primary' => true]);
            }
        }

        $address->delete();

        return redirect()->route('admin.addresses.index')
                        ->with('success', 'Address deleted successfully.');
    }

    /**
     * Set the specified address as primary.
     */
    public function setPrimary(Address $address)
    {
        // Unset other primary addresses for this user
        Address::where('user_id', $address->user_id)
               ->where('is_primary', true)
               ->update(['is_primary' => false]);

        // Set this address as primary
        $address->update(['is_primary' => true]);

        return redirect()->back()
                        ->with('success', 'Address set as primary successfully.');
    }

    /**
     * Get addresses for a specific user (AJAX).
     */
    public function getUserAddresses(User $user)
    {
        $addresses = $user->addresses()->orderBy('is_primary', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'addresses' => $addresses,
                'total' => $addresses->count(),
                'primary_count' => $addresses->where('is_primary', true)->count(),
            ]
        ]);
    }

    /**
     * Bulk operations on addresses.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,set_primary,unset_primary',
            'addresses' => 'required|array',
            'addresses.*' => 'exists:addresses,id'
        ]);

        $addresses = Address::whereIn('id', $request->addresses)->get();
        $count = 0;

        switch ($request->action) {
            case 'delete':
                foreach ($addresses as $address) {
                    // Check if this is the user's only address
                    $userAddressCount = Address::where('user_id', $address->user_id)->count();
                    if ($userAddressCount > 1) {
                        // If deleting primary address, set another as primary
                        if ($address->is_primary) {
                            $nextPrimary = Address::where('user_id', $address->user_id)
                                                 ->where('id', '!=', $address->id)
                                                 ->first();
                            if ($nextPrimary) {
                                $nextPrimary->update(['is_primary' => true]);
                            }
                        }
                        $address->delete();
                        $count++;
                    }
                }
                break;

            case 'set_primary':
                foreach ($addresses as $address) {
                    // Unset other primary addresses for this user
                    Address::where('user_id', $address->user_id)
                           ->where('is_primary', true)
                           ->update(['is_primary' => false]);
                    
                    $address->update(['is_primary' => true]);
                    $count++;
                }
                break;

            case 'unset_primary':
                foreach ($addresses as $address) {
                    if ($address->is_primary) {
                        // Ensure user has another address to set as primary
                        $userAddressCount = Address::where('user_id', $address->user_id)->count();
                        if ($userAddressCount > 1) {
                            $nextPrimary = Address::where('user_id', $address->user_id)
                                                 ->where('id', '!=', $address->id)
                                                 ->first();
                            if ($nextPrimary) {
                                $nextPrimary->update(['is_primary' => true]);
                                $address->update(['is_primary' => false]);
                                $count++;
                            }
                        }
                    }
                }
                break;
        }

        $actionName = str_replace('_', ' ', $request->action);
        return redirect()->back()
                        ->with('success', "Successfully {$actionName} {$count} address(es).");
    }
} 