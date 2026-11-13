<template>
  <AdminLayout>
    <div class="space-y-6 max-w-full">
      <!-- Header -->
      <div class="pb-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <div class="flex items-center space-x-2">
            <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
              </svg>
            </span>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Theme &rsaquo; Buttons</h1>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            Configure Primary, Secondary, and Accent button styles for Normal and Hover states with Solid or 2-Color Gradient backgrounds.
          </p>
        </div>

        <!-- Variant Selector Tabs -->
        <div class="inline-flex p-1 bg-slate-100 border border-slate-200 rounded-lg text-xs">
          <button
            v-for="v in variantTabs"
            :key="v.key"
            type="button"
            @click="activeVariant = v.key"
            :class="activeVariant === v.key ? 'bg-white text-indigo-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-3 py-1.5 rounded-md transition"
          >
            {{ v.label }}
          </button>
        </div>
      </div>

      <!-- Main Grid: Left Controls + Right Side Preview Panel -->
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        
        <!-- Left 8 Cols: Normal/Hover & Shape Form -->
        <form @submit.prevent="save" class="xl:col-span-8 space-y-6">
          <!-- Normal & Hover State Editors for Selected Variant -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div
              v-for="stateName in ['normal', 'hover']"
              :key="stateName"
              class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4 text-xs"
            >
              <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <h2 class="text-sm font-bold text-slate-800 capitalize">
                  {{ activeVariant }} &mdash; {{ stateName }} State
                </h2>
                <span
                  :class="stateName === 'normal' ? 'bg-slate-100 text-slate-700' : 'bg-indigo-100 text-indigo-700'"
                  class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase"
                >
                  {{ stateName }}
                </span>
              </div>

              <!-- Background Type: Solid vs 2-Color Gradient -->
              <div class="space-y-1.5">
                <label class="font-semibold text-slate-700 block">Background Fill Type</label>
                <div class="grid grid-cols-2 gap-2">
                  <button
                    type="button"
                    @click="currentVariant[stateName].bg_type = 'solid'"
                    :class="currentVariant[stateName].bg_type === 'solid' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 font-bold' : 'border-slate-200 bg-white text-slate-600'"
                    class="py-2 px-3 rounded-lg border text-center transition"
                  >
                    Solid Color
                  </button>
                  <button
                    type="button"
                    @click="currentVariant[stateName].bg_type = 'gradient'"
                    :class="currentVariant[stateName].bg_type === 'gradient' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 font-bold' : 'border-slate-200 bg-white text-slate-600'"
                    class="py-2 px-3 rounded-lg border text-center transition"
                  >
                    2-Color Gradient
                  </button>
                </div>
              </div>

              <!-- Solid Color Picker -->
              <ThemeColorPicker
                v-if="currentVariant[stateName].bg_type === 'solid'"
                v-model="currentVariant[stateName].bg_color"
                label="Background Color"
                placeholder="#4f46e5 or transparent"
                :allow-transparent="true"
              />

              <!-- 2-Color Gradient Pickers -->
              <div v-else class="space-y-3 p-3 rounded-lg bg-slate-50 border border-slate-200">
                <div class="space-y-3">
                  <ThemeColorPicker
                    v-model="currentVariant[stateName].grad_start"
                    label="Color 1 (Start)"
                  />
                  <ThemeColorPicker
                    v-model="currentVariant[stateName].grad_end"
                    label="Color 2 (End)"
                  />
                </div>

                <div class="space-y-1">
                  <label class="font-semibold text-slate-700 block">Gradient Direction</label>
                  <select v-model="currentVariant[stateName].grad_angle" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg bg-white">
                    <option value="90deg">Horizontal (90deg &rarr;)</option>
                    <option value="135deg">Diagonal (135deg &searr;)</option>
                    <option value="180deg">Vertical (180deg &darr;)</option>
                    <option value="45deg">Diagonal Up (45deg &nearr;)</option>
                  </select>
                </div>
              </div>

              <!-- Text & Border Colors -->
              <div class="space-y-3 pt-1 border-t border-slate-100">
                <ThemeColorPicker
                  v-model="currentVariant[stateName].text_color"
                  label="Text Color"
                />
                <ThemeColorPicker
                  v-model="currentVariant[stateName].border_color"
                  label="Border Color"
                  :allow-transparent="true"
                />
              </div>
            </div>
          </div>

          <!-- Shared Button Shape & Spacing -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-4 text-xs">
            <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Global Button Shape &amp; Spacing</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Border Radius</label>
                <input v-model="form.shape.border_radius" type="text" placeholder="8px" class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono bg-slate-50" />
              </div>
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Padding X</label>
                <input v-model="form.shape.padding_x" type="text" placeholder="18px" class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono bg-slate-50" />
              </div>
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Padding Y</label>
                <input v-model="form.shape.padding_y" type="text" placeholder="9px" class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono bg-slate-50" />
              </div>
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Font Weight</label>
                <select v-model="form.shape.font_weight" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50">
                  <option value="500">500 (Medium)</option>
                  <option value="600">600 (SemiBold)</option>
                  <option value="700">700 (Bold)</option>
                </select>
              </div>
            </div>
          </div>

          <div class="flex flex-wrap items-center justify-between gap-3">
            <button
              type="button"
              @click="syncFromThemeColors"
              class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition"
            >
              Sync Defaults from Theme Colors
            </button>

            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition disabled:opacity-50"
            >
              {{ form.processing ? 'Saving...' : 'Save Button Styles' }}
            </button>
          </div>
        </form>

        <!-- Right 4 Cols: Sticky Side Preview Panel -->
        <aside class="xl:col-span-4 xl:sticky xl:top-20 space-y-4">
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-700">
                Live Button Preview
              </span>
              <span class="text-[10px] font-mono text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                .theme-btn-{{ activeVariant }}
              </span>
            </div>

            <component :is="'style'">{{ previewCss }}</component>

            <div class="p-5 space-y-5">
              <p class="text-[11px] text-slate-500">
                Hover over each button below to preview its hover transition live.
              </p>

              <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-4">
                <div class="space-y-1.5">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Primary Button</span>
                  <button type="button" @click="activeVariant = 'primary'" class="theme-preview-btn-primary">
                    Primary Button
                  </button>
                </div>

                <div class="space-y-1.5 pt-3 border-t border-slate-200/70">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Secondary Button</span>
                  <button type="button" @click="activeVariant = 'secondary'" class="theme-preview-btn-secondary">
                    Secondary Button
                  </button>
                </div>

                <div class="space-y-1.5 pt-3 border-t border-slate-200/70">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Accent / Outline Button</span>
                  <button type="button" @click="activeVariant = 'accent'" class="theme-preview-btn-accent">
                    Accent / Outline
                  </button>
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
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ThemeColorPicker from '@/Components/ThemeColorPicker.vue';

const props = defineProps({
  buttons: Object,
});

const page = usePage();
const activeVariant = ref('primary');
const variantTabs = [
  { key: 'primary', label: 'Primary Button' },
  { key: 'secondary', label: 'Secondary Button' },
  { key: 'accent', label: 'Accent / Outline' },
];

const form = useForm({
  shape: {
    border_radius: props.buttons?.shape?.border_radius || '8px',
    padding_x: props.buttons?.shape?.padding_x || '18px',
    padding_y: props.buttons?.shape?.padding_y || '9px',
    font_weight: props.buttons?.shape?.font_weight || '600',
  },
  variants: JSON.parse(JSON.stringify(props.buttons?.variants || {})),
});

const currentVariant = computed(() => form.variants[activeVariant.value]);

const syncFromThemeColors = () => {
  const colors = page.props.theme?.general?.colors || {};
  const primary = colors.primary || '#4f46e5';
  const secondary = colors.secondary || '#0f172a';
  const accent = colors.accent || '#6366f1';

  form.variants.primary.normal = {
    ...form.variants.primary.normal,
    bg_type: 'solid',
    bg_color: primary,
    grad_start: primary,
    grad_end: accent,
    text_color: '#ffffff',
    border_color: primary,
  };
  form.variants.primary.hover = {
    ...form.variants.primary.hover,
    bg_type: 'solid',
    bg_color: accent,
    grad_start: primary,
    grad_end: secondary,
    text_color: '#ffffff',
    border_color: accent,
  };

  form.variants.secondary.normal = {
    ...form.variants.secondary.normal,
    bg_type: 'solid',
    bg_color: secondary,
    grad_start: secondary,
    grad_end: primary,
    text_color: '#ffffff',
    border_color: secondary,
  };
  form.variants.secondary.hover = {
    ...form.variants.secondary.hover,
    bg_type: 'solid',
    bg_color: primary,
    grad_start: secondary,
    grad_end: accent,
    text_color: '#ffffff',
    border_color: primary,
  };

  form.variants.accent.normal = {
    ...form.variants.accent.normal,
    bg_type: 'solid',
    bg_color: 'transparent',
    grad_start: accent,
    grad_end: primary,
    text_color: accent,
    border_color: accent,
  };
  form.variants.accent.hover = {
    ...form.variants.accent.hover,
    bg_type: 'solid',
    bg_color: accent,
    grad_start: accent,
    grad_end: primary,
    text_color: '#ffffff',
    border_color: accent,
  };
};

const stateBg = (s) =>
  s.bg_type === 'gradient'
    ? `linear-gradient(${s.grad_angle || '135deg'}, ${s.grad_start}, ${s.grad_end})`
    : s.bg_color;

const previewCss = computed(() => {
  const { border_radius, padding_x, padding_y, font_weight } = form.shape;
  return ['primary', 'secondary', 'accent']
    .map((key) => {
      const v = form.variants[key];
      if (!v) return '';
      return `
        .theme-preview-btn-${key} {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border-radius: ${border_radius};
          padding: ${padding_y} ${padding_x};
          font-weight: ${font_weight};
          font-size: 13px;
          border: 1px solid ${v.normal.border_color};
          background: ${stateBg(v.normal)};
          color: ${v.normal.text_color};
          transition: all 0.2s ease;
          cursor: pointer;
        }
        .theme-preview-btn-${key}:hover {
          border-color: ${v.hover.border_color};
          background: ${stateBg(v.hover)};
          color: ${v.hover.text_color};
        }
      `;
    })
    .join('\n');
});

const save = () => {
  form.post('/admin/theme/buttons');
};
</script>
