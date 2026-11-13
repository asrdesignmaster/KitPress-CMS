<template>
  <AdminLayout>
    <div class="space-y-6 max-w-full">
      <!-- Header -->
      <div class="pb-4 border-b border-slate-200">
        <div class="flex items-center space-x-2">
          <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h14a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
            </svg>
          </span>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Theme &rsaquo; Header Builder</h1>
        </div>
        <p class="text-xs text-slate-500 mt-1">
          Customize your public site navigation bar, transparency, header layout style, logo, menu links, and call-to-action button.
        </p>
      </div>

      <!-- Live Header Preview (Top Full-Width) -->
      <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-500">
          <span>Live Header Preview</span>
          <span v-if="form.transparent" class="text-[10px] text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Transparent</span>
        </div>

        <div :class="form.transparent ? 'bg-gradient-to-r from-slate-800 to-indigo-900 pt-2 pb-6' : 'bg-slate-100'">
          <div
            :style="{
              backgroundColor: form.transparent ? 'transparent' : form.bg_color,
              color: form.text_color,
              '--header-link-color': form.transparent ? (form.transparent_nav_link_color || '#ffffff') : (form.nav_link_color || form.text_color),
              '--header-link-hover-color': form.nav_link_hover_color || '#4f46e5',
              '--header-link-active-color': form.nav_link_active_color || form.nav_link_hover_color || '#4f46e5',
            }"
            :class="form.transparent ? '' : 'border-b border-slate-200/70'"
          >
            <!-- Top Bar -->
            <div class="h-16 px-6 flex items-center justify-between gap-4">
              <div
                class="flex items-center space-x-2 font-bold text-base shrink-0"
                :style="{ color: form.transparent ? (form.transparent_logo_text_color || '#ffffff') : (form.logo_text_color || form.text_color) }"
              >
                <img v-if="form.logo_image" :src="form.logo_image" alt="Logo" class="h-8 w-auto object-contain" />
                <KitPressLogo v-else-if="form.show_logo_icon" size-class="w-6 h-6 shrink-0" />
                <span>{{ form.logo_text || $page.props.settings?.site_name || 'KitPress' }}</span>
              </div>

              <!-- Style 2 Centered Nav -->
              <div v-if="form.layout_style === 'style_2'" class="flex-1 flex items-center justify-center space-x-5 text-xs font-semibold overflow-hidden">
                <span
                  v-for="(link, idx) in form.nav_links"
                  :key="idx"
                  :class="['preview-nav-link truncate cursor-pointer', idx === 0 ? 'is-active' : '']"
                >
                  {{ link.label || 'Link' }}
                </span>
              </div>

              <!-- Right Side -->
              <div class="flex items-center space-x-4 text-xs font-semibold shrink-0">
                <template v-if="form.layout_style === 'style_1'">
                  <span
                    v-for="(link, idx) in form.nav_links"
                    :key="idx"
                    :class="['preview-nav-link cursor-pointer', idx === 0 ? 'is-active' : '']"
                  >
                    {{ link.label || 'Link' }}
                  </span>
                </template>
                <span
                  v-if="form.cta_enabled && form.cta_text"
                  class="px-3 py-1.5 rounded-lg text-white bg-indigo-600"
                >
                  {{ form.cta_text }}
                </span>
              </div>
            </div>

            <!-- Style 3 Full-Width Menu Bar Below -->
            <div
              v-if="form.layout_style === 'style_3'"
              :style="{
                backgroundColor: form.menu_bar_bg,
                borderTopColor: form.menu_bar_border,
                borderBottomColor: form.menu_bar_border,
                color: form.menu_bar_text,
                '--header-link-color': form.nav_link_color || form.menu_bar_text,
              }"
              class="w-full px-6 py-2.5 border-t border-b flex items-center justify-center space-x-6 text-xs font-semibold"
            >
              <span
                v-for="(link, idx) in form.nav_links"
                :key="idx"
                :class="['preview-nav-link cursor-pointer', idx === 0 ? 'is-active' : '']"
              >
                {{ link.label || 'Link' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Grid: Left Controls + Right Styles Panel -->
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        
        <!-- Left 7 Cols: Controls -->
        <form @submit.prevent="save" class="xl:col-span-7 space-y-6">
          <!-- Brand & Style -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-5 text-xs">
            <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Branding &amp; Appearance</h2>

            <!-- Brand Identity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Logo Text (optional override)</label>
                <input v-model="form.logo_text" type="text" placeholder="Uses Site Name if empty" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
              </div>

              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Logo Image URL</label>
                <div class="flex items-center space-x-2">
                  <input v-model="form.logo_image" type="text" placeholder="/media-files/..." class="flex-1 px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
                  <button type="button" @click="mediaModalOpen = true" class="px-3 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg font-semibold hover:bg-indigo-100">
                    Browse
                  </button>
                </div>
              </div>
            </div>

            <!-- Header & Logo Colors -->
            <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-4 space-y-3">
              <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Header &amp; Logo Colors</div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <ThemeColorPicker v-model="form.bg_color" label="Background" compact />
                <ThemeColorPicker v-model="form.text_color" label="Header Text" compact />
                <ThemeColorPicker v-model="form.logo_text_color" label="Logo Text" compact />
              </div>
            </div>

            <!-- Menu Link Colors -->
            <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-4 space-y-3">
              <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Menu Link Colors</div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <ThemeColorPicker v-model="form.nav_link_color" label="Normal" compact />
                <ThemeColorPicker v-model="form.nav_link_hover_color" label="Hover" compact />
                <ThemeColorPicker v-model="form.nav_link_active_color" label="Active" compact />
              </div>
            </div>

            <!-- Transparent, Sticky & Brand Icon Toggles -->
            <div class="flex flex-wrap items-center gap-3 pt-1">
              <label class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100/70 cursor-pointer transition">
                <input v-model="form.transparent" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
                <span class="font-semibold text-slate-700">Transparent Header <span class="text-[10px] font-normal text-slate-400">(Home page only)</span></span>
              </label>
              <label class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100/70 cursor-pointer transition">
                <input v-model="form.sticky" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
                <span class="font-semibold text-slate-700">Sticky Header on Scroll</span>
              </label>
              <label class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100/70 cursor-pointer transition">
                <input v-model="form.show_logo_icon" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
                <span class="font-semibold text-slate-700">Show Default Brand Icon</span>
              </label>
            </div>

            <!-- Transparent Header Colors (Home Page Only) -->
            <div v-if="form.transparent" class="bg-indigo-50/60 border border-indigo-200/80 rounded-xl p-4 space-y-3">
              <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">
                Transparent Header Colors (Home Page Only)
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <ThemeColorPicker v-model="form.transparent_logo_text_color" label="Logo Text (Transparent)" compact />
                <ThemeColorPicker v-model="form.transparent_nav_link_color" label="Menu Link (Transparent)" compact />
              </div>
            </div>

            <!-- Style 3 Menu Bar Colors -->
            <div v-if="form.layout_style === 'style_3'" class="pt-4 border-t border-slate-200 space-y-3">
              <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Style 3 &mdash; Full-Width Menu Bar Colors</h3>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <ThemeColorPicker v-model="form.menu_bar_bg" label="Menu Background" />
                <ThemeColorPicker v-model="form.menu_bar_border" label="Menu Border Color" />
                <ThemeColorPicker v-model="form.menu_bar_text" label="Menu Text Color" />
              </div>
            </div>
          </div>

          <!-- Navigation Menu Builder -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
              <div>
                <h2 class="text-sm font-bold text-slate-800">Navigation Menu Links</h2>
                <p class="text-[11px] text-slate-500">Add custom links or quick-add published pages.</p>
              </div>
              <div class="flex items-center space-x-2">
                <select
                  v-if="pages.length"
                  @change="quickAddPage($event)"
                  class="px-2.5 py-1.5 border border-slate-300 rounded-lg bg-slate-50 text-slate-700"
                >
                  <option value="">+ Quick Add Page...</option>
                  <option v-for="p in pages" :key="p.id" :value="p.id">{{ p.title }} (/{{ p.slug }})</option>
                </select>
                <button
                  type="button"
                  @click="addLink"
                  class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg font-semibold"
                >
                  + Add Link
                </button>
              </div>
            </div>

            <div v-if="form.nav_links.length === 0" class="py-6 text-center text-slate-400">
              No navigation links yet. Click "+ Add Link" to build your header menu.
            </div>

            <div class="space-y-2.5">
              <div
                v-for="(link, idx) in form.nav_links"
                :key="idx"
                class="p-3 bg-slate-50 border border-slate-200 rounded-lg flex flex-col sm:flex-row sm:items-center gap-2"
              >
                <input v-model="link.label" type="text" placeholder="Label (e.g. About)" required class="flex-1 px-2.5 py-1.5 border border-slate-300 rounded bg-white" />
                <input v-model="link.url" type="text" placeholder="/about or https://..." required class="flex-1 px-2.5 py-1.5 border border-slate-300 rounded bg-white font-mono" />
                <label class="flex items-center space-x-1.5 text-[11px] text-slate-600 px-2">
                  <input v-model="link.new_tab" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
                  <span>New Tab</span>
                </label>
                <div class="flex items-center space-x-1">
                  <button type="button" @click="moveLink(idx, -1)" :disabled="idx === 0" class="px-2 py-1 border rounded bg-white disabled:opacity-30">&uarr;</button>
                  <button type="button" @click="moveLink(idx, 1)" :disabled="idx === form.nav_links.length - 1" class="px-2 py-1 border rounded bg-white disabled:opacity-30">&darr;</button>
                  <button type="button" @click="form.nav_links.splice(idx, 1)" class="px-2 py-1 border border-rose-200 text-rose-600 rounded bg-white hover:bg-rose-50">&times;</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Header CTA Button -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
              <h2 class="text-sm font-bold text-slate-800">Action Button (CTA)</h2>
              <label class="flex items-center space-x-2 cursor-pointer">
                <input v-model="form.cta_enabled" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
                <span class="font-semibold text-slate-700">Enable Button</span>
              </label>
            </div>

            <div v-if="form.cta_enabled" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Button Text</label>
                <input v-model="form.cta_text" type="text" placeholder="Get Started" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
              </div>
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Button URL</label>
                <input v-model="form.cta_url" type="text" placeholder="/admin or /contact" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 font-mono" />
              </div>
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition disabled:opacity-50"
            >
              {{ form.processing ? 'Saving...' : 'Save Header Builder' }}
            </button>
          </div>
        </form>

        <!-- Right 5 Cols: Header Styles Selector & Live Preview -->
        <aside class="xl:col-span-5 xl:sticky xl:top-20 space-y-5">
          <!-- 3 Header Styles -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-3 text-xs">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2">
              Header Styles
            </h2>

            <div class="space-y-2.5">
              <!-- Style 1 -->
              <button
                type="button"
                @click="form.layout_style = 'style_1'"
                :class="form.layout_style === 'style_1' ? 'border-indigo-600 bg-indigo-50/70 ring-1 ring-indigo-600' : 'border-slate-200 bg-white hover:bg-slate-50'"
                class="w-full text-left p-3 rounded-xl border transition space-y-2"
              >
                <div class="flex items-center justify-between">
                  <span class="font-bold text-slate-800">Style 1 &mdash; Classic</span>
                  <span v-if="form.layout_style === 'style_1'" class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-full">Active</span>
                </div>
                <div class="p-2 bg-slate-100 rounded border border-slate-200 flex items-center justify-between text-[9px] text-slate-500">
                  <span class="font-bold text-slate-700">LOGO</span>
                  <div class="flex items-center space-x-2">
                    <span>Menu</span>
                    <span class="px-1.5 py-0.5 bg-indigo-600 text-white rounded">CTA</span>
                  </div>
                </div>
              </button>

              <!-- Style 2 -->
              <button
                type="button"
                @click="form.layout_style = 'style_2'"
                :class="form.layout_style === 'style_2' ? 'border-indigo-600 bg-indigo-50/70 ring-1 ring-indigo-600' : 'border-slate-200 bg-white hover:bg-slate-50'"
                class="w-full text-left p-3 rounded-xl border transition space-y-2"
              >
                <div class="flex items-center justify-between">
                  <span class="font-bold text-slate-800">Style 2 &mdash; Centered Menu</span>
                  <span v-if="form.layout_style === 'style_2'" class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-full">Active</span>
                </div>
                <div class="p-2 bg-slate-100 rounded border border-slate-200 flex items-center justify-between text-[9px] text-slate-500">
                  <span class="font-bold text-slate-700">LOGO</span>
                  <span>Menu &bull; Menu &bull; Menu</span>
                  <span class="px-1.5 py-0.5 bg-indigo-600 text-white rounded">CTA</span>
                </div>
              </button>

              <!-- Style 3 -->
              <button
                type="button"
                @click="form.layout_style = 'style_3'"
                :class="form.layout_style === 'style_3' ? 'border-indigo-600 bg-indigo-50/70 ring-1 ring-indigo-600' : 'border-slate-200 bg-white hover:bg-slate-50'"
                class="w-full text-left p-3 rounded-xl border transition space-y-2"
              >
                <div class="flex items-center justify-between">
                  <span class="font-bold text-slate-800">Style 3 &mdash; Menu Below Logo</span>
                  <span v-if="form.layout_style === 'style_3'" class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-full">Active</span>
                </div>
                <p class="text-[11px] text-slate-500">Full-width menu bar below logo with custom background, border &amp; text color.</p>
                <div class="bg-slate-100 rounded border border-slate-200 overflow-hidden text-[9px] text-slate-500">
                  <div class="px-2 py-1.5 flex items-center justify-between bg-white">
                    <span class="font-bold text-slate-700">LOGO</span>
                    <span class="px-1.5 py-0.5 bg-indigo-600 text-white rounded">CTA</span>
                  </div>
                  <div class="px-2 py-1 border-t border-slate-200 text-center">
                    Menu &bull; Menu &bull; Menu
                  </div>
                </div>
              </button>
            </div>
          </div>
        </aside>

      </div>
    </div>

    <MediaModal :is-open="mediaModalOpen" @close="mediaModalOpen = false" @select="onMediaSelect" />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MediaModal from '@/Components/MediaModal.vue';
import ThemeColorPicker from '@/Components/ThemeColorPicker.vue';
import KitPressLogo from '@/Components/KitPressLogo.vue';

const props = defineProps({
  header: Object,
  pages: {
    type: Array,
    default: () => [],
  },
});

const mediaModalOpen = ref(false);

const form = useForm({
  layout_style: props.header?.layout_style || 'style_1',
  transparent: Boolean(props.header?.transparent),
  logo_text: props.header?.logo_text || '',
  logo_text_color: props.header?.logo_text_color || props.header?.text_color || '#0f172a',
  transparent_logo_text_color: props.header?.transparent_logo_text_color || '#ffffff',
  transparent_nav_link_color: props.header?.transparent_nav_link_color || '#ffffff',
  logo_image: props.header?.logo_image || '',
  show_logo_icon: props.header?.show_logo_icon !== undefined ? Boolean(props.header.show_logo_icon) : true,
  sticky: props.header?.sticky !== undefined ? Boolean(props.header.sticky) : true,
  bg_color: props.header?.bg_color || '#ffffff',
  text_color: props.header?.text_color || '#0f172a',
  nav_link_color: props.header?.nav_link_color || props.header?.text_color || '#334155',
  nav_link_hover_color: props.header?.nav_link_hover_color || '#4f46e5',
  nav_link_active_color: props.header?.nav_link_active_color || props.header?.nav_link_hover_color || '#4f46e5',
  menu_bar_bg: props.header?.menu_bar_bg || '#f8fafc',
  menu_bar_border: props.header?.menu_bar_border || '#e2e8f0',
  menu_bar_text: props.header?.menu_bar_text || '#1e293b',
  nav_links: Array.isArray(props.header?.nav_links) ? props.header.nav_links.map(l => ({ ...l })) : [],
  cta_enabled: props.header?.cta_enabled !== undefined ? Boolean(props.header.cta_enabled) : true,
  cta_text: props.header?.cta_text || 'Admin Dashboard',
  cta_url: props.header?.cta_url || '/admin',
});

const addLink = () => {
  form.nav_links.push({ label: 'New Link', url: '/', new_tab: false });
};

const quickAddPage = (e) => {
  const id = Number(e.target.value);
  const page = props.pages.find((p) => p.id === id);
  if (page) {
    form.nav_links.push({ label: page.title, url: `/${page.slug}`, new_tab: false });
  }
  e.target.value = '';
};

const moveLink = (idx, dir) => {
  const target = idx + dir;
  if (target < 0 || target >= form.nav_links.length) return;
  const item = form.nav_links.splice(idx, 1)[0];
  form.nav_links.splice(target, 0, item);
};

const onMediaSelect = (mediaItem) => {
  form.logo_image = mediaItem.sizes?.medium?.url || mediaItem.url;
};

const save = () => {
  form.post('/admin/theme/header');
};
</script>

<style scoped>
.preview-nav-link {
  color: var(--header-link-color);
  transition: color 0.15s ease;
}

.preview-nav-link:hover {
  color: var(--header-link-hover-color);
}

.preview-nav-link.is-active {
  color: var(--header-link-active-color);
}
</style>
