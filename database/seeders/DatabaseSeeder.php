<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Core Users with RBAC
        $admin = User::firstOrCreate(
            ['email' => 'admin@cms.test'],
            [
                'name' => 'Altamash (Admin)',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'bio' => 'System Administrator & Head Architect',
            ]
        );

        $editor = User::firstOrCreate(
            ['email' => 'editor@cms.test'],
            [
                'name' => 'Sarah Connor (Editor)',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'bio' => 'Lead Content Strategist & Reviewer',
            ]
        );

        $author = User::firstOrCreate(
            ['email' => 'author@cms.test'],
            [
                'name' => 'John Doe (Author)',
                'password' => Hash::make('password'),
                'role' => 'author',
                'bio' => 'Contributing Technical Writer',
            ]
        );

        // 2. Initial Taxonomies
        $catEngineering = Category::create([
            'name' => 'Engineering',
            'slug' => 'engineering',
            'description' => 'Modern backend and architectural articles.',
        ]);

        $catDesign = Category::create([
            'name' => 'Product Design',
            'slug' => 'product-design',
            'description' => 'User experience and interactive UI systems.',
        ]);

        $catPerformance = Category::create([
            'name' => 'High Performance',
            'slug' => 'high-performance',
            'description' => 'Speed optimization, WebP, and caching strategies.',
        ]);

        $tagVue = Tag::create(['name' => 'Vue 3', 'slug' => 'vue-3']);
        $tagLaravel = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $tagSeo = Tag::create(['name' => 'SEO Suite', 'slug' => 'seo-suite']);
        $tagSpeed = Tag::create(['name' => 'WebP Speed', 'slug' => 'webp-speed']);

        // 3. Sample Classic TipTap Post
        $postClassic = Post::create([
            'user_id' => $admin->id,
            'category_id' => $catPerformance->id,
            'title' => 'Building a Zero-Overhead Modern CMS with Laravel and Vue 3',
            'slug' => 'building-a-zero-overhead-modern-cms',
            'type' => 'post',
            'editor_type' => 'classic',
            'classic_content' => '<h2>The Death of Legacy Hook Pollution</h2><p>Traditional WordPress architectures suffer from thousands of dynamic hooks (<code>add_action</code>, <code>add_filter</code>), bloated options tables, and memory bloat. By combining Laravel 11 with Vue 3 and Inertia, we create a hyper-responsive single-page administrative experience that retains familiar publishing workflows without technical debt.</p><h3>Automatic WebP Transformation</h3><p>Every image dragged into our Media Library is intercepted server-side, converted into lightweight WebP format, and automatically indexed with responsive sizes (Thumbnail, Medium, Large). This guarantees sub-second page loads and near-perfect Google Core Web Vitals.</p><blockquote>"Simplicity is prerequisite for reliability." — Edsger W. Dijkstra</blockquote><p>Our integrated SEO suite continuously evaluates content density, keyword placement, and OpenGraph metadata in real-time, right inside the editor.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(2),
            'featured_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80',
            'excerpt' => 'Discover how modern reactive architectures eliminate legacy CMS bottlenecks while providing automated WebP optimization and Elementor-grade visual design.',
            // SEO
            'seo_focus_keyword' => 'Modern CMS',
            'seo_title' => 'Building a Zero-Overhead Modern CMS - Architecture Guide',
            'seo_description' => 'Discover how modern reactive architectures eliminate legacy CMS bottlenecks while providing automated WebP optimization and Elementor-grade visual design.',
            'seo_og_title' => 'Building a Zero-Overhead Modern CMS with Laravel & Vue 3',
            'seo_og_description' => 'Step-by-step breakdown of how this custom CMS replaces legacy WordPress bloat with modern elegance.',
            'seo_og_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80',
            'seo_robots_index' => true,
            'seo_robots_follow' => true,
        ]);
        $postClassic->tags()->sync([$tagLaravel->id, $tagVue->id, $tagSpeed->id]);

        // 4. Sample Elementor Pro-Style Visual Builder Page
        $builderTree = [
            [
                'id' => 'sec_hero_1',
                'name' => 'Hero Banner Section',
                'backgroundColor' => '#0f172a',
                'textColor' => '#ffffff',
                'paddingTop' => '64px',
                'paddingBottom' => '64px',
                'columns' => [
                    [
                        'id' => 'col_hero_left',
                        'width' => '60%',
                        'widgets' => [
                            [
                                'id' => 'w_head_1',
                                'type' => 'heading',
                                'content' => 'High-Performance CMS Built for Tomorrow',
                                'tag' => 'h1',
                                'fontSize' => '40px',
                                'color' => '#ffffff',
                                'textAlign' => 'left',
                                'marginBottom' => '16px',
                            ],
                            [
                                'id' => 'w_text_1',
                                'type' => 'text',
                                'content' => 'Replaces legacy WordPress overhead with clean Laravel, Vue 3, automated WebP image processing, and a visual tree layout builder.',
                                'fontSize' => '18px',
                                'color' => '#94a3b8',
                                'textAlign' => 'left',
                                'lineHeight' => '1.7',
                                'marginBottom' => '24px',
                            ],
                            [
                                'id' => 'w_btn_1',
                                'type' => 'button',
                                'content' => 'Explore Administration',
                                'url' => '/admin',
                                'buttonStyle' => 'primary',
                                'backgroundColor' => '#4f46e5',
                                'textColor' => '#ffffff',
                                'borderRadius' => '8px',
                                'textAlign' => 'left',
                            ],
                        ],
                    ],
                    [
                        'id' => 'col_hero_right',
                        'width' => '40%',
                        'widgets' => [
                            [
                                'id' => 'w_img_hero',
                                'type' => 'image',
                                'url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
                                'alt' => 'Modern Analytics Dashboard',
                                'borderRadius' => '12px',
                                'textAlign' => 'center',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'sec_features_2',
                'name' => 'Key Features Section',
                'backgroundColor' => '#ffffff',
                'textColor' => '#1e293b',
                'paddingTop' => '56px',
                'paddingBottom' => '56px',
                'columns' => [
                    [
                        'id' => 'col_feat_1',
                        'width' => '33.33%',
                        'widgets' => [
                            [
                                'id' => 'w_f1_head',
                                'type' => 'heading',
                                'content' => 'Dual Content Mode',
                                'tag' => 'h3',
                                'fontSize' => '22px',
                                'color' => '#1e293b',
                                'textAlign' => 'center',
                                'marginBottom' => '8px',
                            ],
                            [
                                'id' => 'w_f1_txt',
                                'type' => 'text',
                                'content' => 'Seamlessly switch between ProseMirror TipTap editor and Elementor Pro tree builder with live property inspector.',
                                'fontSize' => '15px',
                                'color' => '#64748b',
                                'textAlign' => 'center',
                            ],
                        ],
                    ],
                    [
                        'id' => 'col_feat_2',
                        'width' => '33.33%',
                        'widgets' => [
                            [
                                'id' => 'w_f2_head',
                                'type' => 'heading',
                                'content' => 'Server WebP Engine',
                                'tag' => 'h3',
                                'fontSize' => '22px',
                                'color' => '#1e293b',
                                'textAlign' => 'center',
                                'marginBottom' => '8px',
                            ],
                            [
                                'id' => 'w_f2_txt',
                                'type' => 'text',
                                'content' => 'Automatic backend interception, compression, and generation of multi-resolution WebP responsive sets.',
                                'fontSize' => '15px',
                                'color' => '#64748b',
                                'textAlign' => 'center',
                            ],
                        ],
                    ],
                    [
                        'id' => 'col_feat_3',
                        'width' => '33.33%',
                        'widgets' => [
                            [
                                'id' => 'w_f3_head',
                                'type' => 'heading',
                                'content' => 'Built-In SEO Suite',
                                'tag' => 'h3',
                                'fontSize' => '22px',
                                'color' => '#1e293b',
                                'textAlign' => 'center',
                                'marginBottom' => '8px',
                            ],
                            [
                                'id' => 'w_f3_txt',
                                'type' => 'text',
                                'content' => 'Yoast-style live analysis checklist, keyword density metrics, OpenGraph cards, and dynamic XML sitemap generation.',
                                'fontSize' => '15px',
                                'color' => '#64748b',
                                'textAlign' => 'center',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $pageBuilder = Post::create([
            'user_id' => $admin->id,
            'title' => 'About Platform Architecture',
            'slug' => 'about',
            'type' => 'page',
            'editor_type' => 'builder',
            'builder_json' => $builderTree,
            'status' => 'published',
            'published_at' => now()->subDay(),
            'excerpt' => 'Learn how this high-performance CMS platform was constructed from the ground up using Laravel, Vue 3, and a client-side visual tree layout engine.',
            'seo_focus_keyword' => 'Platform Architecture',
            'seo_title' => 'About Platform Architecture - Laravel & Vue 3 CMS',
            'seo_description' => 'Explore the technical architecture behind our Elementor-style visual builder and automated WebP media processing engine.',
            'seo_og_title' => 'Modern CMS Platform Architecture',
            'seo_og_description' => 'Learn about our tree-based visual layout engine and high-performance Laravel backend.',
            'seo_robots_index' => true,
            'seo_robots_follow' => true,
        ]);

        // 5. Global Settings
        Setting::set('site_name', 'Modern CMS', 'general');
        Setting::set('site_tagline', 'High-Performance Laravel + Vue CMS with Visual Builder', 'general');
        Setting::set('lazy_loading', true, 'optimization');
        Setting::set('response_compression', true, 'optimization');
        Setting::set('route_caching', false, 'optimization');
        Setting::set('config_caching', false, 'optimization');
    }
}
