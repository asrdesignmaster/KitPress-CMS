<template>
  <div
    class="min-h-screen flex flex-col antialiased public-theme-root"
    :style="rootThemeStyles"
  >
    <!-- Inject Selected Webfonts, Theme Typography, Buttons & Custom CSS -->
    <link v-if="bunnyFontsHref" rel="stylesheet" :href="bunnyFontsHref" />
    <component :is="'style'" v-if="compiledThemeCss">{{ compiledThemeCss }}</component>

    <!-- Dynamic Header from Theme Header Builder -->
    <header
      :class="[
        isTransparentHeader
          ? (headerConfig.sticky ? 'fixed top-0 left-0 right-0 z-30' : 'absolute top-0 left-0 right-0 z-30')
          : (headerConfig.sticky ? 'sticky top-0 z-30 border-b border-slate-200/80 shadow-2xs' : 'relative z-30 border-b border-slate-200/80 shadow-2xs')
      ]"
      :style="{
        backgroundColor: isTransparentHeader ? 'transparent' : headerConfig.bg_color,
        color: headerConfig.text_color,
        '--header-link-color': isTransparentHeader
          ? (headerConfig.transparent_nav_link_color || '#ffffff')
          : (headerConfig.nav_link_color || (headerConfig.layout_style === 'style_3' ? headerConfig.menu_bar_text : headerConfig.text_color)),
        '--header-link-hover-color': headerConfig.nav_link_hover_color || generalConfig.colors.primary || '#4f46e5',
        '--header-link-active-color': headerConfig.nav_link_active_color || headerConfig.nav_link_hover_color || generalConfig.colors.primary || '#4f46e5',
      }"
    >
      <!-- Top Bar (Logo, optional Center Menu for Style 2, Right Menu/CTA) -->
      <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
        <Link
          href="/"
          class="flex items-center space-x-2 font-bold text-lg tracking-tight shrink-0"
          :style="{
            color: isTransparentHeader
              ? (headerConfig.transparent_logo_text_color || '#ffffff')
              : (headerConfig.logo_text_color || headerConfig.text_color)
          }"
        >
          <img
            v-if="headerConfig.logo_image"
            :src="headerConfig.logo_image"
            :alt="headerConfig.logo_text || siteName"
            class="h-8 w-auto object-contain"
          />
          <KitPressLogo v-else-if="headerConfig.show_logo_icon" size-class="w-7 h-7 shrink-0" />
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
              class="header-nav-link text-xs font-semibold whitespace-nowrap"
            >
              {{ link.label }}
            </a>
            <Link
              v-else
              :href="link.url || '/'"
              :class="['header-nav-link text-xs font-semibold whitespace-nowrap', isNavLinkActive(link.url) ? 'is-active' : '']"
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
                class="header-nav-link text-xs font-semibold whitespace-nowrap"
              >
                {{ link.label }}
              </a>
              <Link
                v-else
                :href="link.url || '/'"
                :class="['header-nav-link text-xs font-semibold whitespace-nowrap', isNavLinkActive(link.url) ? 'is-active' : '']"
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
              class="header-nav-link text-xs font-semibold whitespace-nowrap"
            >
              {{ link.label }}
            </a>
            <Link
              v-else
              :href="link.url || '/'"
              :class="['header-nav-link text-xs font-semibold whitespace-nowrap', isNavLinkActive(link.url) ? 'is-active' : '']"
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
        <div
          v-if="footerConfig.brand_enabled || (footerConfig.columns && footerConfig.columns.length) || footerConfig.contact_enabled"
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8"
        >
          <!-- Default Brand Column -->
          <div v-if="footerConfig.brand_enabled" class="space-y-3">
            <Link href="/" class="inline-flex items-center space-x-2 font-bold text-sm">
              <img
                v-if="footerConfig.brand_logo"
                :src="footerConfig.brand_logo"
                :alt="footerConfig.brand_name || siteName"
                class="h-8 w-auto object-contain"
              />
              <span v-else>{{ footerConfig.brand_name || siteName }}</span>
            </Link>
            <div
              v-if="footerConfig.brand_description"
              class="max-w-none opacity-90"
              v-html="footerConfig.brand_description"
            ></div>
          </div>

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

          <!-- Contact & Social Icons Column -->
          <div v-if="footerConfig.contact_enabled" class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider">{{ footerConfig.contact_title || 'Contact Us' }}</h4>
            <ul class="space-y-2 opacity-85">
              <li v-if="footerConfig.contact_email">
                <a :href="`mailto:${footerConfig.contact_email}`" class="hover:underline">{{ footerConfig.contact_email }}</a>
              </li>
              <li v-if="footerConfig.contact_phone">
                <a :href="`tel:${footerConfig.contact_phone}`" class="hover:underline">{{ footerConfig.contact_phone }}</a>
              </li>
              <li v-if="footerConfig.contact_address" class="whitespace-pre-line">{{ footerConfig.contact_address }}</li>
            </ul>
            <div v-if="footerConfig.social_links && footerConfig.social_links.length" class="flex flex-wrap items-center gap-2.5 pt-1">
              <a
                v-for="(social, sIdx) in footerConfig.social_links"
                :key="sIdx"
                :href="social.url"
                target="_blank"
                rel="noopener noreferrer"
                :aria-label="social.platform"
                :title="social.platform"
                class="p-1.5 rounded-lg border border-current/20 opacity-80 hover:opacity-100 transition"
              >
                <SocialIcon :platform="social.platform" />
              </a>
            </div>
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
import KitPressLogo from '@/Components/KitPressLogo.vue';
import { getTypographyFontsHref, compileTypographyCss } from '@/utils/themeFonts';
import SocialIcon from '@/Components/SocialIcon.vue';

const page = usePage();
const siteName = computed(() => page.props.settings?.site_name || 'KitPress');

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
  logo_text_color: '',
  transparent_logo_text_color: '#ffffff',
  transparent_nav_link_color: '#ffffff',
  logo_image: '',
  show_logo_icon: true,
  sticky: true,
  bg_color: '#ffffff',
  text_color: '#0f172a',
  nav_link_color: '',
  nav_link_hover_color: '',
  nav_link_active_color: '',
  menu_bar_bg: '#f8fafc',
  menu_bar_border: '#e2e8f0',
  menu_bar_text: '#1e293b',
  nav_links: [],
  cta_enabled: true,
  cta_text: 'Admin Dashboard',
  cta_url: '/admin',
  ...(page.props.theme?.header || {}),
}));

const isHomePage = computed(() => {
  if (typeof page.props.isHome === 'boolean') {
    return page.props.isHome;
  }
  const path = (page.url || '/').split('?')[0].replace(/\/+$/, '') || '/';
  return path === '/';
});

const isTransparentHeader = computed(() => Boolean(headerConfig.value.transparent) && isHomePage.value);

const footerConfig = computed(() => ({
  brand_enabled: true,
  brand_logo: '',
  brand_name: '',
  brand_description: '',
  bg_color: '#ffffff',
  text_color: '#64748b',
  copyright_text: 'High-Performance Laravel + Vue CMS.',
  show_year: true,
  contact_enabled: false,
  contact_title: 'Contact Us',
  contact_email: '',
  contact_phone: '',
  contact_address: '',
  social_links: [],
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

const bunnyFontsHref = computed(() => getTypographyFontsHref(generalConfig.value.typography || {}));

const isNavLinkActive = (url) => {
  if (!url || url.startsWith('http')) return false;
  const currentPath = (page.url || '/').split('?')[0].replace(/\/+$/, '') || '/';
  const linkPath = url.split('?')[0].replace(/\/+$/, '') || '/';
  if (linkPath === '/') return currentPath === '/';
  return currentPath === linkPath || currentPath.startsWith(linkPath + '/');
};

const compiledThemeCss = computed(() => {
  const typo = generalConfig.value.typography || {};
  const colors = generalConfig.value.colors || {};
  const custom = generalConfig.value.custom_css || '';

  const elementRules = compileTypographyCss('.public-theme-root', typo, colors);
  return `${elementRules}\n${compiledButtonsCss.value}\n${custom}`;
});
</script>

<style scoped>
.header-nav-link {
  color: var(--header-link-color);
  transition: color 0.15s ease;
}

.header-nav-link:hover {
  color: var(--header-link-hover-color);
}

.header-nav-link.is-active {
  color: var(--header-link-active-color);
  font-weight: 700;
}
</style>

