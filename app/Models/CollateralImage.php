<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CollateralImage extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'collateral_images';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * The data type of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'collateral_id',
        'image_url',
        'is_thumbnail',
        'order_index',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_thumbnail' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Get the collateral that owns the image.
     */
    public function collateral(): BelongsTo
    {
        return $this->belongsTo(Collateral::class, 'collateral_id');
    }

    /**
     * Scope a query to only include thumbnail images.
     */
    public function scopeThumbnail($query)
    {
        return $query->where('is_thumbnail', true);
    }

    /**
     * Scope a query to order by index.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}
