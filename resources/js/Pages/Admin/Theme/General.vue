<template>
  <AdminLayout>
    <link v-if="bunnyFontsHref" rel="stylesheet" :href="bunnyFontsHref" />

    <div class="space-y-6 max-w-full">
      <!-- Header -->
      <div class="pb-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <div class="flex items-center space-x-2">
            <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
              </svg>
            </span>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Theme &rsaquo; General</h1>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            Configure global site Colors, Typography (Paragraph &amp; Headings H1–H4), and Custom CSS applied across public pages.
          </p>
        </div>

        <!-- Section Tabs -->
        <div class="inline-flex p-1 bg-slate-100 border border-slate-200 rounded-lg text-xs">
          <button
            type="button"
            @click="activeTab = 'colors'"
            :class="activeTab === 'colors' ? 'bg-white text-indigo-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-3 py-1.5 rounded-md transition"
          >
            Colors
          </button>
          <button
            type="button"
            @click="activeTab = 'typography'"
            :class="activeTab === 'typography' ? 'bg-white text-indigo-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-3 py-1.5 rounded-md transition"
          >
            Typography
          </button>
          <button
            type="button"
            @click="activeTab = 'custom_css'"
            :class="activeTab === 'custom_css' ? 'bg-white text-indigo-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-3 py-1.5 rounded-md transition"
          >
            Custom CSS
          </button>
        </div>
      </div>

      <!-- 2-Column Layout: Left Controls + Right Sticky Preview -->
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        <!-- LEFT: Settings Form -->
        <form @submit.prevent="save" class="xl:col-span-8 space-y-6">
          <!-- TAB 1: COLORS -->
          <div v-show="activeTab === 'colors'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div>
                <h2 class="text-sm font-bold text-slate-800">Global Color Palette</h2>
                <p class="text-xs text-slate-500">Exposed as CSS variables (<code>--theme-primary</code>, <code>--theme-bg</code>, etc.).</p>
              </div>
            </div>

            <!-- Preset Color Palettes -->
            <div class="space-y-2.5">
              <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-700">Preset Color Palettes</span>
                <span class="text-[11px] text-slate-400">Click any palette to apply</span>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                <button
                  v-for="palette in colorPalettes"
                  :key="palette.name"
                  type="button"
                  @click="applyPalette(palette)"
                  :class="isPaletteActive(palette) ? 'border-indigo-600 bg-indigo-50/60 ring-1 ring-indigo-600' : 'border-slate-200 bg-slate-50/60 hover:bg-white hover:border-slate-300'"
                  class="p-3 rounded-xl border text-left transition space-y-2"
                >
                  <div class="flex items-center justify-between gap-2">
                    <span class="font-semibold text-slate-800 truncate">{{ palette.name }}</span>
                    <span v-if="isPaletteActive(palette)" class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-1.5 py-0.5 rounded-full shrink-0">Active</span>
                  </div>
                  <div class="flex items-center gap-1.5">
                    <span
                      v-for="key in ['primary', 'secondary', 'accent', 'background', 'surface', 'text']"
                      :key="key"
                      :title="`${key}: ${palette.colors[key]}`"
                      :style="{ backgroundColor: palette.colors[key] }"
                      class="w-4 h-4 rounded-full border border-slate-300/80 shadow-2xs"
                    ></span>
                  </div>
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs pt-2 border-t border-slate-100">
              <div v-for="field in colorFields" :key="field.key" class="p-3 rounded-lg border border-slate-200 bg-slate-50/50 space-y-2">
                <label class="font-semibold text-slate-700 block">{{ field.label }}</label>
                <div class="flex items-center space-x-2">
                  <input
                    v-model="form.colors[field.key]"
                    type="color"
                    class="w-9 h-9 rounded border border-slate-300 cursor-pointer bg-white p-0.5"
                  />
                  <input
                    v-model="form.colors[field.key]"
                    type="text"
                    class="flex-1 px-2.5 py-1.5 border border-slate-300 rounded-lg bg-white font-mono text-slate-800"
                  />
                </div>
                <p class="text-[11px] text-slate-400 font-mono">{{ field.varName }}</p>
              </div>
            </div>
          </div>

          <!-- TAB 2: TYPOGRAPHY -->
          <div v-show="activeTab === 'typography'" class="space-y-6">
            <!-- Base Global Defaults -->
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-5">
              <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800">Global Typography Defaults</h2>
                <p class="text-xs text-slate-500">Inherited by any element set to <strong>Default</strong> below.</p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Default Body Font Family</label>
                  <select v-model="form.typography.body_font" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                    <option v-for="f in fontOptions" :key="f.value" :value="f.value">{{ f.label }}</option>
                  </select>
                </div>

                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Default Heading Font Family</label>
                  <select v-model="form.typography.heading_font" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                    <option v-for="f in fontOptions" :key="f.value" :value="f.value">{{ f.label }}</option>
                  </select>
                </div>

                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Base Font Size</label>
                  <select v-model="form.typography.base_size" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                    <option value="14px">14px (Compact)</option>
                    <option value="15px">15px</option>
                    <option value="16px">16px (Default)</option>
                    <option value="17px">17px</option>
                    <option value="18px">18px (Editorial)</option>
                  </select>
                </div>

                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Default Heading Weight</label>
                  <select v-model="form.typography.heading_weight" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                    <option value="600">600 (SemiBold)</option>
                    <option value="700">700 (Bold)</option>
                    <option value="800">800 (ExtraBold)</option>
                  </select>
                </div>

                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Default Body Line Height</label>
                  <select v-model="form.typography.line_height" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                    <option value="1.4">1.4 (Tight)</option>
                    <option value="1.6">1.6 (Balanced)</option>
                    <option value="1.75">1.75 (Relaxed)</option>
                    <option value="1.9">1.9 (Spacious)</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Per-Element Typography (Paragraph, H1, H2, H3, H4) -->
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-5">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                  <h2 class="text-sm font-bold text-slate-800">Element Typography (Paragraph &amp; Headings)</h2>
                  <p class="text-xs text-slate-500">Customize webfont, color, size (px / em / rem), weight, and line height per element.</p>
                </div>

                <!-- Element Selector Pills -->
                <div class="inline-flex flex-wrap p-1 bg-slate-100 border border-slate-200 rounded-lg text-xs gap-1">
                  <button
                    v-for="el in elementTabs"
                    :key="el.tag"
                    type="button"
                    @click="activeElement = el.tag"
                    :class="activeElement === el.tag ? 'bg-white text-indigo-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-2.5 py-1 rounded-md transition"
                  >
                    {{ el.label }}
                  </button>
                </div>
              </div>

              <div v-if="currentElement" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <!-- Font Family -->
                <div class="space-y-1 md:col-span-2">
                  <label class="font-semibold text-slate-700 block">Font Family ({{ activeElementLabel }})</label>
                  <select
                    v-model="currentElement.font_family"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800"
                  >
                    <option value="default">Default (Inherit Global Font)</option>
                    <option v-for="f in fontOptions" :key="f.value" :value="f.value">{{ f.label }}</option>
                  </select>
                </div>

                <!-- Color -->
                <div class="space-y-1">
                  <div class="flex items-center justify-between">
                    <span class="font-semibold text-slate-700">Text Color</span>
                    <button
                      v-if="currentElement.color"
                      type="button"
                      @click="currentElement.color = ''"
                      class="text-[11px] text-indigo-600 hover:text-indigo-800 font-medium"
                    >
                      Use Default
                    </button>
                  </div>
                  <ThemeColorPicker
                    v-model="currentElement.color"
                    placeholder="Default (Inherit Theme Color)"
                    :allow-transparent="false"
                  />
                </div>

                <!-- Font Weight -->
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Font Weight</label>
                  <select
                    v-model="currentElement.font_weight"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800"
                  >
                    <option value="default">Default (Inherit)</option>
                    <option value="300">300 (Light)</option>
                    <option value="400">400 (Regular)</option>
                    <option value="500">500 (Medium)</option>
                    <option value="600">600 (SemiBold)</option>
                    <option value="700">700 (Bold)</option>
                    <option value="800">800 (ExtraBold)</option>
                    <option value="900">900 (Black)</option>
                  </select>
                </div>

                <!-- Font Size (px / em / rem) -->
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Font Size (px or em)</label>
                  <div class="flex items-center space-x-2">
                    <input
                      :value="parsedFontSize.num"
                      @input="updateElementSizeNum($event.target.value)"
                      type="number"
                      step="any"
                      min="0"
                      class="flex-1 px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800 font-mono"
                    />
                    <select
                      :value="parsedFontSize.unit"
                      @change="updateElementSizeUnit($event.target.value)"
                      class="w-24 px-2.5 py-2 border border-slate-300 rounded-lg bg-white text-slate-800 font-semibold"
                    >
                      <option value="px">px</option>
                      <option value="em">em</option>
                      <option value="rem">rem</option>
                    </select>
                  </div>
                </div>

                <!-- Line Height -->
                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Line Height</label>
                  <input
                    v-model="currentElement.line_height"
                    type="text"
                    placeholder="e.g. 1.25 or 1.6"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800 font-mono"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 3: CUSTOM CSS -->
          <div v-show="activeTab === 'custom_css'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3">
              <h2 class="text-sm font-bold text-slate-800">Custom CSS</h2>
              <p class="text-xs text-slate-500">
                Injected into all public pages. You can target widget custom IDs/classes or use theme variables
                (<code>var(--theme-primary)</code>, <code>var(--theme-bg)</code>, <code>var(--theme-text)</code>).
              </p>
            </div>

            <textarea
              v-model="form.custom_css"
              rows="14"
              spellcheck="false"
              placeholder="/* Example: */&#10;.my-card {&#10;  border: 2px solid var(--theme-primary);&#10;  border-radius: 16px;&#10;}"
              class="w-full p-4 font-mono text-xs bg-slate-900 text-emerald-300 border border-slate-700 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-indigo-500 leading-relaxed"
            ></textarea>
          </div>

          <!-- Save Bar -->
          <div class="flex items-center justify-between pt-2">
            <button
              type="button"
              @click="resetDefaults"
              class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-300 rounded-lg transition"
            >
              Reset to Defaults
            </button>

            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition disabled:opacity-50"
            >
              {{ form.processing ? 'Saving...' : 'Save Theme General Settings' }}
            </button>
          </div>
        </form>

        <!-- RIGHT: Sticky Live Theme Preview -->
        <aside class="xl:col-span-4 xl:sticky xl:top-20 space-y-4">
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Live Theme Preview</span>
              <span class="text-[11px] font-mono text-slate-400">H1–H4 &bull; P &bull; Palette</span>
            </div>

            <div
              class="p-4 rounded-xl border border-slate-200 space-y-3"
              :style="{ backgroundColor: form.colors.background, color: form.colors.text }"
            >
              <div
                class="p-4 rounded-lg border border-slate-200/70 shadow-2xs space-y-2.5"
                :style="{ backgroundColor: form.colors.surface }"
              >
                <div :style="previewStyle('h1')">Heading 1</div>
                <div :style="previewStyle('h2')">Heading 2</div>
                <div :style="previewStyle('h3')">Heading 3</div>
                <div :style="previewStyle('h4')">Heading 4</div>

                <p :style="previewStyle('p')" class="pt-1">
                  Paragraph text renders using your configured webfont, size, weight, color, and line height across public pages.
                </p>

                <div class="flex items-center space-x-3 pt-2">
                  <span
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white"
                    :style="{ backgroundColor: form.colors.primary }"
                  >
                    Primary Button
                  </span>
                  <span
                    class="text-xs font-semibold underline"
                    :style="{ color: form.colors.accent }"
                  >
                    Accent Link
                  </span>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ThemeColorPicker from '@/Components/ThemeColorPicker.vue';
import {
  DEFAULT_TYPOGRAPHY_ELEMENTS,
  THEME_FONT_OPTIONS,
  getBunnyFontsHref,
  resolveElementStyle,
} from '@/utils/themeFonts';

const props = defineProps({
  general: Object,
});

const activeTab = ref('colors');
const activeElement = ref('p');

const colorFields = [
  { key: 'primary', label: 'Primary Color', varName: '--theme-primary' },
  { key: 'secondary', label: 'Secondary / Heading', varName: '--theme-secondary' },
  { key: 'background', label: 'Page Background', varName: '--theme-bg' },
  { key: 'surface', label: 'Card / Surface', varName: '--theme-surface' },
  { key: 'text', label: 'Body Text Color', varName: '--theme-text' },
  { key: 'accent', label: 'Link / Accent', varName: '--theme-accent' },
];

const colorPalettes = [
  {
    name: 'Indigo Modern',
    colors: { primary: '#4f46e5', secondary: '#0f172a', background: '#f8fafc', surface: '#ffffff', text: '#1e293b', accent: '#6366f1' },
  },
  {
    name: 'Emerald Editorial',
    colors: { primary: '#059669', secondary: '#064e3b', background: '#f4faf7', surface: '#ffffff', text: '#1f2937', accent: '#10b981' },
  },
  {
    name: 'Midnight Slate',
    colors: { primary: '#6366f1', secondary: '#f8fafc', background: '#0b1120', surface: '#111827', text: '#cbd5e1', accent: '#818cf8' },
  },
  {
    name: 'Warm Terracotta',
    colors: { primary: '#ea580c', secondary: '#292524', background: '#faf7f5', surface: '#ffffff', text: '#44403c', accent: '#f97316' },
  },
  {
    name: 'Oceanic Teal',
    colors: { primary: '#0284c7', secondary: '#0c4a6e', background: '#f0f9ff', surface: '#ffffff', text: '#1e293b', accent: '#06b6d4' },
  },
  {
    name: 'Royal Plum',
    colors: { primary: '#9333ea', secondary: '#1e1b4b', background: '#faf5ff', surface: '#ffffff', text: '#334155', accent: '#c084fc' },
  },
];

const applyPalette = (palette) => {
  Object.assign(form.colors, palette.colors);
};

const isPaletteActive = (palette) =>
  Object.entries(palette.colors).every(
    ([key, val]) => (form.colors[key] || '').toLowerCase() === val.toLowerCase()
  );

const elementTabs = [
  { tag: 'p', label: 'Paragraph' },
  { tag: 'h1', label: 'Heading 1' },
  { tag: 'h2', label: 'Heading 2' },
  { tag: 'h3', label: 'Heading 3' },
  { tag: 'h4', label: 'Heading 4' },
];

const fontOptions = THEME_FONT_OPTIONS;

const buildElementsState = (savedElements = {}) => {
  const result = {};
  for (const tag of Object.keys(DEFAULT_TYPOGRAPHY_ELEMENTS)) {
    result[tag] = {
      ...DEFAULT_TYPOGRAPHY_ELEMENTS[tag],
      ...(savedElements?.[tag] || {}),
    };
  }
  return result;
};

const form = useForm({
  colors: {
    primary: props.general?.colors?.primary || '#4f46e5',
    secondary: props.general?.colors?.secondary || '#0f172a',
    background: props.general?.colors?.background || '#f8fafc',
    surface: props.general?.colors?.surface || '#ffffff',
    text: props.general?.colors?.text || '#1e293b',
    accent: props.general?.colors?.accent || '#6366f1',
  },
  typography: {
    body_font: props.general?.typography?.body_font || 'Instrument Sans, Inter, sans-serif',
    heading_font: props.general?.typography?.heading_font || 'Instrument Sans, Inter, sans-serif',
    base_size: props.general?.typography?.base_size || '16px',
    heading_weight: props.general?.typography?.heading_weight || '700',
    line_height: props.general?.typography?.line_height || '1.6',
    elements: buildElementsState(props.general?.typography?.elements),
  },
  custom_css: props.general?.custom_css || '',
});

const currentElement = computed(() => form.typography.elements?.[activeElement.value] || null);
const activeElementLabel = computed(
  () => elementTabs.find((el) => el.tag === activeElement.value)?.label || activeElement.value.toUpperCase()
);

const parsedFontSize = computed(() => {
  const raw = String(currentElement.value?.font_size || '16px').trim();
  const match = raw.match(/^([\d.]+)\s*(px|em|rem)?$/i);
  if (!match) return { num: '16', unit: 'px' };
  return {
    num: match[1],
    unit: (match[2] || 'px').toLowerCase(),
  };
});

const updateElementSizeNum = (val) => {
  if (!currentElement.value) return;
  const num = val === '' ? '0' : val;
  currentElement.value.font_size = `${num}${parsedFontSize.value.unit}`;
};

const updateElementSizeUnit = (unit) => {
  if (!currentElement.value) return;
  currentElement.value.font_size = `${parsedFontSize.value.num}${unit}`;
};

const bunnyFontsHref = computed(() => {
  const selected = [
    form.typography.body_font,
    form.typography.heading_font,
    ...Object.values(form.typography.elements || {}).map((el) => el?.font_family),
  ];
  return getBunnyFontsHref(selected);
});

const previewStyle = (tag) => resolveElementStyle(tag, form.typography, form.colors);

const resetDefaults = () => {
  form.colors = {
    primary: '#4f46e5',
    secondary: '#0f172a',
    background: '#f8fafc',
    surface: '#ffffff',
    text: '#1e293b',
    accent: '#6366f1',
  };
  form.typography = {
    body_font: 'Instrument Sans, Inter, sans-serif',
    heading_font: 'Instrument Sans, Inter, sans-serif',
    base_size: '16px',
    heading_weight: '700',
    line_height: '1.6',
    elements: buildElementsState(),
  };
  form.custom_css = '';
};

const save = () => {
  form.post('/admin/theme/general', {
    preserveScroll: true,
  });
};
</script>
