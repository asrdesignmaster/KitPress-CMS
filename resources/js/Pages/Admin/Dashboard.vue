<template>
  <AdminLayout>
    <div class="space-y-6">
      
      <!-- Welcome Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Dashboard
          </h1>
          <p class="text-xs text-slate-500 mt-1">
            Welcome to your zero-overhead Laravel + Vue 3 CMS control center.
          </p>
        </div>

        <div class="flex items-center space-x-2">
          <Link 
            href="/admin/posts/create?editor=classic" 
            class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 shadow-2xs transition flex items-center space-x-1.5"
          >
            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>+ TipTap Post</span>
          </Link>

          <Link 
            href="/admin/posts/create?editor=builder" 
            class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition flex items-center space-x-1.5"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <span>+ Visual Builder Page</span>
          </Link>
        </div>
      </div>

      <!-- Quick Metrics Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Posts -->
        <div class="p-5 bg-white rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Posts</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.posts_count }}</p>
            <p class="text-[11px] text-emerald-600 font-medium mt-0.5">{{ stats.published_posts_count }} published</p>
          </div>
          <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
          </div>
        </div>

        <!-- Pages -->
        <div class="p-5 bg-white rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Pages</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.pages_count }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Visual Builder & Classic</p>
          </div>
          <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
        </div>

        <!-- Media Assets -->
        <div class="p-5 bg-white rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Media Assets</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.media_count }}</p>
            <p class="text-[11px] text-emerald-600 font-medium mt-0.5">WebP Optimized</p>
          </div>
          <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>

        <!-- Categories & Revisions -->
        <div class="p-5 bg-white rounded-xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
          <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">System State</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.categories_count }} <span class="text-xs font-normal text-slate-400">cats</span></p>
            <p class="text-[11px] text-slate-500 mt-0.5">{{ stats.revisions_count }} revisions stored</p>
          </div>
          <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Main Dashboard Content Columns -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Recent Content & Quick Actions -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Recent Content Table -->
          <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
              <h3 class="text-sm font-semibold text-slate-800">
                Recent Posts & Pages
              </h3>
              <Link href="/admin/posts" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                View all &rarr;
              </Link>
            </div>

            <div class="divide-y divide-slate-100">
              <div 
                v-for="post in recent_posts" 
                :key="post.id"
                class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50/80 transition"
              >
                <div class="flex items-center space-x-3 min-w-0">
                  <!-- Editor Type Badge -->
                  <span 
                    :class="post.editor_type === 'builder' ? 'bg-purple-100 text-purple-800 border-purple-200' : 'bg-blue-100 text-blue-800 border-blue-200'"
                    class="px-2 py-0.5 text-[10px] font-bold rounded uppercase border shrink-0"
                  >
                    {{ post.editor_type }}
                  </span>

                  <div class="min-w-0">
                    <Link :href="`/admin/posts/${post.id}/edit`" class="text-xs font-semibold text-slate-900 hover:text-indigo-600 truncate block">
                      {{ post.title }}
                    </Link>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                      By {{ post.user?.name || 'Admin' }} &bull; {{ post.category?.name || 'Uncategorized' }} &bull; /{{ post.slug }}
                    </p>
                  </div>
                </div>

                <div class="flex items-center space-x-2 shrink-0">
                  <span 
                    :class="post.status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                    class="px-2 py-0.5 text-[10px] font-semibold rounded-full"
                  >
                    {{ post.status }}
                  </span>
                  <Link :href="`/admin/posts/${post.id}/edit`" class="text-xs text-slate-400 hover:text-indigo-600 p-1">
                    Edit
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Media Grid -->
          <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs p-5">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-sm font-semibold text-slate-800">
                Recent Optimized WebP Media
              </h3>
              <Link href="/admin/media" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                Open Media Library &rarr;
              </Link>
            </div>

            <div v-if="recent_media.length === 0" class="py-8 text-center text-slate-400 text-xs">
              No media uploaded yet.
            </div>

            <div v-else class="grid grid-cols-4 sm:grid-cols-8 gap-2">
              <div 
                v-for="item in recent_media" 
                :key="item.id"
                class="group relative aspect-square bg-slate-100 rounded-lg overflow-hidden border border-slate-200"
              >
                <img 
                  :src="item.sizes?.thumbnail?.url || item.url" 
                  :alt="item.name"
                  class="w-full h-full object-cover group-hover:scale-105 transition"
                  loading="lazy"
                />
                <span class="absolute top-1 left-1 px-1 py-0.2 text-[8px] font-bold bg-slate-900/80 text-white rounded">
                  WebP
                </span>
              </div>
            </div>
          </div>

        </div>

        <!-- Right 1 Col: Server Architecture & Quick Tips -->
        <div class="space-y-6">
          
          <!-- System Status Box -->
          <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs p-5 space-y-3">
            <h3 class="text-sm font-semibold text-slate-800 flex items-center space-x-2">
              <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <circle cx="10" cy="10" r="6" />
              </svg>
              <span>Platform Health</span>
            </h3>

            <div class="divide-y divide-slate-100 text-xs text-slate-600">
              <div class="py-2 flex justify-between">
                <span class="text-slate-400">PHP Version</span>
                <span class="font-mono font-medium text-slate-800">{{ server_info.php_version }}</span>
              </div>
              <div class="py-2 flex justify-between">
                <span class="text-slate-400">Laravel Framework</span>
                <span class="font-mono font-medium text-slate-800">v{{ server_info.laravel_version }}</span>
              </div>
              <div class="py-2 flex justify-between">
                <span class="text-slate-400">Frontend Stack</span>
                <span class="font-medium text-indigo-600">Vue 3 + Inertia + Tailwind</span>
              </div>
              <div class="py-2 flex justify-between">
                <span class="text-slate-400">Database Driver</span>
                <span class="font-mono font-medium text-slate-800">{{ server_info.database }}</span>
              </div>
              <div class="py-2 flex justify-between">
                <span class="text-slate-400">Image Processor</span>
                <span class="font-medium text-emerald-600">GD / WebP Interceptor</span>
              </div>
            </div>

            <div class="pt-2">
              <Link 
                href="/admin/settings/optimization" 
                class="block w-full py-2 text-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100/70 rounded-lg transition"
              >
                Go to Optimization Panel &rarr;
              </Link>
            </div>
          </div>

          <!-- Architecture Highlight Box -->
          <div class="bg-linear-to-br from-indigo-900 to-slate-900 text-white rounded-xl shadow-xs p-5 space-y-3">
            <div class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
              WordPress Replacement
            </div>
            <h4 class="text-sm font-bold text-white">
              Zero Hooks, Zero Legacy
            </h4>
            <p class="text-xs text-slate-300 leading-relaxed">
              Every request runs without the weight of 5,000 WordPress hooks or unindexed options queries. Your content is stored as pristine HTML or clean JSON tree structures.
            </p>
          </div>

        </div>

      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  stats: Object,
  recent_posts: Array,
  recent_media: Array,
  server_info: Object,
});
</script>

