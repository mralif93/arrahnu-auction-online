<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddressService
{
    /**
     * Get user's addresses with ordering.
     */
    public function getUserAddresses(User $user, array $options = []): Collection
    {
        $query = $user->addresses();

        // Apply ordering
        $orderBy = $options['order_by'] ?? 'is_primary';
        $orderDirection = $options['order_direction'] ?? 'desc';
        
        if ($orderBy === 'is_primary') {
            $query->orderBy('is_primary', 'desc')
                  ->orderBy('created_at', 'desc');
        } else {
            $query->orderBy($orderBy, $orderDirection);
        }

        // Apply filters
        if (isset($options['state'])) {
            $query->where('state', $options['state']);
        }

        if (isset($options['is_primary'])) {
            $query->where('is_primary', $options['is_primary']);
        }

        return $query->get();
    }

    /**
     * Create a new address for user.
     */
    public function createAddress(User $user, array $data): Address
    {
        return DB::transaction(function () use ($user, $data) {
            // Determine if this should be primary
            $isPrimary = $data['is_primary'] ?? false;
            $userAddressCount = $user->addresses()->count();
            
            // If this is the user's first address, make it primary
            if ($userAddressCount === 0) {
                $isPrimary = true;
            }

            // Create the address
            $address = Address::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'] ?? null,
                'city' => $data['city'],
                'state' => $data['state'],
                'postcode' => $data['postcode'],
                'country' => $data['country'] ?? 'Malaysia',
                'is_primary' => $isPrimary,
            ]);

            // Handle primary address logic
            if ($isPrimary) {
                $this->setPrimaryAddress($user, $address);
            }

            return $address->fresh();
        });
    }

    /**
     * Update an existing address.
     */
    public function updateAddress(Address $address, array $data): Address
    {
        return DB::transaction(function () use ($address, $data) {
            $user = $address->user;
            $wasPrimary = $address->is_primary;
            $shouldBePrimary = $data['is_primary'] ?? false;

            // Update address data
            $address->update([
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'] ?? null,
                'city' => $data['city'],
                'state' => $data['state'],
                'postcode' => $data['postcode'],
                'country' => $data['country'] ?? 'Malaysia',
            ]);

            // Handle primary address changes
            if ($shouldBePrimary && !$wasPrimary) {
                $this->setPrimaryAddress($user, $address);
            } elseif (!$shouldBePrimary && $wasPrimary) {
                // If unsetting primary, set another address as primary
                $this->unsetPrimaryAddress($user, $address);
            }

            return $address->fresh();
        });
    }

    /**
     * Delete an address with safety checks.
     */
    public function deleteAddress(Address $address): array
    {
        return DB::transaction(function () use ($address) {
            $user = $address->user;
            $isPrimary = $address->is_primary;
            $userAddressCount = $user->addresses()->count();

            // Safety check: Don't allow deletion of only address
            if ($userAddressCount === 1) {
                return [
                    'success' => false,
                    'message' => 'Cannot delete your only address. Please add another address first.',
                    'error_code' => 'ONLY_ADDRESS'
                ];
            }

            $addressId = $address->id;
            $address->delete();

            // If deleted address was primary, set another as primary
            $newPrimaryId = null;
            if ($isPrimary) {
                $newPrimary = $user->addresses()->first();
                if ($newPrimary) {
                    $this->setPrimaryAddress($user, $newPrimary);
                    $newPrimaryId = $newPrimary->id;
                }
            }

            return [
                'success' => true,
                'message' => 'Address deleted successfully.',
                'deleted_address_id' => $addressId,
                'new_primary_address_id' => $newPrimaryId
            ];
        });
    }

    /**
     * Set an address as primary for a user.
     */
    public function setPrimaryAddress(User $user, Address $address): void
    {
        DB::transaction(function () use ($user, $address) {
            // Unset all other primary addresses for this user
            $user->addresses()
                 ->where('id', '!=', $address->id)
                 ->where('is_primary', true)
                 ->update(['is_primary' => false]);

            // Set this address as primary
            $address->update(['is_primary' => true]);

            // Update user's primary address reference
            $user->update(['primary_address_id' => $address->id]);
        });
    }

    /**
     * Unset primary address and assign to another address.
     */
    public function unsetPrimaryAddress(User $user, Address $currentPrimary): void
    {
        DB::transaction(function () use ($user, $currentPrimary) {
            // Find another address to make primary
            $newPrimary = $user->addresses()
                              ->where('id', '!=', $currentPrimary->id)
                              ->first();

            if ($newPrimary) {
                // Set the new primary
                $currentPrimary->update(['is_primary' => false]);
                $this->setPrimaryAddress($user, $newPrimary);
            }
            // If no other address exists, keep current as primary
        });
    }

    /**
     * Get address statistics for a user.
     */
    public function getUserAddressStatistics(User $user): array
    {
        $addresses = $user->addresses;
        $stateDistribution = $addresses->groupBy('state')->map->count();

        return [
            'total_addresses' => $addresses->count(),
            'primary_address' => $addresses->where('is_primary', true)->first(),
            'states_covered' => $stateDistribution->keys()->count(),
            'state_distribution' => $stateDistribution->toArray(),
            'most_recent' => $addresses->sortByDesc('created_at')->first(),
            'oldest' => $addresses->sortBy('created_at')->first(),
        ];
    }

    /**
     * Get global address statistics (admin).
     */
    public function getGlobalStatistics(): array
    {
        $totalAddresses = Address::count();
        $primaryAddresses = Address::where('is_primary', true)->count();
        $recentAddresses = Address::where('created_at', '>=', now()->subDays(30))->count();
        
        $stateDistribution = Address::select('state', DB::raw('count(*) as count'))
                                   ->groupBy('state')
                                   ->orderBy('count', 'desc')
                                   ->get()
                                   ->pluck('count', 'state')
                                   ->toArray();

        $topState = array_key_first($stateDistribution);

        return [
            'total_addresses' => $totalAddresses,
            'primary_addresses' => $primaryAddresses,
            'recent_addresses' => $recentAddresses,
            'top_state' => $topState,
            'state_distribution' => $stateDistribution,
            'users_with_addresses' => Address::distinct('user_id')->count('user_id'),
            'average_addresses_per_user' => $totalAddresses > 0 ? round($totalAddresses / Address::distinct('user_id')->count('user_id'), 2) : 0,
        ];
    }

    /**
     * Search addresses with advanced filters.
     */
    public function searchAddresses(array $filters = []): Collection
    {
        $query = Address::with('user');

        // Text search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
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

        // State filter
        if (!empty($filters['state'])) {
            $query->where('state', $filters['state']);
        }

        // Primary status filter
        if (isset($filters['is_primary'])) {
            $query->where('is_primary', $filters['is_primary']);
        }

        // User filter
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Ordering
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDirection = $filters['order_direction'] ?? 'desc';
        $query->orderBy($orderBy, $orderDirection);

        return $query->get();
    }

    /**
     * Bulk operations on addresses.
     */
    public function bulkOperation(array $addressIds, string $operation, array $options = []): array
    {
        return DB::transaction(function () use ($addressIds, $operation, $options) {
            $addresses = Address::whereIn('id', $addressIds)->get();
            $results = [];

            foreach ($addresses as $address) {
                try {
                    switch ($operation) {
                        case 'delete':
                            $result = $this->deleteAddress($address);
                            $results[] = [
                                'address_id' => $address->id,
                                'success' => $result['success'],
                                'message' => $result['message']
                            ];
                            break;

                        case 'set_primary':
                            if (isset($options['user_id']) && $address->user_id == $options['user_id']) {
                                $this->setPrimaryAddress($address->user, $address);
                                $results[] = [
                                    'address_id' => $address->id,
                                    'success' => true,
                                    'message' => 'Set as primary successfully'
                                ];
                            } else {
                                $results[] = [
                                    'address_id' => $address->id,
                                    'success' => false,
                                    'message' => 'Invalid user for primary operation'
                                ];
                            }
                            break;

                        case 'unset_primary':
                            if ($address->is_primary) {
                                $this->unsetPrimaryAddress($address->user, $address);
                                $results[] = [
                                    'address_id' => $address->id,
                                    'success' => true,
                                    'message' => 'Unset primary successfully'
                                ];
                            } else {
                                $results[] = [
                                    'address_id' => $address->id,
                                    'success' => false,
                                    'message' => 'Address is not primary'
                                ];
                            }
                            break;

                        default:
                            $results[] = [
                                'address_id' => $address->id,
                                'success' => false,
                                'message' => 'Unknown operation'
                            ];
                    }
                } catch (\Exception $e) {
                    $results[] = [
                        'address_id' => $address->id,
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            }

            return [
                'success' => true,
                'operation' => $operation,
                'total_processed' => count($results),
                'results' => $results
            ];
        });
    }

    /**
     * Get Malaysian states list.
     */
    public function getMalaysianStates(): array
    {
        return [
            'Johor',
            'Kedah',
            'Kelantan',
            'Kuala Lumpur',
            'Labuan',
            'Melaka',
            'Negeri Sembilan',
            'Pahang',
            'Penang',
            'Perak',
            'Perlis',
            'Putrajaya',
            'Sabah',
            'Sarawak',
            'Selangor',
            'Terengganu'
        ];
    }

    /**
     * Validate postcode format.
     */
    public function validatePostcode(string $postcode, string $country = 'Malaysia'): bool
    {
        if ($country === 'Malaysia') {
            return preg_match('/^\d{5}$/', $postcode);
        }
        
        // Add other country validations as needed
        return true;
    }

    /**
     * Format address for display.
     */
    public function formatAddress(Address $address, string $format = 'full'): string
    {
        switch ($format) {
            case 'short':
                return "{$address->city}, {$address->state} {$address->postcode}";
            
            case 'single_line':
                $parts = array_filter([
                    $address->address_line_1,
                    $address->address_line_2,
                    $address->city,
                    $address->state,
                    $address->postcode,
                    $address->country
                ]);
                return implode(', ', $parts);
            
            case 'full':
            default:
                return $address->full_address;
        }
    }

    /**
     * Get address suggestions based on partial input.
     */
    public function getAddressSuggestions(User $user, string $query, int $limit = 5): Collection
    {
        return $user->addresses()
                   ->where(function ($q) use ($query) {
                       $q->where('address_line_1', 'like', "%{$query}%")
                         ->orWhere('city', 'like', "%{$query}%")
                         ->orWhere('state', 'like', "%{$query}%");
                   })
                   ->limit($limit)
                   ->get();
    }

    /**
     * Export user addresses to array.
     */
    public function exportUserAddresses(User $user): array
    {
        return $user->addresses->map(function ($address) {
            return [
                'id' => $address->id,
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
    }
} 