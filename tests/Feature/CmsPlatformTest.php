<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostRevision;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $editor;
    protected User $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@test.test',
            'password' => Hash::make('password'),
            'role' => 'editor',
        ]);

        $this->author = User::create([
            'name' => 'Author User',
            'email' => 'author@test.test',
            'password' => Hash::make('password'),
            'role' => 'author',
        ]);
    }

    public function test_public_homepage_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_public_classic_post_renders_with_seo_meta(): void
    {
        $post = Post::create([
            'user_id' => $this->admin->id,
            'title' => 'High Performance Architecture',
            'slug' => 'high-performance-architecture',
            'type' => 'post',
            'editor_type' => 'classic',
            'classic_content' => '<p>Modern reactive content without WordPress legacy baggage.</p>',
            'status' => 'published',
            'published_at' => now(),
            'seo_focus_keyword' => 'Performance',
            'seo_title' => 'High Performance Architecture - CMS Guide',
            'seo_description' => 'Detailed guide on high performance CMS architectures.',
            'seo_robots_index' => true,
        ]);

        $response = $this->get('/high-performance-architecture');
        $response->assertStatus(200);
    }

    public function test_public_visual_builder_page_renders_layout_tree(): void
    {
        $page = Post::create([
            'user_id' => $this->admin->id,
            'title' => 'About Visual Engine',
            'slug' => 'about-visual-engine',
            'type' => 'page',
            'editor_type' => 'builder',
            'builder_json' => [
                [
                    'id' => 'sec_1',
                    'name' => 'Hero',
                    'backgroundColor' => '#0f172a',
                    'columns' => [
                        [
                            'id' => 'col_1',
                            'width' => '100%',
                            'widgets' => [
                                [
                                    'id' => 'w_1',
                                    'type' => 'heading',
                                    'content' => 'Visual Builder Running Smoothly',
                                    'tag' => 'h1',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get('/about-visual-engine');
        $response->assertStatus(200);
    }

    public function test_dynamic_sitemap_xml_contains_published_urls(): void
    {
        Post::create([
            'user_id' => $this->admin->id,
            'title' => 'Sitemap Indexed Post',
            'slug' => 'sitemap-indexed-post',
            'status' => 'published',
            'seo_robots_index' => true,
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('sitemap-indexed-post', $response->getContent());
    }

    public function test_dynamic_robots_txt(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $this->assertStringContainsString('Disallow: /admin/', $response->getContent());
        $this->assertStringContainsString('Sitemap:', $response->getContent());
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_author_cannot_access_site_optimization_settings(): void
    {
        $response = $this->actingAs($this->author)->get('/admin/settings/optimization');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_optimization_settings(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings/optimization');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_post_with_dual_mode_and_seo(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/posts', [
            'title' => 'Creating Post with TipTap & SEO Suite',
            'type' => 'post',
            'editor_type' => 'classic',
            'classic_content' => '<h2>Testing content</h2><p>Article body</p>',
            'status' => 'published',
            'seo_focus_keyword' => 'Testing',
            'seo_title' => 'Testing SEO Title',
            'seo_description' => 'Meta description for testing SEO suite.',
            'seo_robots_index' => true,
            'seo_robots_follow' => true,
            'tags' => ['Laravel', 'Vue 3', 'SEO'],
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Creating Post with TipTap & SEO Suite',
            'slug' => 'creating-post-with-tiptap-seo-suite',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('post_revisions', [
            'title' => 'Creating Post with TipTap & SEO Suite',
        ]);
    }

    public function test_media_service_converts_upload_to_webp_and_sizes(): void
    {
        Storage::fake('public');

        // Create a fake test image
        $file = UploadedFile::fake()->image('hero-banner.jpg', 800, 600);

        $mediaService = app(MediaService::class);
        $media = $mediaService->processUpload($file, $this->admin, 'Hero Alt Text');

        $this->assertNotNull($media->id);
        $this->assertEquals('image/webp', $media->mime_type);
        $this->assertStringEndsWith('.webp', $media->file_name);
        $this->assertArrayHasKey('thumbnail', $media->sizes);
        $this->assertArrayHasKey('original', $media->sizes);

        Storage::disk('public')->assertExists($media->webp_path);
    }

    public function test_optimization_purge_revisions(): void
    {
        $post = Post::create([
            'user_id' => $this->admin->id,
            'title' => 'Post with Revisions',
            'slug' => 'post-with-revisions',
            'status' => 'draft',
        ]);

        PostRevision::create([
            'post_id' => $post->id,
            'user_id' => $this->admin->id,
            'title' => 'Snapshot 1',
            'summary' => 'Auto-save',
        ]);

        $this->assertDatabaseCount('post_revisions', 1);

        $response = $this->actingAs($this->admin)->post('/admin/settings/optimization/purge-revisions');
        $response->assertRedirect();

        $this->assertDatabaseCount('post_revisions', 0);
    }
}

