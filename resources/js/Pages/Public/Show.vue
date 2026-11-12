<template>
  <div class="min-h-screen bg-slate-50 flex flex-col antialiased text-slate-800">
    <!-- Dynamic SEO Suite Meta Headers -->
    <Head>
      <title>{{ seo.title }}</title>
      <meta name="description" :content="seo.description" />
      <link rel="canonical" :href="seo.canonical" />
      <meta name="robots" :content="seo.robots_index ? 'index,follow' : 'noindex,nofollow'" />

      <!-- OpenGraph Meta -->
      <meta property="og:title" :content="seo.og_title" />
      <meta property="og:description" :content="seo.og_description" />
      <meta property="og:url" :content="seo.canonical" />
      <meta property="og:type" :content="post.type === 'page' ? 'website' : 'article'" />
      <meta v-if="seo.og_image" property="og:image" :content="seo.og_image" />

      <!-- Twitter Card -->
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" :content="seo.og_title" />
      <meta name="twitter:description" :content="seo.og_description" />
      <meta v-if="seo.og_image" name="twitter:image" :content="seo.og_image" />
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
          <Link href="/" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
            Home
          </Link>
          <Link 
            v-if="$page.props.auth?.user" 
            :href="`/admin/posts/${post.id}/edit`" 
            class="px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200 transition"
          >
            Edit in CMS
          </Link>
          <Link 
            href="/admin" 
            class="px-3.5 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition"
          >
            Dashboard
          </Link>
        </div>
      </div>
    </header>

    <!-- CONTENT RENDERING -->
    <!-- Mode 2: Visual Builder Layout Tree Rendering (Elementor Pro style) -->
    <div v-if="post.editor_type === 'builder'" class="flex-1">
      <div 
        v-for="section in post.builder_json" 
        :key="section.id"
        :style="{
          backgroundColor: section.backgroundColor || '#ffffff',
          color: section.textColor || '#1e293b',
          paddingTop: section.paddingTop || '48px',
          paddingBottom: section.paddingBottom || '48px',
        }"
      >
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row gap-6">
          <div 
            v-for="column in section.columns" 
            :key="column.id"
            :style="{ width: column.width || '100%' }"
            class="space-y-4"
          >
            <!-- Render Column Widgets -->
            <div v-for="widget in column.widgets" :key="widget.id">
              
              <!-- Heading -->
              <component 
                v-if="widget.type === 'heading'"
                :is="widget.tag || 'h2'"
                :style="{
                  color: widget.color || 'inherit',
                  fontSize: widget.fontSize,
                  textAlign: widget.textAlign || 'left',
                  marginBottom: widget.marginBottom || '0.5rem',
                }"
                class="font-bold tracking-tight"
              >
                {{ widget.content }}
              </component>

              <!-- Paragraph / Text -->
              <p 
                v-else-if="widget.type === 'text'"
                :style="{
                  color: widget.color || 'inherit',
                  fontSize: widget.fontSize || '16px',
                  textAlign: widget.textAlign || 'left',
                  lineHeight: widget.lineHeight || '1.7',
                  marginBottom: widget.marginBottom || '0.75rem',
                }"
              >
                {{ widget.content }}
              </p>

              <!-- WebP Image -->
              <div 
                v-else-if="widget.type === 'image'"
                :style="{ textAlign: widget.textAlign || 'center' }"
              >
                <img 
                  :src="widget.url" 
                  :alt="widget.alt || post.title"
                  :style="{ borderRadius: widget.borderRadius || '8px' }"
                  class="inline-block max-w-full h-auto shadow-xs"
                  loading="lazy"
                />
              </div>

              <!-- Button -->
              <div 
                v-else-if="widget.type === 'button'"
                :style="{ textAlign: widget.textAlign || 'left' }"
              >
                <a 
                  :href="widget.url || '#'" 
                  :style="{
                    backgroundColor: widget.backgroundColor || '#4f46e5',
                    color: widget.textColor || '#ffffff',
                    borderRadius: widget.borderRadius || '8px',
                    padding: '10px 24px',
                  }"
                  class="inline-block text-xs font-semibold shadow-xs hover:opacity-90 transition"
                >
                  {{ widget.content }}
                </a>
              </div>

              <!-- Spacer -->
              <div 
                v-else-if="widget.type === 'spacer'"
                :style="{ height: widget.height || '32px' }"
                class="w-full"
              ></div>

              <!-- Divider -->
              <hr 
                v-else-if="widget.type === 'divider'"
                class="my-4 border-t border-slate-300"
              />

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mode 1: Classic TipTap Prose Rendering -->
    <main v-else class="max-w-4xl mx-auto px-4 py-12 flex-1 w-full space-y-8">
      
      <!-- Article Header -->
      <div class="space-y-4 pb-6 border-b border-slate-200">
        <div class="flex items-center space-x-2 text-xs font-semibold text-indigo-600">
          <span v-if="post.category" class="bg-indigo-50 px-2.5 py-1 rounded-full">
            {{ post.category.name }}
          </span>
          <span class="text-slate-400">&bull;</span>
          <span class="text-slate-500">
            {{ new Date(post.published_at || post.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}
          </span>
        </div>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
          {{ post.title }}
        </h1>

        <div class="flex items-center space-x-3 text-xs text-slate-500">
          <span class="font-medium text-slate-700">By {{ post.user?.name || 'Altamash' }}</span>
          <span v-if="post.user?.bio" class="text-slate-400">({{ post.user.bio }})</span>
        </div>
      </div>

      <!-- Featured Image -->
      <div v-if="post.featured_image" class="rounded-2xl overflow-hidden aspect-video bg-slate-100 border border-slate-200 shadow-xs">
        <img 
          :src="post.featured_image" 
          :alt="post.title" 
          class="w-full h-full object-cover"
          loading="lazy"
        />
      </div>

      <!-- TipTap Prose Body -->
      <article 
        class="prose prose-indigo max-w-none text-slate-800 tiptap-content bg-white p-8 rounded-2xl border border-slate-200/90 shadow-2xs leading-relaxed text-base"
        v-html="post.classic_content"
      ></article>

      <!-- Tags List -->
      <div v-if="post.tags && post.tags.length" class="flex flex-wrap items-center gap-2 pt-4">
        <span class="text-xs font-semibold text-slate-400">Tags:</span>
        <span 
          v-for="tag in post.tags" 
          :key="tag.id"
          class="px-2.5 py-1 bg-slate-200/70 text-slate-700 rounded-lg text-xs font-medium"
        >
          #{{ tag.name }}
        </span>
      </div>

    </main>

    <!-- Public Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 text-center text-xs text-slate-500 mt-12">
      <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p>&copy; {{ new Date().getFullYear() }} {{ $page.props.settings?.site_name || 'Modern CMS' }}. High-Performance Architecture.</p>
        <div class="flex items-center space-x-4">
          <Link href="/" class="hover:text-indigo-600">Home</Link>
          <a href="/sitemap.xml" target="_blank" class="hover:text-indigo-600">Sitemap</a>
          <Link href="/admin" class="hover:text-indigo-600 font-semibold">Admin Panel</Link>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  post: Object,
  recent: Array,
  seo: Object,
});
</script>

