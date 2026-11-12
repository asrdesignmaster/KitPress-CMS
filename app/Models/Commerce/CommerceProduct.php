<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommerceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'status',
        'short_description',
        'description',
        'builder_json',
        'featured_image',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'builder_json' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CommerceCategory::class, 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(CommerceVariant::class, 'product_id');
    }
}

