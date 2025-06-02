<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollateralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isMaker());
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|exists:accounts,id',
            'auction_id' => 'required|exists:auctions,id',
            'item_type' => 'required|string|max:50',
            'description' => 'required|string|min:10|max:1000',
            'weight_grams' => 'nullable|numeric|min:0|max:99999.99',
            'purity' => 'nullable|string|max:20',
            'estimated_value_rm' => 'nullable|numeric|min:0|max:999999.99',
            'starting_bid_rm' => 'required|numeric|min:1|max:999999.99',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail_index' => 'nullable|integer|min:0|max:4',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'account_id.required' => 'Please select an account.',
            'account_id.exists' => 'The selected account is invalid.',
            'auction_id.required' => 'Please select an auction.',
            'auction_id.exists' => 'The selected auction is invalid.',
            'item_type.required' => 'Item type is required.',
            'item_type.max' => 'Item type cannot exceed 50 characters.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'weight_grams.numeric' => 'Weight must be a valid number.',
            'weight_grams.min' => 'Weight cannot be negative.',
            'weight_grams.max' => 'Weight cannot exceed 99,999.99 grams.',
            'purity.max' => 'Purity cannot exceed 20 characters.',
            'estimated_value_rm.numeric' => 'Estimated value must be a valid number.',
            'estimated_value_rm.min' => 'Estimated value cannot be negative.',
            'estimated_value_rm.max' => 'Estimated value cannot exceed RM 999,999.99.',
            'starting_bid_rm.required' => 'Starting bid is required.',
            'starting_bid_rm.numeric' => 'Starting bid must be a valid number.',
            'starting_bid_rm.min' => 'Starting bid must be at least RM 1.00.',
            'starting_bid_rm.max' => 'Starting bid cannot exceed RM 999,999.99.',
            'images.array' => 'Images must be an array.',
            'images.max' => 'You can upload a maximum of 5 images.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Images must be in JPEG, PNG, JPG, or GIF format.',
            'images.*.max' => 'Each image cannot exceed 2MB.',
            'thumbnail_index.integer' => 'Thumbnail index must be a valid number.',
            'thumbnail_index.min' => 'Thumbnail index cannot be negative.',
            'thumbnail_index.max' => 'Thumbnail index cannot exceed 4.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'account_id' => 'account',
            'auction_id' => 'auction',
            'item_type' => 'item type',
            'description' => 'description',
            'weight_grams' => 'weight',
            'purity' => 'purity',
            'estimated_value_rm' => 'estimated value',
            'starting_bid_rm' => 'starting bid',
            'images' => 'images',
            'thumbnail_index' => 'thumbnail selection',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up numeric fields
        if ($this->has('weight_grams')) {
            $this->merge([
                'weight_grams' => $this->weight_grams ? (float) str_replace(',', '', $this->weight_grams) : null,
            ]);
        }

        if ($this->has('estimated_value_rm')) {
            $this->merge([
                'estimated_value_rm' => $this->estimated_value_rm ? (float) str_replace(',', '', $this->estimated_value_rm) : null,
            ]);
        }

        if ($this->has('starting_bid_rm')) {
            $this->merge([
                'starting_bid_rm' => (float) str_replace(',', '', $this->starting_bid_rm),
            ]);
        }
    }
}
