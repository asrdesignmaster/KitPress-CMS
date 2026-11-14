<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostRevision;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $type = $request->query('type', 'post');
        if (! in_array($type, ['post', 'page'], true)) {
            $type = 'post';
        }

        $query = Post::query()
            ->where('type', $type)
            ->with(['category', 'user', 'tags']);

        // Search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->query('status')) {
            if (in_array($status, ['published', 'draft'], true)) {
                $query->where('status', $status);
            }
        }

        // Category filter
        if ($category = $request->query('category_id')) {
            $query->where('category_id', $category);
        }

        // Author RBAC check: Authors only see their own posts, unless Editor or Admin
        $user = $request->user();
        if ($user->role === 'author') {
            $query->where('user_id', $user->id);
        }

        $posts = $query->latest()->paginate(15)->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        // Counts for tab filters
        $baseCounts = Post::where('type', $type);
        if ($user->role === 'author') {
            $baseCounts->where('user_id', $user->id);
        }

        $counts = [
            'all' => (clone $baseCounts)->count(),
            'published' => (clone $baseCounts)->where('status', 'published')->count(),
            'draft' => (clone $baseCounts)->where('status', 'draft')->count(),
        ];

        $homepagePostId = \App\Models\Setting::get('homepage_display', 'latest_posts') === 'static_page'
            ? (int) \App\Models\Setting::get('homepage_post_id')
            : null;

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['search', 'status', 'category_id', 'type']),
            'categories' => $categories,
            'counts' => $counts,
            'type' => $type,
            'homepagePostId' => $homepagePostId ?: null,
        ]);
    }

    public function create(Request $request): Response
    {
        $type = $request->query('type', 'post');
        $editorType = $request->query('editor', 'classic');

        return Inertia::render('Admin/Posts/Editor', [
            'post' => null,
            'type' => $type,
            'default_editor' => $editorType,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('slug'))]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'type' => ['required', 'in:post,page'],
            'editor_type' => ['required', 'in:classic,builder'],
            'classic_content' => ['nullable', 'string'],
            'builder_json' => ['nullable', 'array'],
            'status' => ['required', 'in:draft,published'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'featured_image' => ['nullable', 'string'],
            'featured_image_id' => ['nullable', 'exists:media,id'],
            'excerpt' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            // SEO Fields
            'seo_focus_keyword' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_og_title' => ['nullable', 'string', 'max:255'],
            'seo_og_description' => ['nullable', 'string', 'max:500'],
            'seo_og_image' => ['nullable', 'string'],
            'seo_robots_index' => ['boolean'],
            'seo_robots_follow' => ['boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            // Ensure unique slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Post::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = "{$originalSlug}-{$counter}";
                $counter++;
            }
        }

        $validated['user_id'] = $request->user()->id;
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        if (! empty($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagItem) {
                if (is_numeric($tagItem)) {
                    $tagIds[] = (int) $tagItem;
                } elseif (is_string($tagItem)) {
                    $createdTag = Tag::firstOrCreate(
                        ['name' => $tagItem],
                        ['slug' => Str::slug($tagItem)]
                    );
                    $tagIds[] = $createdTag->id;
                }
            }
            $post->tags()->sync($tagIds);
        }

        // Create initial revision
        PostRevision::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'title' => $post->title,
            'editor_type' => $post->editor_type,
            'classic_content' => $post->classic_content,
            'builder_json' => $post->builder_json,
            'summary' => 'Initial creation',
        ]);

        return redirect()->route('admin.posts.edit', $post->id)
            ->with('success', ucfirst($post->type) . ' created successfully.');
    }

    public function edit(Post $post, Request $request): Response
    {
        $user = $request->user();
        if ($user->role === 'author' && $post->user_id !== $user->id) {
            abort(403, 'You can only edit your own content.');
        }

        $post->load(['category', 'tags', 'revisions.user', 'featuredMedia']);

        return Inertia::render('Admin/Posts/Editor', [
            'post' => $post,
            'type' => $post->type,
            'default_editor' => $post->editor_type,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Post $post, Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->role === 'author' && $post->user_id !== $user->id) {
            abort(403, 'You can only edit your own content.');
        }

        $request->merge([
            'slug' => Str::slug((string) ($request->input('slug') ?: $request->input('title'))),
        ]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug,' . $post->id],
            'editor_type' => ['required', 'in:classic,builder'],
            'classic_content' => ['nullable', 'string'],
            'builder_json' => ['nullable', 'array'],
            'status' => ['required', 'in:draft,published'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'featured_image' => ['nullable', 'string'],
            'featured_image_id' => ['nullable', 'exists:media,id'],
            'excerpt' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            // SEO Fields
            'seo_focus_keyword' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_og_title' => ['nullable', 'string', 'max:255'],
            'seo_og_description' => ['nullable', 'string', 'max:500'],
            'seo_og_image' => ['nullable', 'string'],
            'seo_robots_index' => ['boolean'],
            'seo_robots_follow' => ['boolean'],
        ]);

        if ($validated['status'] === 'published' && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        if (isset($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagItem) {
                if (is_numeric($tagItem)) {
                    $tagIds[] = (int) $tagItem;
                } elseif (is_string($tagItem)) {
                    $createdTag = Tag::firstOrCreate(
                        ['name' => $tagItem],
                        ['slug' => Str::slug($tagItem)]
                    );
                    $tagIds[] = $createdTag->id;
                }
            }
            $post->tags()->sync($tagIds);
        }

        // Save revision
        PostRevision::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'title' => $post->title,
            'editor_type' => $post->editor_type,
            'classic_content' => $post->classic_content,
            'builder_json' => $post->builder_json,
            'summary' => 'Updated at ' . now()->format('M d, H:i'),
        ]);

        return redirect()->back()
            ->with('success', ucfirst($post->type) . ' updated successfully.');
    }

    public function destroy(Post $post, Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->role === 'author' && $post->user_id !== $user->id) {
            abort(403, 'You do not have permission to delete this content.');
        }

        $type = $post->type;
        $post->delete();

        return redirect()->route('admin.posts.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' deleted successfully.');
    }

    public function duplicate(Post $post, Request $request): RedirectResponse
    {
        $clone = $post->replicate();
        $clone->title = "{$post->title} (Copy)";
        $clone->slug = Str::slug($clone->title) . '-' . time();
        $clone->status = 'draft';
        $clone->user_id = $request->user()->id;
        $clone->save();

        // Copy tags
        $clone->tags()->sync($post->tags->pluck('id')->toArray());

        return redirect()->route('admin.posts.edit', $clone->id)
            ->with('success', 'Post duplicated as draft.');
    }
}

