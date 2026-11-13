<template>
  <AdminLayout>
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
            Configure global site Colors, Typography, and Custom CSS applied across public pages.
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

      <form @submit.prevent="save" class="space-y-6">
        <!-- TAB 1: COLORS -->
        <div v-show="activeTab === 'colors'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <h2 class="text-sm font-bold text-slate-800">Global Color Palette</h2>
              <p class="text-xs text-slate-500">Exposed as CSS variables (<code>--theme-primary</code>, <code>--theme-bg</code>, etc.).</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
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

          <!-- Live Color Preview -->
          <div
            class="p-5 rounded-xl border border-slate-200 space-y-3"
            :style="{ backgroundColor: form.colors.background, color: form.colors.text }"
          >
            <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Live Palette Preview</span>
            <div class="p-4 rounded-lg border border-slate-200/60 shadow-2xs space-y-2" :style="{ backgroundColor: form.colors.surface }">
              <h4 class="text-base font-bold" :style="{ color: form.colors.secondary }">Sample Card Heading</h4>
              <p class="text-xs opacity-85">Body text appears in your selected text color on top of the surface color.</p>
              <div class="flex items-center space-x-2 pt-1">
                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white" :style="{ backgroundColor: form.colors.primary }">
                  Primary Button
                </span>
                <span class="text-xs font-semibold underline" :style="{ color: form.colors.accent }">
                  Accent Link
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB 2: TYPOGRAPHY -->
        <div v-show="activeTab === 'typography'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-6 space-y-5">
          <div class="border-b border-slate-100 pb-3">
            <h2 class="text-sm font-bold text-slate-800">Global Typography</h2>
            <p class="text-xs text-slate-500">Controls default fonts, sizes, and weights across public pages.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Body Font Family</label>
              <select v-model="form.typography.body_font" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                <option v-for="f in fontOptions" :key="f.value" :value="f.value">{{ f.label }}</option>
              </select>
            </div>

            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Heading Font Family</label>
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
              <label class="font-semibold text-slate-700 block">Heading Font Weight</label>
              <select v-model="form.typography.heading_weight" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                <option value="600">600 (SemiBold)</option>
                <option value="700">700 (Bold)</option>
                <option value="800">800 (ExtraBold)</option>
              </select>
            </div>

            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Body Line Height</label>
              <select v-model="form.typography.line_height" class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-800">
                <option value="1.4">1.4 (Tight)</option>
                <option value="1.6">1.6 (Balanced)</option>
                <option value="1.75">1.75 (Relaxed)</option>
                <option value="1.9">1.9 (Spacious)</option>
              </select>
            </div>
          </div>

          <!-- Live Typography Preview -->
          <div class="p-5 rounded-xl border border-slate-200 bg-slate-50 space-y-2">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Live Typography Preview</span>
            <h3
              class="text-2xl text-slate-900"
              :style="{ fontFamily: form.typography.heading_font, fontWeight: form.typography.heading_weight }"
            >
              The Quick Brown Fox Jumps Over the Lazy Dog
            </h3>
            <p
              class="text-slate-600"
              :style="{
                fontFamily: form.typography.body_font,
                fontSize: form.typography.base_size,
                lineHeight: form.typography.line_height,
              }"
            >
              This is how your body paragraphs, article summaries, and standard content will look across your public site.
            </p>
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  general: Object,
});

const activeTab = ref('colors');

const colorFields = [
  { key: 'primary', label: 'Primary Color', varName: '--theme-primary' },
  { key: 'secondary', label: 'Secondary / Heading', varName: '--theme-secondary' },
  { key: 'background', label: 'Page Background', varName: '--theme-bg' },
  { key: 'surface', label: 'Card / Surface', varName: '--theme-surface' },
  { key: 'text', label: 'Body Text Color', varName: '--theme-text' },
  { key: 'accent', label: 'Link / Accent', varName: '--theme-accent' },
];

const fontOptions = [
  { label: 'Instrument Sans / Inter (Default)', value: 'Instrument Sans, Inter, sans-serif' },
  { label: 'Inter', value: 'Inter, system-ui, sans-serif' },
  { label: 'System UI (Native)', value: 'system-ui, -apple-system, BlinkMacSystemFont, sans-serif' },
  { label: 'Georgia (Classic Serif)', value: 'Georgia, Cambria, serif' },
  { label: 'JetBrains Mono / Monospace', value: 'ui-monospace, SFMono-Regular, Menlo, monospace' },
];

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
  },
  custom_css: props.general?.custom_css || '',
});

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
  };
  form.custom_css = '';
};

const save = () => {
  form.post('/admin/theme/general');
};
</script>

