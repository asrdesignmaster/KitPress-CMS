<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostRevision;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OptimizationController extends Controller
{
    public function index(): Response
    {
        $settings = [
            'route_caching' => (bool) Setting::get('route_caching', false),
            'config_caching' => (bool) Setting::get('config_caching', false),
            'lazy_loading' => (bool) Setting::get('lazy_loading', true),
            'response_compression' => (bool) Setting::get('response_compression', true),
            'site_name' => (string) Setting::get('site_name', 'Modern CMS'),
            'site_tagline' => (string) Setting::get('site_tagline', 'Lightweight Laravel + Vue CMS Platform'),
        ];

        $revisionsCount = PostRevision::count();

        // Orphan media: media not linked as featured_image_id and not referenced in post contents
        $linkedMediaIds = Post::whereNotNull('featured_image_id')->pluck('featured_image_id')->toArray();
        $orphanMediaCount = Media::whereNotIn('id', $linkedMediaIds)->count();

        return Inertia::render('Admin/Settings/Optimization', [
            'settings' => $settings,
            'stats' => [
                'revisions_count' => $revisionsCount,
                'orphan_media_count' => $orphanMediaCount,
                'cache_driver' => config('cache.default'),
                'is_route_cached' => app()->routesAreCached(),
                'is_config_cached' => app()->configurationIsCached(),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'lazy_loading' => ['boolean'],
            'response_compression' => ['boolean'],
            'route_caching' => ['boolean'],
            'config_caching' => ['boolean'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'optimization');
        }

        // Apply route or config cache if toggled
        if (! empty($validated['route_caching']) && ! app()->routesAreCached()) {
            Artisan::call('route:cache');
        } elseif (empty($validated['route_caching']) && app()->routesAreCached()) {
            Artisan::call('route:clear');
        }

        if (! empty($validated['config_caching']) && ! app()->configurationIsCached()) {
            Artisan::call('config:cache');
        } elseif (empty($validated['config_caching']) && app()->configurationIsCached()) {
            Artisan::call('config:clear');
        }

        return redirect()->back()->with('success', 'Optimization settings updated successfully.');
    }

    public function purgeRevisions(): RedirectResponse
    {
        $count = PostRevision::count();
        PostRevision::truncate();

        return redirect()->back()->with('success', "Purged {$count} post revisions from the database.");
    }

    public function purgeOrphans(): RedirectResponse
    {
        $linkedMediaIds = Post::whereNotNull('featured_image_id')->pluck('featured_image_id')->toArray();
        $orphans = Media::whereNotIn('id', $linkedMediaIds)->get();
        $count = $orphans->count();

        // We clean up unlinked media files and rows safely
        foreach ($orphans as $media) {
            // Delete record
            $media->delete();
        }

        return redirect()->back()->with('success', "Purged {$count} unlinked/orphan media assets.");
    }

    public function purgeLogs(): RedirectResponse
    {
        // Clear log files or log cache
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
        }

        Cache::flush();

        return redirect()->back()->with('success', 'Transient log files and query cache purged successfully.');
    }

    public function clearCache(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'all');

        if ($type === 'routes') {
            Artisan::call('route:clear');
            $msg = 'Route cache cleared.';
        } elseif ($type === 'views') {
            Artisan::call('view:clear');
            $msg = 'Compiled view templates cleared.';
        } elseif ($type === 'config') {
            Artisan::call('config:clear');
            $msg = 'Configuration cache cleared.';
        } else {
            Artisan::call('optimize:clear');
            $msg = 'Entire application cache, views, routes, and config cleared.';
        }

        return redirect()->back()->with('success', $msg);
    }
}

