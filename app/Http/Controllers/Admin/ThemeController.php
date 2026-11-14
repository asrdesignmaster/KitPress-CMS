<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ThemeController extends Controller
{
    public static function defaultGeneral(): array
    {
        return [
            'colors' => [
                'primary' => '#4f46e5',
                'secondary' => '#0f172a',
                'background' => '#f8fafc',
                'surface' => '#ffffff',
                'text' => '#1e293b',
                'accent' => '#6366f1',
            ],
            'typography' => [
                'body_font' => 'Instrument Sans, Inter, sans-serif',
                'heading_font' => 'Instrument Sans, Inter, sans-serif',
                'base_size' => '16px',
                'heading_weight' => '700',
                'line_height' => '1.6',
                'elements' => [
                    'p' => [
                        'font_family' => 'default',
                        'color' => '',
                        'font_size' => '16px',
                        'font_weight' => 'default',
                        'line_height' => '1.6',
                    ],
                    'h1' => [
                        'font_family' => 'default',
                        'color' => '',
                        'font_size' => '2.5em',
                        'font_weight' => '800',
                        'line_height' => '1.2',
                    ],
                    'h2' => [
                        'font_family' => 'default',
                        'color' => '',
                        'font_size' => '2em',
                        'font_weight' => '700',
                        'line_height' => '1.25',
                    ],
                    'h3' => [
                        'font_family' => 'default',
                        'color' => '',
                        'font_size' => '1.5em',
                        'font_weight' => '700',
                        'line_height' => '1.3',
                    ],
                    'h4' => [
                        'font_family' => 'default',
                        'color' => '',
                        'font_size' => '1.25em',
                        'font_weight' => '600',
                        'line_height' => '1.35',
                    ],
                ],
            ],
            'custom_css' => '',
        ];
    }

    public static function defaultButtons(array $colors = []): array
    {
        $primary = $colors['primary'] ?? '#4f46e5';
        $secondary = $colors['secondary'] ?? '#0f172a';
        $accent = $colors['accent'] ?? '#6366f1';

        return [
            'shape' => [
                'border_radius' => '8px',
                'padding_x' => '18px',
                'padding_y' => '9px',
                'font_weight' => '600',
            ],
            'variants' => [
                'primary' => [
                    'normal' => [
                        'bg_type' => 'solid',
                        'bg_color' => $primary,
                        'grad_start' => $primary,
                        'grad_end' => $accent,
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => $primary,
                    ],
                    'hover' => [
                        'bg_type' => 'solid',
                        'bg_color' => $accent,
                        'grad_start' => $primary,
                        'grad_end' => $secondary,
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => $accent,
                    ],
                ],
                'secondary' => [
                    'normal' => [
                        'bg_type' => 'solid',
                        'bg_color' => $secondary,
                        'grad_start' => $secondary,
                        'grad_end' => $primary,
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => $secondary,
                    ],
                    'hover' => [
                        'bg_type' => 'solid',
                        'bg_color' => $primary,
                        'grad_start' => $secondary,
                        'grad_end' => $accent,
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => $primary,
                    ],
                ],
                'accent' => [
                    'normal' => [
                        'bg_type' => 'solid',
                        'bg_color' => 'transparent',
                        'grad_start' => $accent,
                        'grad_end' => $primary,
                        'grad_angle' => '135deg',
                        'text_color' => $accent,
                        'border_color' => $accent,
                    ],
                    'hover' => [
                        'bg_type' => 'solid',
                        'bg_color' => $accent,
                        'grad_start' => $accent,
                        'grad_end' => $primary,
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => $accent,
                    ],
                ],
            ],
        ];
    }

    public static function defaultHeader(): array
    {
        return [
            'layout_style' => 'style_1',
            'transparent' => false,
            'logo_text' => '',
            'logo_text_color' => '#0f172a',
            'transparent_logo_text_color' => '#ffffff',
            'transparent_nav_link_color' => '#ffffff',
            'logo_image' => '',
            'show_logo_icon' => true,
            'sticky' => true,
            'bg_color' => '#ffffff',
            'text_color' => '#0f172a',
            'nav_link_color' => '#334155',
            'nav_link_hover_color' => '#4f46e5',
            'nav_link_active_color' => '#4f46e5',
            'menu_bar_bg' => '#f8fafc',
            'menu_bar_border' => '#e2e8f0',
            'menu_bar_text' => '#1e293b',
            'nav_links' => [
                ['label' => 'Home', 'url' => '/', 'new_tab' => false],
                ['label' => 'About', 'url' => '/about', 'new_tab' => false],
            ],
            'cta_enabled' => true,
            'cta_text' => 'Admin Dashboard',
            'cta_url' => '/admin',
        ];
    }

    public static function defaultFooter(): array
    {
        return [
            'brand_enabled' => true,
            'brand_logo' => '',
            'brand_name' => '',
            'brand_description' => '<p>Building fast, modern web experiences with clean architecture.</p>',
            'bg_color' => '#ffffff',
            'text_color' => '#64748b',
            'copyright_text' => 'Build Smarter. Publish Faster.',
            'show_year' => true,
            'show_powered_by' => true,
            'contact_enabled' => false,
            'contact_title' => 'Contact Us',
            'contact_email' => '',
            'contact_phone' => '',
            'contact_address' => '',
            'social_links' => [],
            'columns' => [
                [
                    'title' => 'Navigation',
                    'links' => [
                        ['label' => 'Home', 'url' => '/'],
                        ['label' => 'Sitemap', 'url' => '/sitemap.xml'],
                    ],
                ],
                [
                    'title' => 'Platform',
                    'links' => [
                        ['label' => 'Admin Panel', 'url' => '/admin'],
                    ],
                ],
            ],
        ];
    }

    public static function defaultBlog(): array
    {
        return [
            'layout_style' => 'style_1',
        ];
    }

    public static function getThemeConfig(): array
    {
        $general = Setting::get('theme_general', []);
        $buttons = Setting::get('theme_buttons', []);
        $blog = Setting::get('theme_blog', []);
        $header = Setting::get('theme_header', []);
        $footer = Setting::get('theme_footer', []);

        $defaultGeneral = static::defaultGeneral();
        $resolvedColors = array_merge($defaultGeneral['colors'], is_array($general['colors'] ?? null) ? $general['colors'] : []);
        $defaultButtons = static::defaultButtons($resolvedColors);

        $mergedVariants = $defaultButtons['variants'];
        if (is_array($buttons['variants'] ?? null)) {
            foreach ($mergedVariants as $variantKey => $variantDefaults) {
                if (is_array($buttons['variants'][$variantKey] ?? null)) {
                    $mergedVariants[$variantKey] = [
                        'normal' => array_merge($variantDefaults['normal'], is_array($buttons['variants'][$variantKey]['normal'] ?? null) ? $buttons['variants'][$variantKey]['normal'] : []),
                        'hover' => array_merge($variantDefaults['hover'], is_array($buttons['variants'][$variantKey]['hover'] ?? null) ? $buttons['variants'][$variantKey]['hover'] : []),
                    ];
                }
            }
        }

        $mergedTypography = array_merge($defaultGeneral['typography'], is_array($general['typography'] ?? null) ? $general['typography'] : []);
        $mergedElements = $defaultGeneral['typography']['elements'];
        if (is_array($general['typography']['elements'] ?? null)) {
            foreach ($mergedElements as $tag => $elementDefaults) {
                if (is_array($general['typography']['elements'][$tag] ?? null)) {
                    $mergedElements[$tag] = array_merge($elementDefaults, $general['typography']['elements'][$tag]);
                }
            }
        }
        $mergedTypography['elements'] = $mergedElements;

        return [
            'general' => [
                'colors' => array_merge($defaultGeneral['colors'], is_array($general['colors'] ?? null) ? $general['colors'] : []),
                'typography' => $mergedTypography,
                'custom_css' => (string) ($general['custom_css'] ?? ''),
            ],
            'buttons' => [
                'shape' => array_merge($defaultButtons['shape'], is_array($buttons['shape'] ?? null) ? $buttons['shape'] : []),
                'variants' => $mergedVariants,
            ],
            'blog' => array_merge(static::defaultBlog(), is_array($blog) ? $blog : []),
            'header' => array_merge(static::defaultHeader(), is_array($header) ? $header : []),
            'footer' => array_merge(static::defaultFooter(), is_array($footer) ? $footer : []),
        ];
    }

    public function general(): Response
    {
        return Inertia::render('Admin/Theme/General', [
            'general' => static::getThemeConfig()['general'],
        ]);
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'colors' => ['required', 'array'],
            'colors.primary' => ['required', 'string', 'max:30'],
            'colors.secondary' => ['required', 'string', 'max:30'],
            'colors.background' => ['required', 'string', 'max:30'],
            'colors.surface' => ['required', 'string', 'max:30'],
            'colors.text' => ['required', 'string', 'max:30'],
            'colors.accent' => ['required', 'string', 'max:30'],
            'typography' => ['required', 'array'],
            'typography.body_font' => ['required', 'string', 'max:150'],
            'typography.heading_font' => ['required', 'string', 'max:150'],
            'typography.base_size' => ['required', 'string', 'max:20'],
            'typography.heading_weight' => ['required', 'string', 'max:10'],
            'typography.line_height' => ['required', 'string', 'max:10'],
            'typography.elements' => ['nullable', 'array'],
            'typography.elements.*.font_family' => ['nullable', 'string', 'max:150'],
            'typography.elements.*.color' => ['nullable', 'string', 'max:30'],
            'typography.elements.*.font_size' => ['nullable', 'string', 'max:20'],
            'typography.elements.*.font_weight' => ['nullable', 'string', 'max:20'],
            'typography.elements.*.line_height' => ['nullable', 'string', 'max:20'],
            'custom_css' => ['nullable', 'string', 'max:20000'],
        ]);

        Setting::set('theme_general', $validated, 'theme');

        return redirect()->back()->with('success', 'Theme General settings (Colors, Typography & Custom CSS) saved.');
    }

    public function buttons(): Response
    {
        return Inertia::render('Admin/Theme/Buttons', [
            'buttons' => static::getThemeConfig()['buttons'],
        ]);
    }

    public function updateButtons(Request $request): RedirectResponse
    {
        $stateRules = fn (string $prefix) => [
            "{$prefix}.bg_type" => ['required', 'in:solid,gradient'],
            "{$prefix}.bg_color" => ['required', 'string', 'max:30'],
            "{$prefix}.grad_start" => ['required', 'string', 'max:30'],
            "{$prefix}.grad_end" => ['required', 'string', 'max:30'],
            "{$prefix}.grad_angle" => ['required', 'string', 'max:20'],
            "{$prefix}.text_color" => ['required', 'string', 'max:30'],
            "{$prefix}.border_color" => ['required', 'string', 'max:30'],
        ];

        $rules = [
            'shape' => ['required', 'array'],
            'shape.border_radius' => ['required', 'string', 'max:20'],
            'shape.padding_x' => ['required', 'string', 'max:20'],
            'shape.padding_y' => ['required', 'string', 'max:20'],
            'shape.font_weight' => ['required', 'string', 'max:10'],
            'variants' => ['required', 'array'],
        ];

        foreach (['primary', 'secondary', 'accent'] as $variant) {
            $rules["variants.{$variant}"] = ['required', 'array'];
            $rules = array_merge($rules, $stateRules("variants.{$variant}.normal"), $stateRules("variants.{$variant}.hover"));
        }

        $validated = $request->validate($rules);
        Setting::set('theme_buttons', $validated, 'theme');

        return redirect()->back()->with('success', 'Theme Button styles saved.');
    }

    public function header(): Response
    {
        $pages = Post::published()->pages()->select('id', 'title', 'slug')->orderBy('title')->get();

        return Inertia::render('Admin/Theme/HeaderBuilder', [
            'header' => static::getThemeConfig()['header'],
            'pages' => $pages,
        ]);
    }

    public function updateHeader(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'layout_style' => ['required', 'in:style_1,style_2,style_3'],
            'transparent' => ['boolean'],
            'logo_text' => ['nullable', 'string', 'max:100'],
            'logo_text_color' => ['nullable', 'string', 'max:30'],
            'transparent_logo_text_color' => ['nullable', 'string', 'max:30'],
            'transparent_nav_link_color' => ['nullable', 'string', 'max:30'],
            'logo_image' => ['nullable', 'string', 'max:500'],
            'show_logo_icon' => ['boolean'],
            'sticky' => ['boolean'],
            'bg_color' => ['required', 'string', 'max:30'],
            'text_color' => ['required', 'string', 'max:30'],
            'nav_link_color' => ['nullable', 'string', 'max:30'],
            'nav_link_hover_color' => ['nullable', 'string', 'max:30'],
            'nav_link_active_color' => ['nullable', 'string', 'max:30'],
            'menu_bar_bg' => ['required', 'string', 'max:30'],
            'menu_bar_border' => ['required', 'string', 'max:30'],
            'menu_bar_text' => ['required', 'string', 'max:30'],
            'nav_links' => ['nullable', 'array'],
            'nav_links.*.label' => ['required', 'string', 'max:80'],
            'nav_links.*.url' => ['required', 'string', 'max:255'],
            'nav_links.*.new_tab' => ['boolean'],
            'cta_enabled' => ['boolean'],
            'cta_text' => ['nullable', 'string', 'max:60'],
            'cta_url' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['nav_links'] = $validated['nav_links'] ?? [];
        Setting::set('theme_header', $validated, 'theme');

        return redirect()->back()->with('success', 'Header Builder settings saved.');
    }

    public function footer(): Response
    {
        return Inertia::render('Admin/Theme/FooterBuilder', [
            'footer' => static::getThemeConfig()['footer'],
        ]);
    }

    public function updateFooter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand_enabled' => ['boolean'],
            'brand_logo' => ['nullable', 'string', 'max:500'],
            'brand_name' => ['nullable', 'string', 'max:100'],
            'brand_description' => ['nullable', 'string', 'max:5000'],
            'bg_color' => ['required', 'string', 'max:30'],
            'text_color' => ['required', 'string', 'max:30'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'show_year' => ['boolean'],
            'show_powered_by' => ['boolean'],
            'contact_enabled' => ['boolean'],
            'contact_title' => ['nullable', 'string', 'max:80'],
            'contact_email' => ['nullable', 'string', 'max:120'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.*.platform' => ['required', 'in:facebook,twitter,instagram,linkedin,youtube,github'],
            'social_links.*.url' => ['required', 'string', 'max:500'],
            'columns' => ['nullable', 'array'],
            'columns.*.title' => ['required', 'string', 'max:80'],
            'columns.*.links' => ['nullable', 'array'],
            'columns.*.links.*.label' => ['required', 'string', 'max:80'],
            'columns.*.links.*.url' => ['required', 'string', 'max:255'],
        ]);

        $validated['columns'] = $validated['columns'] ?? [];
        $validated['social_links'] = $validated['social_links'] ?? [];
        Setting::set('theme_footer', $validated, 'theme');

        return redirect()->back()->with('success', 'Footer Builder settings saved.');
    }

    public function blog(): Response
    {
        return Inertia::render('Admin/Theme/Blog', [
            'blog' => static::getThemeConfig()['blog'],
        ]);
    }

    public function updateBlog(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'layout_style' => ['required', 'in:style_1,style_2,style_3'],
        ]);

        Setting::set('theme_blog', $validated, 'theme');

        return redirect()->back()->with('success', 'Blog layout style updated.');
    }
}

