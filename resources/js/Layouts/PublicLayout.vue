<template>
  <div
    class="min-h-screen flex flex-col antialiased public-theme-root"
    :style="rootThemeStyles"
  >
    <!-- Inject Theme Custom CSS & Heading Typography -->
    <component :is="'style'" v-if="compiledThemeCss">{{ compiledThemeCss }}</component>

    <!-- Dynamic Header from Theme Header Builder -->
    <header
      :class="[
        headerConfig.transparent
          ? (headerConfig.sticky ? 'fixed top-0 left-0 right-0 z-30' : 'absolute top-0 left-0 right-0 z-30')
          : (headerConfig.sticky ? 'sticky top-0 z-30 border-b border-slate-200/80 shadow-2xs' : 'relative z-30 border-b border-slate-200/80 shadow-2xs')
      ]"
      :style="{
        backgroundColor: headerConfig.transparent ? 'transparent' : headerConfig.bg_color,
        color: headerConfig.text_color,
      }"
    >
      <!-- Top Bar (Logo, optional Center Menu for Style 2, Right Menu/CTA) -->
      <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
        <Link href="/" class="flex items-center space-x-2 font-bold text-lg tracking-tight shrink-0" :style="{ color: headerConfig.text_color }">
          <img
            v-if="headerConfig.logo_image"
            :src="headerConfig.logo_image"
            :alt="headerConfig.logo_text || siteName"
            class="h-8 w-auto object-contain"
          />
          <svg
            v-else-if="headerConfig.show_logo_icon"
            class="w-6 h-6"
            :style="{ color: generalConfig.colors.primary }"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
          <span>{{ headerConfig.logo_text || siteName }}</span>
        </Link>

        <!-- Style 2: Centered Menu -->
        <nav v-if="headerConfig.layout_style === 'style_2'" class="flex-1 flex items-center justify-center space-x-5 overflow-x-auto">
          <template v-for="(link, idx) in headerConfig.nav_links" :key="idx">
            <a
              v-if="link.new_tab || (link.url && link.url.startsWith('http'))"
              :href="link.url"
              :target="link.new_tab ? '_blank' : undefined"
              :rel="link.new_tab ? 'noopener noreferrer' : undefined"
              class="text-xs font-semibold opacity-80 hover:opacity-100 transition whitespace-nowrap"
            >
              {{ link.label }}
            </a>
            <Link
              v-else
              :href="link.url || '/'"
              class="text-xs font-semibold opacity-80 hover:opacity-100 transition whitespace-nowrap"
            >
              {{ link.label }}
            </Link>
          </template>
        </nav>

        <!-- Right Nav (Style 1) + Header Extra + CTA -->
        <div class="flex items-center space-x-4 shrink-0">
          <nav v-if="headerConfig.layout_style === 'style_1'" class="flex items-center space-x-4 overflow-x-auto">
            <template v-for="(link, idx) in headerConfig.nav_links" :key="idx">
              <a
                v-if="link.new_tab || (link.url && link.url.startsWith('http'))"
                :href="link.url"
                :target="link.new_tab ? '_blank' : undefined"
                :rel="link.new_tab ? 'noopener noreferrer' : undefined"
                class="text-xs font-semibold opacity-80 hover:opacity-100 transition whitespace-nowrap"
              >
                {{ link.label }}
              </a>
              <Link
                v-else
                :href="link.url || '/'"
                class="text-xs font-semibold opacity-80 hover:opacity-100 transition whitespace-nowrap"
              >
                {{ link.label }}
              </Link>
            </template>
          </nav>

          <slot name="header-extra" />

          <Link
            v-if="headerConfig.cta_enabled && headerConfig.cta_text"
            :href="headerConfig.cta_url || '/admin'"
            class="theme-btn-primary text-xs shadow-xs whitespace-nowrap"
          >
            {{ headerConfig.cta_text }}
          </Link>
        </div>
      </div>

      <!-- Style 3: Full-Width Menu Bar Below Logo/Name -->
      <div
        v-if="headerConfig.layout_style === 'style_3'"
        :style="{
          backgroundColor: headerConfig.menu_bar_bg,
          borderTopColor: headerConfig.menu_bar_border,
          borderBottomColor: headerConfig.menu_bar_border,
          color: headerConfig.menu_bar_text,
        }"
        class="w-full border-t border-b"
      >
        <nav class="max-w-6xl mx-auto px-4 py-2.5 flex items-center justify-center space-x-6 overflow-x-auto">
          <template v-for="(link, idx) in headerConfig.nav_links" :key="idx">
            <a
              v-if="link.new_tab || (link.url && link.url.startsWith('http'))"
              :href="link.url"
              :target="link.new_tab ? '_blank' : undefined"
              :rel="link.new_tab ? 'noopener noreferrer' : undefined"
              class="text-xs font-semibold opacity-85 hover:opacity-100 transition whitespace-nowrap"
            >
              {{ link.label }}
            </a>
            <Link
              v-else
              :href="link.url || '/'"
              class="text-xs font-semibold opacity-85 hover:opacity-100 transition whitespace-nowrap"
            >
              {{ link.label }}
            </Link>
          </template>
        </nav>
      </div>
    </header>

    <!-- Main Page Content -->
    <slot />

    <!-- Dynamic Footer from Theme Footer Builder -->
    <footer
      :style="{ backgroundColor: footerConfig.bg_color, color: footerConfig.text_color }"
      class="border-t border-slate-200/80 py-10 text-xs mt-12"
    >
      <div class="max-w-6xl mx-auto px-4 space-y-8">
        <div v-if="footerConfig.columns && footerConfig.columns.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
          <div v-for="(col, cIdx) in footerConfig.columns" :key="cIdx" class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider">{{ col.title }}</h4>
            <ul class="space-y-2 opacity-85">
              <li v-for="(link, lIdx) in col.links" :key="lIdx">
                <a v-if="link.url && (link.url.startsWith('http') || link.url.endsWith('.xml'))" :href="link.url" target="_blank" class="hover:underline">
                  {{ link.label }}
                </a>
                <Link v-else :href="link.url || '/'" class="hover:underline">
                  {{ link.label }}
                </Link>
              </li>
            </ul>
          </div>
        </div>

        <div class="pt-6 border-t border-current/15 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p>
            &copy; <span v-if="footerConfig.show_year">{{ new Date().getFullYear() }}</span> {{ siteName }}.
            {{ footerConfig.copyright_text }}
          </p>
          <div class="flex items-center space-x-4">
            <Link href="/" class="hover:underline">Home</Link>
            <a href="/sitemap.xml" target="_blank" class="hover:underline">Sitemap</a>
            <Link href="/admin" class="font-semibold hover:underline">Admin Panel</Link>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const siteName = computed(() => page.props.settings?.site_name || 'Modern CMS');

const generalConfig = computed(() => ({
  colors: {
    primary: '#4f46e5',
    secondary: '#0f172a',
    background: '#f8fafc',
    surface: '#ffffff',
    text: '#1e293b',
    accent: '#6366f1',
    ...(page.props.theme?.general?.colors || {}),
  },
  typography: {
    body_font: 'Instrument Sans, Inter, sans-serif',
    heading_font: 'Instrument Sans, Inter, sans-serif',
    base_size: '16px',
    heading_weight: '700',
    line_height: '1.6',
    ...(page.props.theme?.general?.typography || {}),
  },
  custom_css: page.props.theme?.general?.custom_css || '',
}));

const headerConfig = computed(() => ({
  layout_style: 'style_1',
  transparent: false,
  logo_text: '',
  logo_image: '',
  show_logo_icon: true,
  sticky: true,
  bg_color: '#ffffff',
  text_color: '#0f172a',
  menu_bar_bg: '#f8fafc',
  menu_bar_border: '#e2e8f0',
  menu_bar_text: '#1e293b',
  nav_links: [],
  cta_enabled: true,
  cta_text: 'Admin Dashboard',
  cta_url: '/admin',
  ...(page.props.theme?.header || {}),
}));

const footerConfig = computed(() => ({
  bg_color: '#ffffff',
  text_color: '#64748b',
  copyright_text: 'High-Performance Laravel + Vue CMS.',
  show_year: true,
  columns: [],
  ...(page.props.theme?.footer || {}),
}));

const rootThemeStyles = computed(() => ({
  '--theme-primary': generalConfig.value.colors.primary,
  '--theme-secondary': generalConfig.value.colors.secondary,
  '--theme-bg': generalConfig.value.colors.background,
  '--theme-surface': generalConfig.value.colors.surface,
  '--theme-text': generalConfig.value.colors.text,
  '--theme-accent': generalConfig.value.colors.accent,
  '--theme-font-body': generalConfig.value.typography.body_font,
  '--theme-font-heading': generalConfig.value.typography.heading_font,
  backgroundColor: generalConfig.value.colors.background,
  color: generalConfig.value.colors.text,
  fontFamily: generalConfig.value.typography.body_font,
  fontSize: generalConfig.value.typography.base_size,
  lineHeight: generalConfig.value.typography.line_height,
}));

const buttonsConfig = computed(() => page.props.theme?.buttons || null);

const compiledButtonsCss = computed(() => {
  const cfg = buttonsConfig.value;
  if (!cfg || !cfg.variants) return '';
  const shape = cfg.shape || {};
  const radius = shape.border_radius || '8px';
  const px = shape.padding_x || '18px';
  const py = shape.padding_y || '9px';
  const weight = shape.font_weight || '600';

  const stateBg = (s) =>
    s?.bg_type === 'gradient'
      ? `linear-gradient(${s.grad_angle || '135deg'}, ${s.grad_start}, ${s.grad_end})`
      : (s?.bg_color || 'transparent');

  return Object.entries(cfg.variants)
    .map(([key, v]) => {
      if (!v?.normal || !v?.hover) return '';
      return `
        .public-theme-root .theme-btn-${key} {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border-radius: ${radius};
          padding: ${py} ${px};
          font-weight: ${weight};
          border: 1px solid ${v.normal.border_color};
          background: ${stateBg(v.normal)};
          color: ${v.normal.text_color};
          transition: all 0.2s ease;
        }
        .public-theme-root .theme-btn-${key}:hover {
          border-color: ${v.hover.border_color};
          background: ${stateBg(v.hover)};
          color: ${v.hover.text_color};
        }
      `;
    })
    .join('\n');
});

const compiledThemeCss = computed(() => {
  const headingFont = generalConfig.value.typography.heading_font;
  const headingWeight = generalConfig.value.typography.heading_weight;
  const custom = generalConfig.value.custom_css || '';

  return `.public-theme-root h1, .public-theme-root h2, .public-theme-root h3, .public-theme-root h4 { font-family: ${headingFont}; font-weight: ${headingWeight}; }\n${compiledButtonsCss.value}\n${custom}`;
});
</script>

