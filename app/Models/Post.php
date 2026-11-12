<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'type',
        'editor_type',
        'classic_content',
        'builder_json',
        'status',
        'featured_image',
        'featured_image_id',
        'excerpt',
        'published_at',
        'seo_focus_keyword',
        'seo_title',
        'seo_description',
        'seo_og_title',
        'seo_og_description',
        'seo_og_image',
        'seo_robots_index',
        'seo_robots_follow',
    ];

    protected $casts = [
        'builder_json' => 'array',
        'published_at' => 'datetime',
        'seo_robots_index' => 'boolean',
        'seo_robots_follow' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PostRevision::class)->latest();
    }

    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopePosts(Builder $query): Builder
    {
        return $query->where('type', 'post');
    }

    public function scopePages(Builder $query): Builder
    {
        return $query->where('type', 'page');
    }
}

