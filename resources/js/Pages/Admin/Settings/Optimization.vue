<template>
  <AdminLayout>
    <div class="space-y-6 max-w-full">
      
      <!-- Header -->
      <div class="pb-4 border-b border-slate-200">
        <div class="flex items-center space-x-2">
          <span class="p-1.5 bg-emerald-100 text-emerald-800 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </span>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Settings &rsaquo; {{ currentTab?.label }}
          </h1>
        </div>
        <p class="text-xs text-slate-500 mt-1">
          Configure general site identity, homepage reading settings, response compression, caching, and database cleanups.
        </p>
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
              v-if="tab.id === 'general'"
              class="w-4 h-4 shrink-0 mt-0.5"
              :class="activeTab === tab.id ? 'text-indigo-600' : 'text-slate-400'"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <svg
              v-else
              class="w-4 h-4 shrink-0 mt-0.5"
              :class="activeTab === tab.id ? 'text-indigo-600' : 'text-slate-400'"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <div class="min-w-0">
              <div class="truncate">{{ tab.label }}</div>
              <div class="text-[11px] text-slate-400 font-normal truncate">{{ tab.description }}</div>
            </div>
          </button>
        </aside>

        <!-- Right Tab Content -->
        <div class="lg:col-span-9 space-y-6">
          <!-- Settings Form -->
          <form @submit.prevent="saveSettings" class="space-y-6">
        
        <!-- TAB 1: GENERAL -->
        <div v-show="activeTab === 'general'" class="space-y-6">
          <!-- General Identity Card -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
              Site Identity
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Site Name</label>
                <input v-model="form.site_name" type="text" class="w-full px-3 py-2 border rounded-lg bg-slate-50 text-slate-900" />
              </div>
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Tagline / Motto</label>
                <input v-model="form.site_tagline" type="text" class="w-full px-3 py-2 border rounded-lg bg-slate-50 text-slate-900" />
              </div>
            </div>
          </div>

          <!-- Homepage Reading Settings (WordPress Style) -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
              Homepage Displays (Reading Settings)
            </h3>
            <div class="space-y-3 text-xs">
              <label class="flex items-start space-x-3 cursor-pointer">
                <input
                  v-model="form.homepage_display"
                  type="radio"
                  value="latest_posts"
                  class="mt-0.5 text-indigo-600 focus:ring-indigo-500"
                />
                <div>
                  <span class="font-bold text-slate-800 block">Your latest posts</span>
                  <span class="text-slate-500 text-[11px]">Displays the default blog feed and featured articles on the homepage (/).</span>
                </div>
              </label>

              <label class="flex items-start space-x-3 cursor-pointer">
                <input
                  v-model="form.homepage_display"
                  type="radio"
                  value="static_page"
                  class="mt-0.5 text-indigo-600 focus:ring-indigo-500"
                />
                <div class="flex-1 space-y-2">
                  <div>
                    <span class="font-bold text-slate-800 block">A static page or post</span>
                    <span class="text-slate-500 text-[11px]">Choose any published Page or Post (including Visual Builder layouts) as your default homepage.</span>
                  </div>

                  <div v-if="form.homepage_display === 'static_page'" class="max-w-md pt-1">
                    <select
                      v-model="form.homepage_post_id"
                      class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-900 font-medium"
                    >
                      <option :value="null">-- Select a Published Page or Post --</option>
                      <optgroup v-if="publishedPages.length" label="Published Pages">
                        <option v-for="p in publishedPages" :key="'page-' + p.id" :value="p.id">
                          {{ p.title }} (/{{ p.slug }})
                        </option>
                      </optgroup>
                      <optgroup v-if="publishedPosts.length" label="Published Posts">
                        <option v-for="p in publishedPosts" :key="'post-' + p.id" :value="p.id">
                          {{ p.title }} (/{{ p.slug }})
                        </option>
                      </optgroup>
                    </select>
                    <p v-if="!publishedPages.length && !publishedPosts.length" class="text-[11px] text-amber-600 mt-1">
                      No published pages or posts found. Publish a page or post first to set it as the homepage.
                    </p>
                  </div>
                </div>
              </label>
            </div>

            <div class="pt-2">
              <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-xs shadow-xs transition"
              >
                Save General Settings
              </button>
            </div>
          </div>
        </div>

        <!-- TAB 2: SITE OPTIMIZATION (Form Section) -->
        <div v-show="activeTab === 'optimization'" class="space-y-6">
          <!-- Asset Caching & Performance Card -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
          <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center justify-between">
            <span>Asset Caching & Compression Controls</span>
            <span class="text-[11px] font-mono text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
              Driver: {{ stats.cache_driver }}
            </span>
          </h3>

          <div class="divide-y divide-slate-100 text-xs">
            
            <!-- Lazy Loading -->
            <div class="py-3 flex items-center justify-between">
              <div>
                <p class="font-bold text-slate-800">Global Image Lazy Loading</p>
                <p class="text-slate-500 text-[11px]">Enforces native browser lazy loading on all WebP images across public views.</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input v-model="form.lazy_loading" type="checkbox" class="sr-only peer" />
                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
              </label>
            </div>

            <!-- Response Compression -->
            <div class="py-3 flex items-center justify-between">
              <div>
                <p class="font-bold text-slate-800">HTTP Response Compression</p>
                <p class="text-slate-500 text-[11px]">Enables Gzip/Brotli payload compression for HTML and JSON responses.</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input v-model="form.response_compression" type="checkbox" class="sr-only peer" />
                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
              </label>
            </div>

            <!-- Route Caching -->
            <div class="py-3 flex items-center justify-between">
              <div>
                <div class="flex items-center space-x-2">
                  <p class="font-bold text-slate-800">Route Caching</p>
                  <span 
                    :class="stats.is_route_cached ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                    class="px-1.5 py-0.2 rounded text-[10px] font-bold"
                  >
                    {{ stats.is_route_cached ? 'Active' : 'Disabled' }}
                  </span>
                </div>
                <p class="text-slate-500 text-[11px]">Compiles all route declarations into a single fast lookup array.</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input v-model="form.route_caching" type="checkbox" class="sr-only peer" />
                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
              </label>
            </div>

            <!-- Config Caching -->
            <div class="py-3 flex items-center justify-between">
              <div>
                <div class="flex items-center space-x-2">
                  <p class="font-bold text-slate-800">Configuration Caching</p>
                  <span 
                    :class="stats.is_config_cached ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                    class="px-1.5 py-0.2 rounded text-[10px] font-bold"
                  >
                    {{ stats.is_config_cached ? 'Active' : 'Disabled' }}
                  </span>
                </div>
                <p class="text-slate-500 text-[11px]">Boots the framework in milliseconds by merging configuration files.</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input v-model="form.config_caching" type="checkbox" class="sr-only peer" />
                <div class="w-9 h-5 bg-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
              </label>
            </div>

          </div>

          <div class="pt-2">
            <button 
              type="submit" 
              :disabled="form.processing"
              class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-xs shadow-xs transition"
            >
              Save Optimization Configuration
            </button>
          </div>
        </div>
        </div>

      </form>

      <!-- TAB 2: SITE OPTIMIZATION (Cleanup & Cache Tools) -->
      <div v-show="activeTab === 'optimization'" class="space-y-6">
      <!-- Database Cleanup Section -->
      <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider text-rose-700">
          Database & Storage Cleanup Tools
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
          <!-- Purge Revisions -->
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
            <h4 class="font-bold text-slate-800">Purge Post Revisions</h4>
            <p class="text-[11px] text-slate-500">
              Permanently purges auto-saved snapshot revisions. (Currently <strong>{{ stats.revisions_count }}</strong> revisions).
            </p>
            <button 
              type="button" 
              @click="purge('purge-revisions', 'Purge all post revisions?')"
              class="w-full py-1.5 bg-white hover:bg-slate-100 text-slate-800 font-semibold border border-slate-300 rounded-lg transition"
            >
              Purge Revisions
            </button>
          </div>

          <!-- Purge Orphan Media -->
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
            <h4 class="font-bold text-slate-800">Purge Unlinked Media</h4>
            <p class="text-[11px] text-slate-500">
              Detects and removes media assets not attached as featured images. (Currently <strong>{{ stats.orphan_media_count }}</strong> unlinked).
            </p>
            <button 
              type="button" 
              @click="purge('purge-orphans', 'Purge unlinked media assets?')"
              class="w-full py-1.5 bg-white hover:bg-slate-100 text-slate-800 font-semibold border border-slate-300 rounded-lg transition"
            >
              Purge Unlinked
            </button>
          </div>

          <!-- Purge Transient Logs -->
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
            <h4 class="font-bold text-slate-800">Purge System Logs</h4>
            <p class="text-[11px] text-slate-500">
              Truncates oversized <code>laravel.log</code> debug files and query runtime traces.
            </p>
            <button 
              type="button" 
              @click="purge('purge-logs', 'Purge transient logs?')"
              class="w-full py-1.5 bg-white hover:bg-slate-100 text-slate-800 font-semibold border border-slate-300 rounded-lg transition"
            >
              Flush Logs
            </button>
          </div>
        </div>
      </div>

      <!-- Cache Flush Controls -->
      <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider text-indigo-700">
          Instant Cache Invalidation
        </h3>

        <div class="flex flex-wrap items-center gap-2">
          <button 
            type="button" 
            @click="clearCache('all')"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-xs transition"
          >
            Clear Entire Application Cache
          </button>
          <button 
            type="button" 
            @click="clearCache('views')"
            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-lg border border-slate-300 transition"
          >
            Clear View Cache
          </button>
          <button 
            type="button" 
            @click="clearCache('routes')"
            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs rounded-lg border border-slate-300 transition"
          >
            Clear Route Cache
          </button>
        </div>
      </div>
      </div>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const tabs = [
  { id: 'general', label: 'General', description: 'Site identity & reading' },
  { id: 'optimization', label: 'Site Optimization', description: 'Caching, compression & cleanup' },
];

const initialTab = new URLSearchParams(window.location.search).get('tab');
const activeTab = ref(tabs.some(t => t.id === initialTab) ? initialTab : 'general');
const currentTab = computed(() => tabs.find(t => t.id === activeTab.value) || tabs[0]);

const props = defineProps({
  settings: Object,
  stats: Object,
  publishedPages: {
    type: Array,
    default: () => [],
  },
  publishedPosts: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  site_name: props.settings?.site_name || '',
  site_tagline: props.settings?.site_tagline || '',
  homepage_display: props.settings?.homepage_display || 'latest_posts',
  homepage_post_id: props.settings?.homepage_post_id || null,
  lazy_loading: props.settings?.lazy_loading !== undefined ? Boolean(props.settings.lazy_loading) : true,
  response_compression: props.settings?.response_compression !== undefined ? Boolean(props.settings.response_compression) : true,
  route_caching: props.settings?.route_caching !== undefined ? Boolean(props.settings.route_caching) : false,
  config_caching: props.settings?.config_caching !== undefined ? Boolean(props.settings.config_caching) : false,
});

const saveSettings = () => {
  form.post('/admin/settings/optimization');
};

const purge = (endpoint, confirmation) => {
  if (confirm(confirmation)) {
    router.post(`/admin/settings/optimization/${endpoint}`);
  }
};

const clearCache = (type) => {
  router.post('/admin/settings/optimization/clear-cache', { type });
};
</script>

