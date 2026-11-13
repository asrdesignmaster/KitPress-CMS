<template>
  <AdminLayout>
    <div class="space-y-6 max-w-5xl">
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
          Customize your public site navigation bar, logo, menu links, and call-to-action button.
        </p>
      </div>

      <!-- Live Header Preview -->
      <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="px-4 py-2 bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
          Live Header Preview
        </div>
        <div
          class="h-16 px-6 flex items-center justify-between border-b border-slate-200/70"
          :style="{ backgroundColor: form.bg_color, color: form.text_color }"
        >
          <div class="flex items-center space-x-2 font-bold text-base">
            <img v-if="form.logo_image" :src="form.logo_image" alt="Logo" class="h-8 w-auto object-contain" />
            <svg v-else-if="form.show_logo_icon" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>{{ form.logo_text || $page.props.settings?.site_name || 'Modern CMS' }}</span>
          </div>

          <div class="flex items-center space-x-4 text-xs font-semibold">
            <span v-for="(link, idx) in form.nav_links" :key="idx" class="opacity-80">
              {{ link.label || 'Link' }}
            </span>
            <span
              v-if="form.cta_enabled && form.cta_text"
              class="px-3 py-1.5 rounded-lg text-white bg-indigo-600"
            >
              {{ form.cta_text }}
            </span>
          </div>
        </div>
      </div>

      <form @submit.prevent="save" class="space-y-6">
        <!-- Brand & Style -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
          <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Branding &amp; Appearance</h2>

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

            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Background Color</label>
              <div class="flex items-center space-x-2">
                <input v-model="form.bg_color" type="color" class="w-9 h-9 rounded border cursor-pointer" />
                <input v-model="form.bg_color" type="text" class="flex-1 px-2.5 py-1.5 border border-slate-300 rounded-lg font-mono" />
              </div>
            </div>

            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Text Color</label>
              <div class="flex items-center space-x-2">
                <input v-model="form.text_color" type="color" class="w-9 h-9 rounded border cursor-pointer" />
                <input v-model="form.text_color" type="text" class="flex-1 px-2.5 py-1.5 border border-slate-300 rounded-lg font-mono" />
              </div>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-6 pt-2">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input v-model="form.sticky" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
              <span class="font-semibold text-slate-700">Sticky Header on Scroll</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
              <input v-model="form.show_logo_icon" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
              <span class="font-semibold text-slate-700">Show Default Brand Icon</span>
            </label>
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
    </div>

    <MediaModal :is-open="mediaModalOpen" @close="mediaModalOpen = false" @select="onMediaSelect" />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MediaModal from '@/Components/MediaModal.vue';

const props = defineProps({
  header: Object,
  pages: {
    type: Array,
    default: () => [],
  },
});

const mediaModalOpen = ref(false);

const form = useForm({
  logo_text: props.header?.logo_text || '',
  logo_image: props.header?.logo_image || '',
  show_logo_icon: props.header?.show_logo_icon !== undefined ? Boolean(props.header.show_logo_icon) : true,
  sticky: props.header?.sticky !== undefined ? Boolean(props.header.sticky) : true,
  bg_color: props.header?.bg_color || '#ffffff',
  text_color: props.header?.text_color || '#0f172a',
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
