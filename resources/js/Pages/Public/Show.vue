<template>
  <PublicLayout>
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

    <template #header-extra>
      <Link 
        v-if="$page.props.auth?.user" 
        :href="`/admin/posts/${post.id}/edit`" 
        class="px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200 transition whitespace-nowrap"
      >
        Edit in CMS
      </Link>
    </template>

    <!-- CONTENT RENDERING -->
    <!-- Mode 2: Visual Builder Layout Tree Rendering (Elementor Pro style) -->
    <div v-if="post.editor_type === 'builder'" class="flex-1">
      <div 
        v-for="(section, sIdx) in post.builder_json" 
        :key="section.id"
        :style="{
          backgroundColor: section.backgroundColor || '#ffffff',
          backgroundImage: section.backgroundGradient || 'none',
          color: section.textColor || '#1e293b',
          paddingTop: getSectionPaddingTop(section, sIdx),
          paddingBottom: section.paddingBottom || '48px',
        }"
      >
        <div
          :class="[
            section.containerWidth === 'full' ? 'w-full px-4' : 'max-w-6xl mx-auto px-4',
            section.stackColumns ? 'flex-col' : 'flex-col md:flex-row'
          ]"
          class="flex gap-6"
        >
          <div 
            v-for="column in section.columns" 
            :key="column.id"
            :style="{
              '--col-width': section.stackColumns ? '100%' : (column.width || '100%'),
              backgroundColor: column.backgroundColor || 'transparent',
              padding: column.padding || '0px',
              borderRadius: column.borderRadius || '0px',
            }"
            :class="section.stackColumns ? 'w-full space-y-4' : 'builder-flex-col space-y-4'"
          >
            <BuilderWidgetRenderer
              v-for="widget in column.widgets"
              :key="widget.id"
              :widget="widget"
              :is-editor="false"
            />
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
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import BuilderWidgetRenderer from '@/Components/Builder/BuilderWidgetRenderer.vue';

defineProps({
  post: Object,
  recent: Array,
  seo: Object,
});

const page = usePage();
const isTransparentHomeHeader = computed(() => {
  const header = page.props.theme?.header || {};
  return Boolean(page.props.isHome) && Boolean(header.transparent);
});

const transparentHeaderHeight = computed(() =>
  page.props.theme?.header?.layout_style === 'style_3' ? '112px' : '64px'
);

const getSectionPaddingTop = (section, idx) => {
  const base = section.paddingTop || '48px';
  if (idx === 0 && isTransparentHomeHeader.value) {
    return `calc(${base} + ${transparentHeaderHeight.value})`;
  }
  return base;
};
</script>

