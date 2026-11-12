<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class MediaController extends Controller
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function index(Request $request): Response|JsonResponse
    {
        $query = Media::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        if ($type = $request->query('mime')) {
            $query->where('mime_type', 'like', "{$type}%");
        }

        $media = $query->latest()->paginate(24)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($media);
        }

        return Inertia::render('Admin/Media/Index', [
            'media' => $media,
            'filters' => $request->only(['search', 'mime']),
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'max:20480'], // max 20MB
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $media = $this->mediaService->processUpload(
                file: $file,
                user: $request->user(),
                altText: $request->input('alt_text')
            );
            $uploaded[] = $media;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'media' => $uploaded,
            ]);
        }

        return redirect()->back()
            ->with('success', count($uploaded) . ' media files uploaded and converted to WebP successfully.');
    }

    public function update(Media $medium, Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:500'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $medium->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'media' => $medium]);
        }

        return redirect()->back()->with('success', 'Media metadata updated.');
    }

    public function destroy(Media $medium, Request $request): RedirectResponse|JsonResponse
    {
        // Remove files
        $disk = Storage::disk($medium->disk ?? 'public');

        if ($medium->path && $disk->exists($medium->path)) {
            $disk->delete($medium->path);
        }

        if ($medium->webp_path && $disk->exists($medium->webp_path)) {
            $disk->delete($medium->webp_path);
        }

        if (is_array($medium->sizes)) {
            foreach ($medium->sizes as $size) {
                if (! empty($size['path']) && $disk->exists($size['path'])) {
                    $disk->delete($size['path']);
                }
            }
        }

        $medium->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Media file permanently deleted.');
    }
}

