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
            ],
            'custom_css' => '',
        ];
    }

    public static function defaultButtons(): array
    {
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
                        'bg_color' => '#4f46e5',
                        'grad_start' => '#4f46e5',
                        'grad_end' => '#7c3aed',
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => '#4f46e5',
                    ],
                    'hover' => [
                        'bg_type' => 'gradient',
                        'bg_color' => '#4338ca',
                        'grad_start' => '#4338ca',
                        'grad_end' => '#6d28d9',
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => '#4338ca',
                    ],
                ],
                'secondary' => [
                    'normal' => [
                        'bg_type' => 'solid',
                        'bg_color' => '#0f172a',
                        'grad_start' => '#0f172a',
                        'grad_end' => '#334155',
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => '#0f172a',
                    ],
                    'hover' => [
                        'bg_type' => 'solid',
                        'bg_color' => '#1e293b',
                        'grad_start' => '#1e293b',
                        'grad_end' => '#475569',
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => '#1e293b',
                    ],
                ],
                'accent' => [
                    'normal' => [
                        'bg_type' => 'solid',
                        'bg_color' => 'transparent',
                        'grad_start' => '#ec4899',
                        'grad_end' => '#f43f5e',
                        'grad_angle' => '135deg',
                        'text_color' => '#4f46e5',
                        'border_color' => '#4f46e5',
                    ],
                    'hover' => [
                        'bg_type' => 'gradient',
                        'bg_color' => '#4f46e5',
                        'grad_start' => '#4f46e5',
                        'grad_end' => '#06b6d4',
                        'grad_angle' => '135deg',
                        'text_color' => '#ffffff',
                        'border_color' => '#4f46e5',
                    ],
                ],
            ],
        ];
    }

    public static function defaultHeader(): array
    {
        return [
            'logo_text' => '',
            'logo_image' => '',
            'show_logo_icon' => true,
            'sticky' => true,
            'bg_color' => '#ffffff',
            'text_color' => '#0f172a',
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
            'bg_color' => '#ffffff',
            'text_color' => '#64748b',
            'copyright_text' => 'High-Performance Laravel + Vue CMS.',
            'show_year' => true,
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

    public static function getThemeConfig(): array
    {
        $general = Setting::get('theme_general', []);
        $buttons = Setting::get('theme_buttons', []);
        $header = Setting::get('theme_header', []);
        $footer = Setting::get('theme_footer', []);

        $defaultGeneral = static::defaultGeneral();
        $defaultButtons = static::defaultButtons();

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

        return [
            'general' => [
                'colors' => array_merge($defaultGeneral['colors'], is_array($general['colors'] ?? null) ? $general['colors'] : []),
                'typography' => array_merge($defaultGeneral['typography'], is_array($general['typography'] ?? null) ? $general['typography'] : []),
                'custom_css' => (string) ($general['custom_css'] ?? ''),
            ],
            'buttons' => [
                'shape' => array_merge($defaultButtons['shape'], is_array($buttons['shape'] ?? null) ? $buttons['shape'] : []),
                'variants' => $mergedVariants,
            ],
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
            'logo_text' => ['nullable', 'string', 'max:100'],
            'logo_image' => ['nullable', 'string', 'max:500'],
            'show_logo_icon' => ['boolean'],
            'sticky' => ['boolean'],
            'bg_color' => ['required', 'string', 'max:30'],
            'text_color' => ['required', 'string', 'max:30'],
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
            'bg_color' => ['required', 'string', 'max:30'],
            'text_color' => ['required', 'string', 'max:30'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'show_year' => ['boolean'],
            'columns' => ['nullable', 'array'],
            'columns.*.title' => ['required', 'string', 'max:80'],
            'columns.*.links' => ['nullable', 'array'],
            'columns.*.links.*.label' => ['required', 'string', 'max:80'],
            'columns.*.links.*.url' => ['required', 'string', 'max:255'],
        ]);

        $validated['columns'] = $validated['columns'] ?? [];
        Setting::set('theme_footer', $validated, 'theme');

        return redirect()->back()->with('success', 'Footer Builder settings saved.');
    }
}

