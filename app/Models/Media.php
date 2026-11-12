<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'file_name',
        'disk',
        'mime_type',
        'path',
        'webp_path',
        'sizes',
        'width',
        'height',
        'file_size',
        'alt_text',
        'caption',
    ];

    protected $casts = [
        'sizes' => 'array',
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
    ];

    protected $appends = [
        'url',
        'formatted_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }

        return Storage::disk($this->disk ?? 'public')->url($this->webp_path ?: $this->path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes <= 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));

        return round($bytes / (1024 ** $i), 1) . ' ' . $units[$i];
    }
}

