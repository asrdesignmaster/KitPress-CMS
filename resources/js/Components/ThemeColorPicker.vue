<template>
  <div class="space-y-1.5">
    <label v-if="label" class="font-semibold text-slate-700 block">{{ label }}</label>
    <div class="flex items-center space-x-1.5">
      <input
        :value="colorInputValue"
        @input="$emit('update:modelValue', $event.target.value)"
        type="color"
        class="w-8 h-8 rounded border border-slate-300 cursor-pointer shrink-0 bg-white p-0.5"
      />
      <input
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        type="text"
        :placeholder="placeholder"
        class="w-full px-2 py-1 border border-slate-300 rounded font-mono text-xs bg-white"
      />
    </div>

    <!-- Defined Theme Color Swatches -->
    <div class="flex flex-wrap items-center gap-1 pt-0.5">
      <button
        v-for="swatch in swatches"
        :key="swatch.key"
        type="button"
        @click="$emit('update:modelValue', swatch.value)"
        :title="`${swatch.label}: ${swatch.value}`"
        :class="isSelected(swatch.value) ? 'ring-2 ring-indigo-600 ring-offset-1 border-indigo-600 font-bold text-slate-900' : 'border-slate-200 text-slate-600 hover:border-slate-400'"
        class="inline-flex items-center space-x-1 px-1.5 py-0.5 rounded border bg-white text-[10px] transition"
      >
        <span
          class="w-2.5 h-2.5 rounded-full border border-slate-300 shrink-0"
          :style="swatch.value === 'transparent'
            ? { backgroundImage: 'linear-gradient(45deg, #cbd5e1 25%, transparent 25%), linear-gradient(-45deg, #cbd5e1 25%, transparent 25%)', backgroundSize: '6px 6px' }
            : { backgroundColor: swatch.value }"
        ></span>
        <span>{{ swatch.label }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  modelValue: {
    type: String,
    default: '#4f46e5',
  },
  label: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '#4f46e5',
  },
  allowTransparent: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['update:modelValue']);

const page = usePage();

const swatches = computed(() => {
  const colors = page.props.theme?.general?.colors || {};
  const list = [
    { key: 'primary', label: 'Primary', value: colors.primary || '#4f46e5' },
    { key: 'secondary', label: 'Secondary', value: colors.secondary || '#0f172a' },
    { key: 'accent', label: 'Accent', value: colors.accent || '#6366f1' },
    { key: 'surface', label: 'Surface', value: colors.surface || '#ffffff' },
    { key: 'text', label: 'Text', value: colors.text || '#1e293b' },
    { key: 'bg', label: 'BG', value: colors.background || '#f8fafc' },
  ];
  if (props.allowTransparent) {
    list.push({ key: 'transparent', label: 'None', value: 'transparent' });
  }
  return list;
});

const colorInputValue = computed(() => {
  return /^#[0-9a-fA-F]{6}$/.test(props.modelValue || '') ? props.modelValue : '#4f46e5';
});

const isSelected = (val) => {
  return String(props.modelValue || '').toLowerCase() === String(val || '').toLowerCase();
};
</script>

