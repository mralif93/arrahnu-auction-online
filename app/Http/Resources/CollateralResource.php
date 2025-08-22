<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollateralResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account' => [
                'id' => $this->account->id,
                'account_title' => $this->account->account_title,
                'branch' => [
                    'id' => $this->account->branch->id ?? null,
                    'name' => $this->account->branch->name ?? null,
                ],
            ],
            'auction' => [
                'id' => $this->auction->id,
                'title' => $this->auction->auction_title,
                'status' => $this->auction->status,
                'start_date' => $this->auction->start_datetime?->format('Y-m-d H:i:s'),
                'end_date' => $this->auction->end_datetime?->format('Y-m-d H:i:s'),
            ],
            'item_type' => $this->item_type,
            'description' => $this->description,
            'weight_grams' => $this->weight_grams,
            'purity' => $this->purity,
            'estimated_value_rm' => $this->estimated_value_rm,
            'starting_bid_rm' => $this->starting_bid_rm,
            'current_highest_bid_rm' => $this->current_highest_bid_rm,
            'status' => $this->status,
            'status_color' => $this->status_color,
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->image_url,
                        'is_thumbnail' => $image->is_thumbnail,
                        'order_index' => $image->order_index,
                    ];
                });
            }),
            'primary_image' => $this->primary_image,
            'highest_bidder' => $this->whenLoaded('highestBidder', function () {
                return $this->highestBidder ? [
                    'id' => $this->highestBidder->id,
                    'name' => $this->highestBidder->name,
                    'email' => $this->highestBidder->email,
                ] : null;
            }),
            'creator' => $this->whenLoaded('creator', function () {
                return $this->creator ? [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                    'email' => $this->creator->email,
                ] : null;
            }),
            'approved_by' => $this->whenLoaded('approvedBy', function () {
                return $this->approvedBy ? [
                    'id' => $this->approvedBy->id,
                    'name' => $this->approvedBy->name,
                    'email' => $this->approvedBy->email,
                ] : null;
            }),
            'bids_count' => $this->whenLoaded('bids', function () {
                return $this->bids->count();
            }),
            'recent_bids' => $this->whenLoaded('bids', function () {
                return $this->bids->take(5)->map(function ($bid) {
                    return [
                        'id' => $bid->id,
                        'amount' => $bid->bid_amount_rm,
                        'bidder' => [
                            'id' => $bid->user->id,
                            'name' => $bid->user->name,
                        ],
                        'created_at' => $bid->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            }),
            'formatted' => [
                'estimated_value' => $this->estimated_value_rm ? 'RM ' . number_format($this->estimated_value_rm, 2) : 'N/A',
                'starting_bid' => 'RM ' . number_format($this->starting_bid_rm, 2),
                'current_highest_bid' => 'RM ' . number_format($this->current_highest_bid_rm, 2),
                'weight' => $this->weight_grams ? number_format($this->weight_grams, 2) . 'g' : 'N/A',
                'status' => ucfirst(str_replace('_', ' ', $this->status)),
            ],
            'can_edit' => $this->canEdit(),
            'can_approve' => $this->canApprove(),
            'can_start_auction' => $this->canStartAuction(),
            'can_end_auction' => $this->canEndAuction(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Check if collateral can be edited.
     */
    private function canEdit(): bool
    {
        return in_array($this->status, ['pending', 'rejected']) &&
               (auth()->user()->isAdmin() || auth()->user()->isMaker());
    }

    /**
     * Check if collateral can be approved.
     */
    private function canApprove(): bool
    {
        return $this->status === 'pending' &&
               (auth()->user()->isAdmin() || auth()->user()->isChecker());
    }

    /**
     * Check if auction can be started for this collateral.
     */
    private function canStartAuction(): bool
    {
        return $this->status === 'active' &&
               $this->auction &&
               $this->auction->status === 'active' &&
               auth()->user()->isAdmin();
    }

    /**
     * Check if auction can be ended for this collateral.
     */
    private function canEndAuction(): bool
    {
        return $this->status === 'active' &&
               auth()->user()->isAdmin();
    }
}
