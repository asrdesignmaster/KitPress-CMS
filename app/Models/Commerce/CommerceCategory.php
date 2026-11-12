<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommerceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    public function products(): HasMany
    {
        return $this->hasMany(CommerceProduct::class, 'category_id');
    }
}

