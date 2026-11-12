<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostRevision;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $postsCount = Post::posts()->count();
        $publishedPostsCount = Post::posts()->published()->count();
        $pagesCount = Post::pages()->count();
        $categoriesCount = Category::count();
        $mediaCount = Media::count();
        $revisionsCount = PostRevision::count();
        $usersCount = User::count();

        $recentPosts = Post::with(['category', 'user'])
            ->latest()
            ->take(6)
            ->get();

        $recentMedia = Media::latest()
            ->take(8)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'posts_count' => $postsCount,
                'published_posts_count' => $publishedPostsCount,
                'pages_count' => $pagesCount,
                'categories_count' => $categoriesCount,
                'media_count' => $mediaCount,
                'revisions_count' => $revisionsCount,
                'users_count' => $usersCount,
            ],
            'recent_posts' => $recentPosts,
            'recent_media' => $recentMedia,
            'server_info' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_time' => now()->toFormattedDateString() . ' ' . now()->format('H:i:s'),
                'database' => config('database.default'),
            ],
        ]);
    }
}

