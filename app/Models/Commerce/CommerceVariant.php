<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommerceVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'title',
        'price',
        'stock',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'attributes' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(CommerceProduct::class, 'product_id');
    }
}

