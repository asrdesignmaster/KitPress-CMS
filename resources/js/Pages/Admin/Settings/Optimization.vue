<template>
  <AdminLayout>
    <div class="space-y-6 max-w-5xl">
      
      <!-- Header -->
      <div class="pb-4 border-b border-slate-200">
        <div class="flex items-center space-x-2">
          <span class="p-1.5 bg-emerald-100 text-emerald-800 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </span>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Site Optimization & Performance Center
          </h1>
        </div>
        <p class="text-xs text-slate-500 mt-1">
          Configure response compression, route and config caching, lazy loading, and one-click database cleanups.
        </p>
      </div>

      <!-- Settings Form -->
      <form @submit.prevent="saveSettings" class="space-y-6">
        
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

      </form>

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
  </AdminLayout>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  settings: Object,
  stats: Object,
});

const form = useForm({
  site_name: props.settings?.site_name || '',
  site_tagline: props.settings?.site_tagline || '',
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

