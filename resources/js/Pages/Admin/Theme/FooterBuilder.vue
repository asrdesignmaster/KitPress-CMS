<template>
  <AdminLayout>
    <link v-if="bunnyFontsHref" rel="stylesheet" :href="bunnyFontsHref" />
    <component :is="'style'">{{ previewTypographyCss }}</component>
    <div class="space-y-6 max-w-full">
      <!-- Header -->
      <div class="pb-4 border-b border-slate-200">
        <div class="flex items-center space-x-2">
          <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 14h16" />
            </svg>
          </span>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Theme &rsaquo; Footer Builder</h1>
        </div>
        <p class="text-xs text-slate-500 mt-1">
          Build your site footer with a default company logo &amp; description column, custom link columns, colors, and copyright notice.
        </p>
      </div>

      <!-- Live Footer Preview -->
      <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="px-4 py-2 bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
          Live Footer Preview
        </div>
        <div class="p-6 space-y-6 border-t border-slate-200/60" :style="{ backgroundColor: form.bg_color, color: form.text_color }">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Default Brand Column -->
            <div v-if="form.brand_enabled" class="space-y-3">
              <div class="flex items-center space-x-2 font-bold text-sm">
                <img v-if="form.brand_logo" :src="form.brand_logo" alt="Company Logo" class="h-8 w-auto object-contain" />
                <span v-else>{{ form.brand_name || $page.props.settings?.site_name || 'KitPress' }}</span>
              </div>
              <div v-if="form.brand_description" class="footer-brand-preview max-w-none opacity-90" v-html="form.brand_description"></div>
            </div>

            <!-- Link Columns -->
            <div v-for="(col, cIdx) in form.columns" :key="cIdx" class="space-y-2">
              <h4 class="text-xs font-bold uppercase tracking-wider">{{ col.title || 'Column' }}</h4>
              <ul class="space-y-1 text-xs opacity-80">
                <li v-for="(l, lIdx) in col.links" :key="lIdx">{{ l.label }}</li>
              </ul>
            </div>
          </div>

          <div class="pt-4 border-t border-current/15 text-xs flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; <span v-if="form.show_year">{{ new Date().getFullYear() }}</span> {{ $page.props.settings?.site_name || 'KitPress' }}. {{ form.copyright_text }}</span>
          </div>
        </div>
      </div>

      <form @submit.prevent="save" class="space-y-6">
        <!-- Default Brand Column (Company Logo + Rich Text Paragraph) -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <h2 class="text-sm font-bold text-slate-800">Default Brand Column (Logo &amp; Paragraph)</h2>
              <p class="text-[11px] text-slate-500">First column of your footer displaying your company logo and formatted description.</p>
            </div>
            <label class="flex items-center space-x-2 cursor-pointer">
              <input v-model="form.brand_enabled" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
              <span class="font-semibold text-slate-700">Enable Column</span>
            </label>
          </div>

          <div v-if="form.brand_enabled" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Company Logo URL</label>
                <div class="flex items-center space-x-2">
                  <input v-model="form.brand_logo" type="text" placeholder="/media-files/..." class="flex-1 px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
                  <button type="button" @click="mediaModalOpen = true" class="px-3 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg font-semibold hover:bg-indigo-100">
                    Browse
                  </button>
                </div>
              </div>

              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Company Name (fallback if no logo)</label>
                <input v-model="form.brand_name" type="text" placeholder="Uses Site Name if empty" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="font-semibold text-slate-700 block">Paragraph Text (below logo)</label>
              <RichParagraphEditor v-model="form.brand_description" />
            </div>
          </div>
        </div>

        <!-- Appearance & Copyright -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
          <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Style &amp; Copyright</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

            <div class="space-y-1 md:col-span-2">
              <label class="font-semibold text-slate-700 block">Copyright / Bottom Text</label>
              <input v-model="form.copyright_text" type="text" placeholder="All rights reserved." class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
            </div>
          </div>

          <label class="flex items-center space-x-2 cursor-pointer pt-1">
            <input v-model="form.show_year" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
            <span class="font-semibold text-slate-700">Automatically show current year (&copy; {{ new Date().getFullYear() }})</span>
          </label>
        </div>

        <!-- Footer Columns Builder -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <h2 class="text-sm font-bold text-slate-800">Footer Link Columns</h2>
              <p class="text-[11px] text-slate-500">Organize footer links into up to 4 responsive columns.</p>
            </div>
            <button
              type="button"
              @click="addColumn"
              :disabled="form.columns.length >= 4"
              class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg font-semibold disabled:opacity-40"
            >
              + Add Column
            </button>
          </div>

          <div v-if="form.columns.length === 0" class="py-6 text-center text-slate-400">
            No footer columns configured. Click "+ Add Column" to add link groups.
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="(col, cIdx) in form.columns" :key="cIdx" class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
              <div class="flex items-center justify-between gap-2">
                <input v-model="col.title" type="text" placeholder="Column Title" required class="flex-1 px-2.5 py-1.5 border border-slate-300 rounded bg-white font-semibold" />
                <button type="button" @click="form.columns.splice(cIdx, 1)" class="px-2 py-1 text-rose-600 hover:bg-rose-50 border border-rose-200 rounded bg-white">&times;</button>
              </div>

              <div class="space-y-2">
                <div v-for="(link, lIdx) in col.links" :key="lIdx" class="flex items-center gap-1.5">
                  <input v-model="link.label" type="text" placeholder="Label" required class="w-1/2 px-2 py-1 border border-slate-300 rounded bg-white" />
                  <input v-model="link.url" type="text" placeholder="/url" required class="w-1/2 px-2 py-1 border border-slate-300 rounded bg-white font-mono" />
                  <button type="button" @click="col.links.splice(lIdx, 1)" class="px-1.5 py-1 text-rose-600 hover:bg-rose-50 rounded">&times;</button>
                </div>
              </div>

              <button type="button" @click="col.links.push({ label: 'Link', url: '/' })" class="text-[11px] font-semibold text-indigo-600 hover:underline">
                + Add Link to Column
              </button>
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            :disabled="form.processing"
            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition disabled:opacity-50"
          >
            {{ form.processing ? 'Saving...' : 'Save Footer Builder' }}
          </button>
        </div>
      </form>
    </div>

    <MediaModal :is-open="mediaModalOpen" @close="mediaModalOpen = false" @select="onMediaSelect" />
  </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MediaModal from '@/Components/MediaModal.vue';
import RichParagraphEditor from '@/Components/RichParagraphEditor.vue';
import { getTypographyFontsHref, compileTypographyCss } from '@/utils/themeFonts';

const props = defineProps({
  footer: Object,
});

const page = usePage();
const bunnyFontsHref = computed(() => getTypographyFontsHref(page.props.theme?.general?.typography || {}));
const previewTypographyCss = computed(() =>
  compileTypographyCss(
    '.footer-brand-preview',
    page.props.theme?.general?.typography || {},
    page.props.theme?.general?.colors || {}
  )
);

const mediaModalOpen = ref(false);

const form = useForm({
  brand_enabled: props.footer?.brand_enabled !== undefined ? Boolean(props.footer.brand_enabled) : true,
  brand_logo: props.footer?.brand_logo || '',
  brand_name: props.footer?.brand_name || '',
  brand_description: props.footer?.brand_description || '<p>Building fast, modern web experiences with clean architecture.</p>',
  bg_color: props.footer?.bg_color || '#ffffff',
  text_color: props.footer?.text_color || '#64748b',
  copyright_text: props.footer?.copyright_text || 'High-Performance Laravel + Vue CMS.',
  show_year: props.footer?.show_year !== undefined ? Boolean(props.footer.show_year) : true,
  columns: Array.isArray(props.footer?.columns)
    ? props.footer.columns.map(c => ({
        title: c.title || '',
        links: Array.isArray(c.links) ? c.links.map(l => ({ ...l })) : [],
      }))
    : [],
});

const addColumn = () => {
  if (form.columns.length >= 4) return;
  form.columns.push({
    title: 'Quick Links',
    links: [{ label: 'Home', url: '/' }],
  });
};

const onMediaSelect = (mediaItem) => {
  form.brand_logo = mediaItem.sizes?.medium?.url || mediaItem.url;
};

const save = () => {
  form.post('/admin/theme/footer');
};
</script>
