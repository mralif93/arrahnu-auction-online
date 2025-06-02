<?php

namespace App\Services;

use App\Models\Collateral;
use App\Models\CollateralImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollateralService
{
    /**
     * Create a new collateral with images.
     */
    public function createCollateral(array $data, array $images = []): Collateral
    {
        $collateral = Collateral::create([
            'account_id' => $data['account_id'],
            'auction_id' => $data['auction_id'],
            'item_type' => $data['item_type'],
            'description' => $data['description'],
            'weight_grams' => $data['weight_grams'] ?? null,
            'purity' => $data['purity'] ?? null,
            'estimated_value_rm' => $data['estimated_value_rm'] ?? null,
            'starting_bid_rm' => $data['starting_bid_rm'],
            'current_highest_bid_rm' => 0.00,
            'status' => Collateral::STATUS_DRAFT,
            'created_by_user_id' => auth()->id(),
        ]);

        if (!empty($images)) {
            $this->handleImageUploads($collateral, $images, $data['thumbnail_index'] ?? 0);
        }

        return $collateral->load(['account', 'auction', 'images', 'creator']);
    }

    /**
     * Update collateral with images.
     */
    public function updateCollateral(Collateral $collateral, array $data, array $images = [], array $removeImages = []): Collateral
    {
        // Update collateral data
        $collateral->update([
            'item_type' => $data['item_type'],
            'description' => $data['description'],
            'weight_grams' => $data['weight_grams'] ?? null,
            'purity' => $data['purity'] ?? null,
            'estimated_value_rm' => $data['estimated_value_rm'] ?? null,
            'starting_bid_rm' => $data['starting_bid_rm'],
        ]);

        // Remove selected images
        if (!empty($removeImages)) {
            $this->removeImages($removeImages);
        }

        // Add new images
        if (!empty($images)) {
            $existingImagesCount = $collateral->images()->count();
            $this->handleImageUploads($collateral, $images, $data['thumbnail_index'] ?? 0, $existingImagesCount);
        }

        return $collateral->load(['account', 'auction', 'images', 'creator']);
    }

    /**
     * Handle image uploads for collateral.
     */
    private function handleImageUploads(Collateral $collateral, array $images, int $thumbnailIndex = 0, int $startingOrderIndex = 0): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $imagePath = $this->storeImage($image);
                
                CollateralImage::create([
                    'collateral_id' => $collateral->id,
                    'image_url' => $imagePath,
                    'is_thumbnail' => $index === $thumbnailIndex,
                    'order_index' => $startingOrderIndex + $index,
                ]);
            }
        }
    }

    /**
     * Store uploaded image and return path.
     */
    private function storeImage(UploadedFile $image): string
    {
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('collateral-images', $filename, 'public');
        
        return Storage::url($path);
    }

    /**
     * Remove images by IDs.
     */
    private function removeImages(array $imageIds): void
    {
        $images = CollateralImage::whereIn('id', $imageIds)->get();
        
        foreach ($images as $image) {
            // Delete file from storage
            $imagePath = str_replace('/storage/', '', $image->image_url);
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            // Delete database record
            $image->delete();
        }
    }

    /**
     * Delete collateral and its images.
     */
    public function deleteCollateral(Collateral $collateral): bool
    {
        // Remove all images
        $imageIds = $collateral->images()->pluck('id')->toArray();
        if (!empty($imageIds)) {
            $this->removeImages($imageIds);
        }

        return $collateral->delete();
    }

    /**
     * Get collateral with all relationships.
     */
    public function getCollateralWithRelations(string $id): ?Collateral
    {
        return Collateral::with([
            'account.branch',
            'auction',
            'images' => function ($query) {
                $query->orderBy('order_index');
            },
            'creator',
            'approvedBy',
            'highestBidder',
            'bids' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            }
        ])->find($id);
    }

    /**
     * Get filtered collaterals for listing.
     */
    public function getFilteredCollaterals(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Collateral::with(['account.branch', 'creator', 'approvedBy', 'images', 'highestBidder']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['item_type'])) {
            $query->where('item_type', 'like', '%' . $filters['item_type'] . '%');
        }

        if (!empty($filters['account_id'])) {
            $query->where('account_id', $filters['account_id']);
        }

        if (!empty($filters['auction_id'])) {
            $query->where('auction_id', $filters['auction_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('item_type', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Update collateral status.
     */
    public function updateStatus(Collateral $collateral, string $status, ?string $reason = null): bool
    {
        $validStatuses = [
            Collateral::STATUS_DRAFT,
            Collateral::STATUS_PENDING_APPROVAL,
            Collateral::STATUS_ACTIVE,
            Collateral::STATUS_INACTIVE,
            Collateral::STATUS_REJECTED,
        ];

        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $updateData = ['status' => $status];

        // Set approved_by_user_id for certain status changes
        if (in_array($status, [Collateral::STATUS_ACTIVE, Collateral::STATUS_REJECTED])) {
            $updateData['approved_by_user_id'] = auth()->id();
        }

        return $collateral->update($updateData);
    }

    /**
     * Get collateral statistics.
     */
    public function getStatistics(): array
    {
        $collaterals = Collateral::all();

        return [
            'total' => $collaterals->count(),
            'active' => $collaterals->where('status', Collateral::STATUS_ACTIVE)->count(),
            'inactive' => $collaterals->where('status', Collateral::STATUS_INACTIVE)->count(),
            'pending_approval' => $collaterals->where('status', Collateral::STATUS_PENDING_APPROVAL)->count(),
            'draft' => $collaterals->where('status', Collateral::STATUS_DRAFT)->count(),
            'rejected' => $collaterals->where('status', Collateral::STATUS_REJECTED)->count(),
            'total_estimated_value' => $collaterals->sum('estimated_value_rm') ?? 0,
            'total_bid_value' => $collaterals->sum('current_highest_bid_rm') ?? 0,
        ];
    }
}
