<template>
  <div class="min-h-screen bg-slate-50 flex flex-col antialiased text-slate-800">
    <Head>
      <title>{{ seo.title }}</title>
      <meta name="description" :content="seo.description" />
      <meta name="robots" :content="seo.robots_index ? 'index,follow' : 'noindex,nofollow'" />
    </Head>

    <!-- Public Navigation Bar -->
    <header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-30 shadow-2xs">
      <div class="max-w-6xl mx-auto px-4 h-full flex items-center justify-between">
        <Link href="/" class="flex items-center space-x-2 text-indigo-600 font-bold text-lg tracking-tight">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
          <span class="text-slate-900">{{ $page.props.settings?.site_name || 'Modern CMS' }}</span>
        </Link>

        <div class="flex items-center space-x-4">
          <Link href="/about" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
            Visual Builder Page
          </Link>
          <Link href="/building-a-zero-overhead-modern-cms" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
            TipTap Post
          </Link>
          <Link 
            href="/admin" 
            class="px-3.5 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition"
          >
            Admin Dashboard &rarr;
          </Link>
        </div>
      </div>
    </header>

    <!-- Hero Header -->
    <section class="py-16 bg-linear-to-b from-white to-slate-50 border-b border-slate-200 text-center px-4">
      <div class="max-w-3xl mx-auto space-y-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
          Laravel 11 + Vue 3 + Automated WebP Pipeline
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
          {{ $page.props.settings?.site_name || 'Modern CMS Platform' }}
        </h1>
        <p class="text-base text-slate-600 leading-relaxed">
          {{ $page.props.settings?.site_tagline || 'Zero hooks, zero legacy debt. Combining TipTap classic prose editing and Elementor visual tree architecture.' }}
        </p>
      </div>
    </section>

    <!-- Main Content Area -->
    <main class="max-w-6xl mx-auto px-4 py-12 flex-1 w-full space-y-12">
      
      <!-- Featured Post (if available) -->
      <div v-if="featured" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col md:flex-row hover:shadow-md transition">
        <div class="md:w-1/2 aspect-video md:aspect-auto">
          <img 
            :src="featured.featured_image" 
            :alt="featured.title" 
            class="w-full h-full object-cover"
            loading="lazy"
          />
        </div>
        <div class="p-8 md:w-1/2 flex flex-col justify-between space-y-4">
          <div class="space-y-2">
            <div class="flex items-center space-x-2">
              <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-indigo-100 text-indigo-800">
                Featured {{ featured.type }}
              </span>
              <span class="text-xs text-slate-400">
                {{ new Date(featured.published_at || featured.created_at).toLocaleDateString() }}
              </span>
            </div>
            <Link :href="`/${featured.slug}`" class="block text-2xl font-bold text-slate-900 hover:text-indigo-600 transition">
              {{ featured.title }}
            </Link>
            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
              {{ featured.excerpt || 'Read this featured article to explore the full architecture.' }}
            </p>
          </div>
          <div>
            <Link :href="`/${featured.slug}`" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center space-x-1">
              <span>Read Full Content &rarr;</span>
            </Link>
          </div>
        </div>
      </div>

      <!-- Articles Grid -->
      <div class="space-y-6">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-bold text-slate-900">
            Latest Publications
          </h2>
          <span class="text-xs text-slate-500 font-medium">
            {{ posts.total }} Published Articles & Pages
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div 
            v-for="post in posts.data" 
            :key="post.id"
            class="bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col"
          >
            <!-- Card Image -->
            <div class="aspect-video bg-slate-100 overflow-hidden relative">
              <img 
                :src="post.featured_image || 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80'" 
                :alt="post.title"
                class="w-full h-full object-cover hover:scale-105 transition duration-300"
                loading="lazy"
              />
              <span 
                :class="post.editor_type === 'builder' ? 'bg-purple-900/90 text-purple-200' : 'bg-slate-900/90 text-slate-200'"
                class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider backdrop-blur-xs"
              >
                {{ post.editor_type }}
              </span>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
              <div class="space-y-2">
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                  <span>{{ post.category?.name || 'General' }}</span>
                  <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
                </div>
                <Link :href="`/${post.slug}`" class="block text-sm font-bold text-slate-900 hover:text-indigo-600 transition leading-snug">
                  {{ post.title }}
                </Link>
                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                  {{ post.excerpt || 'Explore this article built with our zero-overhead CMS.' }}
                </p>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">By {{ post.user?.name || 'Admin' }}</span>
                <Link :href="`/${post.slug}`" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                  Read &rarr;
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>

    <!-- Public Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 text-center text-xs text-slate-500">
      <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p>&copy; {{ new Date().getFullYear() }} {{ $page.props.settings?.site_name || 'Modern CMS' }}. Built with Laravel 11 & Vue 3.</p>
        <div class="flex items-center space-x-4">
          <a href="/sitemap.xml" target="_blank" class="hover:text-indigo-600">XML Sitemap</a>
          <a href="/robots.txt" target="_blank" class="hover:text-indigo-600">Robots.txt</a>
          <Link href="/admin" class="hover:text-indigo-600 font-semibold">Admin Panel</Link>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  posts: Object,
  featured: Object,
  categories: Array,
  seo: Object,
});
</script>

