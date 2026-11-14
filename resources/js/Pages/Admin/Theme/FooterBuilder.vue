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

            <!-- Contact & Social Icons Column -->
            <div v-if="form.contact_enabled" class="space-y-2.5">
              <h4 class="text-xs font-bold uppercase tracking-wider">{{ form.contact_title || 'Contact Us' }}</h4>
              <ul class="space-y-1 text-xs opacity-80">
                <li v-if="form.contact_email">{{ form.contact_email }}</li>
                <li v-if="form.contact_phone">{{ form.contact_phone }}</li>
                <li v-if="form.contact_address" class="whitespace-pre-line">{{ form.contact_address }}</li>
              </ul>
              <div v-if="form.social_links.length" class="flex flex-wrap items-center gap-2 pt-1">
                <span
                  v-for="(social, sIdx) in form.social_links"
                  :key="sIdx"
                  :title="social.platform"
                  class="p-1.5 rounded-lg border border-current/20 opacity-85"
                >
                  <SocialIcon :platform="social.platform" />
                </span>
              </div>
            </div>
          </div>

          <div class="pt-4 border-t border-current/15 text-xs flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; <span v-if="form.show_year">{{ new Date().getFullYear() }}</span> {{ $page.props.settings?.site_name || 'KitPress' }}. {{ form.copyright_text }}</span>
          </div>
        </div>
      </div>

      <!-- 2-Column Layout: Left Tabs Sidebar + Right Content -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left Vertical Tabs Sidebar -->
        <aside class="lg:col-span-3 bg-white rounded-xl border border-slate-200/90 shadow-2xs p-2 space-y-1">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            type="button"
            @click="activeTab = tab.id"
            :class="activeTab === tab.id ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium'"
            class="w-full flex items-start space-x-3 px-3 py-2.5 rounded-lg text-left text-xs transition"
          >
            <svg
              class="w-4 h-4 shrink-0 mt-0.5"
              :class="activeTab === tab.id ? 'text-indigo-600' : 'text-slate-400'"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path v-if="tab.id === 'brand'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              <path v-else-if="tab.id === 'links'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
              <path v-else-if="tab.id === 'contact'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              <path v-else-if="tab.id === 'copyright'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
            </svg>
            <div class="min-w-0">
              <div class="truncate">{{ tab.label }}</div>
              <div class="text-[11px] text-slate-400 font-normal truncate">{{ tab.description }}</div>
            </div>
          </button>
        </aside>

        <!-- Right Tab Content -->
        <form @submit.prevent="save" class="lg:col-span-9 space-y-6">
          <!-- TAB 1: BRAND COLUMN -->
          <div v-show="activeTab === 'brand'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
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

          <!-- TAB 2: LINKS COLUMN -->
          <div v-show="activeTab === 'links'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
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

          <!-- TAB 3: CONTACT & SOCIAL ICONS -->
          <div v-show="activeTab === 'contact'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-5 text-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div>
                <h2 class="text-sm font-bold text-slate-800">Contact &amp; Social Icons</h2>
                <p class="text-[11px] text-slate-500">Display contact details and social media icon links in your footer.</p>
              </div>
              <label class="flex items-center space-x-2 cursor-pointer">
                <input v-model="form.contact_enabled" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
                <span class="font-semibold text-slate-700">Enable Section</span>
              </label>
            </div>

            <div v-if="form.contact_enabled" class="space-y-5">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Column Title</label>
                  <input v-model="form.contact_title" type="text" placeholder="Contact Us" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
                </div>
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Email Address</label>
                  <input v-model="form.contact_email" type="email" placeholder="hello@example.com" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
                </div>
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Phone Number</label>
                  <input v-model="form.contact_phone" type="text" placeholder="+1 (555) 123-4567" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
                </div>
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Address</label>
                  <input v-model="form.contact_address" type="text" placeholder="123 Main St, City" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
                </div>
              </div>

              <div class="pt-3 border-t border-slate-100 space-y-3">
                <div class="flex items-center justify-between">
                  <span class="font-bold text-slate-800">Social Icons</span>
                  <button
                    type="button"
                    @click="addSocialLink"
                    class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg font-semibold"
                  >
                    + Add Social Icon
                  </button>
                </div>

                <div v-if="form.social_links.length === 0" class="py-4 text-center text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                  No social icons added yet. Click "+ Add Social Icon" to add links.
                </div>

                <div class="space-y-2">
                  <div v-for="(social, sIdx) in form.social_links" :key="sIdx" class="flex items-center gap-2">
                    <select v-model="social.platform" class="w-40 px-2.5 py-2 border border-slate-300 rounded-lg bg-slate-50 font-semibold">
                      <option value="facebook">Facebook</option>
                      <option value="twitter">X (Twitter)</option>
                      <option value="instagram">Instagram</option>
                      <option value="linkedin">LinkedIn</option>
                      <option value="youtube">YouTube</option>
                      <option value="github">GitHub</option>
                    </select>
                    <input v-model="social.url" type="url" placeholder="https://..." required class="flex-1 px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 font-mono" />
                    <button type="button" @click="form.social_links.splice(sIdx, 1)" class="px-2.5 py-2 text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-lg bg-white">&times;</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 4: COPYRIGHT -->
          <div v-show="activeTab === 'copyright'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
            <div class="border-b border-slate-100 pb-3">
              <h2 class="text-sm font-bold text-slate-800">Copyright Notice</h2>
              <p class="text-[11px] text-slate-500">Customize the bottom copyright bar displayed below the footer columns.</p>
            </div>

            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Copyright / Bottom Text</label>
              <input v-model="form.copyright_text" type="text" placeholder="All rights reserved." class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50" />
            </div>

            <label class="flex items-center space-x-2 cursor-pointer pt-1">
              <input v-model="form.show_year" type="checkbox" class="rounded border-slate-300 text-indigo-600" />
              <span class="font-semibold text-slate-700">Automatically show current year (&copy; {{ new Date().getFullYear() }})</span>
            </label>
          </div>

          <!-- TAB 5: STYLE -->
          <div v-show="activeTab === 'style'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
            <div class="border-b border-slate-100 pb-3">
              <h2 class="text-sm font-bold text-slate-800">Footer Style &amp; Colors</h2>
              <p class="text-[11px] text-slate-500">Configure the background and text colors for the footer area.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <ThemeColorPicker v-model="form.bg_color" label="Background Color" compact />
              <ThemeColorPicker v-model="form.text_color" label="Text Color" compact />
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
import ThemeColorPicker from '@/Components/ThemeColorPicker.vue';
import SocialIcon from '@/Components/SocialIcon.vue';
import { getTypographyFontsHref, compileTypographyCss } from '@/utils/themeFonts';

const props = defineProps({
  footer: Object,
});

const tabs = [
  { id: 'brand', label: 'Brand column', description: 'Logo & description' },
  { id: 'links', label: 'Links column', description: 'Navigation groups' },
  { id: 'contact', label: 'Contact & social icons', description: 'Details & social links' },
  { id: 'copyright', label: 'Copyright', description: 'Bottom notice & year' },
  { id: 'style', label: 'Style', description: 'Background & text colors' },
];

const activeTab = ref('brand');

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
  contact_enabled: props.footer?.contact_enabled !== undefined ? Boolean(props.footer.contact_enabled) : false,
  contact_title: props.footer?.contact_title || 'Contact Us',
  contact_email: props.footer?.contact_email || '',
  contact_phone: props.footer?.contact_phone || '',
  contact_address: props.footer?.contact_address || '',
  social_links: Array.isArray(props.footer?.social_links)
    ? props.footer.social_links.map(s => ({ ...s }))
    : [],
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

const addSocialLink = () => {
  form.social_links.push({ platform: 'facebook', url: 'https://' });
};

const onMediaSelect = (mediaItem) => {
  form.brand_logo = mediaItem.sizes?.medium?.url || mediaItem.url;
};

const save = () => {
  form.post('/admin/theme/footer');
};
</script>
