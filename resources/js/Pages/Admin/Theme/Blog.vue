<template>
  <AdminLayout>
    <Head title="Theme › Blog" />

    <div class="max-w-5xl mx-auto space-y-6">
      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Blog Layout</h1>
          <p class="text-sm text-slate-500 mt-1">
            Choose how your blog homepage displays posts when Blog mode is active.
          </p>
        </div>

        <button
          type="button"
          @click="submit"
          :disabled="form.processing"
          class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold shadow-xs hover:bg-indigo-700 disabled:opacity-50 transition cursor-pointer"
        >
          <span v-if="form.processing">Saving...</span>
          <span v-else>Save Blog Style</span>
        </button>
      </div>

      <!-- Style Selector Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <button
          v-for="style in styles"
          :key="style.id"
          type="button"
          @click="form.layout_style = style.id"
          :class="[
            'text-left rounded-2xl border-2 bg-white p-5 transition-all flex flex-col justify-between cursor-pointer',
            form.layout_style === style.id
              ? 'border-indigo-600 ring-4 ring-indigo-50 shadow-md'
              : 'border-slate-200 hover:border-slate-300 shadow-2xs',
          ]"
        >
          <div class="space-y-4">
            <!-- Header & Radio Badge -->
            <div class="flex items-start justify-between gap-2">
              <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">
                  {{ style.badge }}
                </span>
                <h2 class="text-base font-bold text-slate-900 mt-0.5">
                  {{ style.name }}
                </h2>
              </div>
              <span
                :class="[
                  'w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 mt-1',
                  form.layout_style === style.id
                    ? 'border-indigo-600 bg-indigo-600'
                    : 'border-slate-300 bg-white',
                ]"
              >
                <span
                  v-if="form.layout_style === style.id"
                  class="w-2 h-2 rounded-full bg-white"
                />
              </span>
            </div>

            <!-- Wireframe Preview -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 space-y-2.5">
              <!-- Style 1 Wireframe: Hero + 4 Posts + Highlighted + Pagination -->
              <template v-if="style.id === 'style_1'">
                <div class="h-14 rounded-lg bg-indigo-100 border border-indigo-200 flex items-center justify-between px-3">
                  <div class="w-16 h-8 rounded bg-indigo-300/70" />
                  <div class="space-y-1.5 flex-1 ml-2.5">
                    <div class="h-2 w-12 rounded bg-indigo-400/80" />
                    <div class="h-2.5 w-3/4 rounded bg-slate-700/60" />
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-1.5">
                  <div v-for="n in 4" :key="n" class="h-8 rounded bg-white border border-slate-200 p-1.5 space-y-1">
                    <div class="h-3 rounded bg-slate-200" />
                    <div class="h-1.5 w-3/4 rounded bg-slate-300" />
                  </div>
                </div>
                <div class="h-10 rounded-lg bg-amber-50 border border-amber-200 flex items-center px-2.5 gap-2">
                  <div class="w-10 h-6 rounded bg-amber-300/70 shrink-0" />
                  <div class="space-y-1 flex-1">
                    <div class="h-1.5 w-10 rounded bg-amber-500/80" />
                    <div class="h-2 w-2/3 rounded bg-slate-600/60" />
                  </div>
                </div>
                <div class="flex justify-center gap-1 pt-0.5">
                  <span class="w-4 h-2.5 rounded bg-indigo-500" />
                  <span class="w-4 h-2.5 rounded bg-slate-300" />
                  <span class="w-4 h-2.5 rounded bg-slate-300" />
                </div>
              </template>

              <!-- Style 2 Wireframe: 2-Column Grid + Sidebar + Pagination -->
              <template v-else-if="style.id === 'style_2'">
                <div class="grid grid-cols-12 gap-2">
                  <div class="col-span-8 grid grid-cols-2 gap-1.5">
                    <div v-for="n in 4" :key="n" class="h-14 rounded bg-white border border-slate-200 p-1.5 space-y-1.5">
                      <div class="h-6 rounded bg-slate-200" />
                      <div class="h-1.5 w-4/5 rounded bg-slate-300" />
                      <div class="h-1.5 w-1/2 rounded bg-slate-200" />
                    </div>
                  </div>
                  <div class="col-span-4 space-y-1.5">
                    <div class="h-14 rounded bg-white border border-slate-200 p-1.5 space-y-1">
                      <div class="h-1.5 w-2/3 rounded bg-indigo-400" />
                      <div class="h-1.5 w-full rounded bg-slate-200" />
                      <div class="h-1.5 w-3/4 rounded bg-slate-200" />
                    </div>
                    <div class="h-14 rounded bg-indigo-50 border border-indigo-200 p-1.5 space-y-1">
                      <div class="h-1.5 w-1/2 rounded bg-indigo-500" />
                      <div class="h-5 rounded bg-indigo-200/70" />
                    </div>
                  </div>
                </div>
                <div class="flex justify-center gap-1 pt-1">
                  <span class="w-4 h-2.5 rounded bg-indigo-500" />
                  <span class="w-4 h-2.5 rounded bg-slate-300" />
                  <span class="w-4 h-2.5 rounded bg-slate-300" />
                </div>
              </template>

              <!-- Style 3 Wireframe: Minimalist Horizontal List + Pagination -->
              <template v-else>
                <div class="space-y-2">
                  <div v-for="n in 3" :key="n" class="h-10 rounded-lg bg-white border border-slate-200 p-1.5 flex items-center gap-2">
                    <div class="w-12 h-full rounded bg-slate-200 shrink-0" />
                    <div class="flex-1 space-y-1">
                      <div class="h-2 w-3/4 rounded bg-slate-400/70" />
                      <div class="h-1.5 w-full rounded bg-slate-200" />
                    </div>
                  </div>
                </div>
                <div class="flex justify-center gap-1 pt-1">
                  <span class="w-4 h-2.5 rounded bg-indigo-500" />
                  <span class="w-4 h-2.5 rounded bg-slate-300" />
                  <span class="w-4 h-2.5 rounded bg-slate-300" />
                </div>
              </template>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
              {{ style.description }}
            </p>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap gap-1.5">
            <span
              v-for="tag in style.features"
              :key="tag"
              class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold"
            >
              {{ tag }}
            </span>
          </div>
        </button>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  blog: Object,
});

const styles = [
  {
    id: 'style_1',
    badge: 'Style 1',
    name: 'Editorial Showcase',
    description:
      'Features a bold Hero Post banner, followed by a 4-post grid, a Highlighted Post spotlight, remaining articles, and pagination.',
    features: ['Hero Post', '4 Posts Grid', 'Highlighted Post', 'Pagination'],
  },
  {
    id: 'style_2',
    badge: 'Style 2',
    name: 'Classic 2-Column + Sidebar',
    description:
      'Clean 2-column article grid paired with a sticky right sidebar displaying Categories and a Highlighted Post.',
    features: ['2-Column Grid', 'Category Sidebar', 'Highlighted Post', 'Pagination'],
  },
  {
    id: 'style_3',
    badge: 'Style 3',
    name: 'Minimalist Horizontal List',
    description:
      'Focused reading feed with horizontal list cards (thumbnail on the left, details on the right) and clean pagination.',
    features: ['Horizontal Cards', 'Minimalist Feed', 'Fast Scanning', 'Pagination'],
  },
];

const form = useForm({
  layout_style: props.blog?.layout_style || 'style_1',
});

const submit = () => {
  form.post('/admin/theme/blog', {
    preserveScroll: true,
  });
};
</script>
