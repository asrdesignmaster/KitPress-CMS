<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class PublicController extends Controller
{
    public function home(Request $request): Response
    {
        $homepageDisplay = Setting::get('homepage_display', 'latest_posts');
        $homepagePostId = Setting::get('homepage_post_id');

        if ($homepageDisplay === 'static_page' && $homepagePostId) {
            $frontPost = Post::published()
                ->where('id', (int) $homepagePostId)
                ->with(['category', 'user', 'tags', 'featuredMedia'])
                ->first();

            if ($frontPost) {
                return $this->renderSinglePost($frontPost, url('/'));
            }
        }

        // Default: show latest published posts
        $posts = Post::published()
            ->posts()
            ->with(['category', 'user', 'tags'])
            ->latest('published_at')
            ->paginate(9);

        $featured = Post::published()
            ->posts()
            ->whereNotNull('featured_image')
            ->latest('published_at')
            ->first();

        $categories = Category::withCount(['posts' => fn ($q) => $q->published()])->get();

        return Inertia::render('Public/Home', [
            'posts' => $posts,
            'featured' => $featured,
            'categories' => $categories,
            'seo' => [
                'title' => Setting::get('site_name', 'Modern CMS') . ' - Fast, Clean Content Platform',
                'description' => Setting::get('site_tagline', 'High-Performance Laravel + Vue CMS with Visual Tree Builder'),
                'og_image' => null,
                'robots_index' => true,
                'robots_follow' => true,
            ],
        ]);
    }

    public function show(string $slug, Request $request): Response
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['category', 'user', 'tags', 'featuredMedia'])
            ->firstOrFail();

        return $this->renderSinglePost($post, url($post->slug));
    }

    private function renderSinglePost(Post $post, string $canonical): Response
    {
        $recentPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('type', $post->type)
            ->latest('published_at')
            ->take(3)
            ->get();

        $seo = [
            'title' => $post->seo_title ?: $post->title,
            'description' => $post->seo_description ?: $post->excerpt,
            'og_title' => $post->seo_og_title ?: ($post->seo_title ?: $post->title),
            'og_description' => $post->seo_og_description ?: ($post->seo_description ?: $post->excerpt),
            'og_image' => $post->seo_og_image ?: $post->featured_image,
            'robots_index' => (bool) $post->seo_robots_index,
            'robots_follow' => (bool) $post->seo_robots_follow,
            'canonical' => $canonical,
        ];

        return Inertia::render('Public/Show', [
            'post' => $post,
            'recent' => $recentPosts,
            'seo' => $seo,
        ]);
    }

    public function sitemap(): HttpResponse
    {
        $posts = Post::published()
            ->where('seo_robots_index', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Home
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';

        foreach ($posts as $post) {
            $xml .= '<url>';
            $xml .= '<loc>' . url($post->slug) . '</loc>';
            $xml .= '<lastmod>' . ($post->updated_at ?? now())->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>' . ($post->type === 'page' ? 'monthly' : 'weekly') . '</changefreq>';
            $xml .= '<priority>' . ($post->type === 'page' ? '0.8' : '0.6') . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function robots(): HttpResponse
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /login\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}

