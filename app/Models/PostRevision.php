<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'title',
        'editor_type',
        'classic_content',
        'builder_json',
        'summary',
    ];

    protected $casts = [
        'builder_json' => 'array',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

