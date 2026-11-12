<?php

namespace App\Services;

use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class MediaService
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Process an uploaded file, optimize, convert to WebP, generate responsive sizes and save.
     */
    public function processUpload(UploadedFile $file, ?User $user = null, ?string $altText = null): Media
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanBaseName = Str::slug($originalName) ?: 'media';
        $timestamp = time() . '_' . Str::random(6);
        $dateFolder = 'uploads/media/' . date('Y/m');

        // Target storage disk
        $disk = Storage::disk('public');
        $disk->makeDirectory($dateFolder);

        $mimeType = $file->getClientMimeType();
        $isImage = str_starts_with($mimeType, 'image/') && ! str_contains($mimeType, 'svg');

        if ($isImage) {
            $image = $this->imageManager->decodePath($file->getRealPath());
            $origWidth = $image->width();
            $origHeight = $image->height();

            // 1. Original WebP
            $webpFileName = "{$cleanBaseName}_{$timestamp}.webp";
            $webpRelativePath = "{$dateFolder}/{$webpFileName}";
            $encodedWebp = $image->encodeUsingFormat(Format::WEBP, quality: 82);
            $disk->put($webpRelativePath, (string) $encodedWebp);

            $sizes = [];

            // 2. Thumbnail (150x150 cover/fit)
            $thumbFileName = "{$cleanBaseName}_{$timestamp}_thumb.webp";
            $thumbPath = "{$dateFolder}/{$thumbFileName}";
            $thumbImage = clone $image;
            $thumbImage->cover(150, 150);
            $thumbEncoded = $thumbImage->encodeUsingFormat(Format::WEBP, quality: 80);
            $disk->put($thumbPath, (string) $thumbEncoded);
            $sizes['thumbnail'] = [
                'path' => $thumbPath,
                'url' => $disk->url($thumbPath),
                'width' => 150,
                'height' => 150,
            ];

            // 3. Medium (Max width 600)
            if ($origWidth > 300) {
                $medFileName = "{$cleanBaseName}_{$timestamp}_med.webp";
                $medPath = "{$dateFolder}/{$medFileName}";
                $medImage = clone $image;
                $medImage->scaleDown(width: 600);
                $medEncoded = $medImage->encodeUsingFormat(Format::WEBP, quality: 80);
                $disk->put($medPath, (string) $medEncoded);
                $sizes['medium'] = [
                    'path' => $medPath,
                    'url' => $disk->url($medPath),
                    'width' => $medImage->width(),
                    'height' => $medImage->height(),
                ];
            }

            // 4. Large (Max width 1200)
            if ($origWidth > 800) {
                $largeFileName = "{$cleanBaseName}_{$timestamp}_large.webp";
                $largePath = "{$dateFolder}/{$largeFileName}";
                $largeImage = clone $image;
                $largeImage->scaleDown(width: 1200);
                $largeEncoded = $largeImage->encodeUsingFormat(Format::WEBP, quality: 82);
                $disk->put($largePath, (string) $largeEncoded);
                $sizes['large'] = [
                    'path' => $largePath,
                    'url' => $disk->url($largePath),
                    'width' => $largeImage->width(),
                    'height' => $largeImage->height(),
                ];
            }

            // Full original webp size entry
            $sizes['original'] = [
                'path' => $webpRelativePath,
                'url' => $disk->url($webpRelativePath),
                'width' => $origWidth,
                'height' => $origHeight,
            ];

            $finalPath = $webpRelativePath;
            $finalFileSize = strlen((string) $encodedWebp);
            $finalMime = 'image/webp';
            $fileNameToStore = $webpFileName;
            $webpPath = $webpRelativePath;
        } else {
            // For SVGs, PDFs, or other assets
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = "{$cleanBaseName}_{$timestamp}.{$extension}";
            $finalPath = $file->storeAs($dateFolder, $fileNameToStore, 'public');
            $finalFileSize = $file->getSize();
            $finalMime = $mimeType;
            $origWidth = null;
            $origHeight = null;
            $webpPath = null;
            $sizes = [];
        }

        return Media::create([
            'user_id' => $user?->id,
            'name' => $originalName,
            'file_name' => $fileNameToStore,
            'disk' => 'public',
            'mime_type' => $finalMime,
            'path' => $finalPath,
            'webp_path' => $webpPath,
            'sizes' => $sizes,
            'width' => $origWidth,
            'height' => $origHeight,
            'file_size' => $finalFileSize,
            'alt_text' => $altText ?: $originalName,
            'caption' => null,
        ]);
    }
}
