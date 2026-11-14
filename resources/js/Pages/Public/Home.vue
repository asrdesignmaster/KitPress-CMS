<template>
  <PublicLayout>
    <Head>
      <title>{{ seo.title }}</title>
      <meta name="description" :content="seo.description" />
      <meta name="robots" :content="seo.robots_index ? 'index,follow' : 'noindex,nofollow'" />
    </Head>

    <!-- Main Content Area -->
    <main class="max-w-6xl mx-auto px-4 py-12 flex-1 w-full space-y-12">

      <!-- STYLE 1: Editorial Showcase (Hero Post + 4 Posts + Highlighted Post + Pagination) -->
      <template v-if="layoutStyle === 'style_1'">
        <!-- 1. Hero Post -->
        <section
          v-if="heroPost"
          class="blog-card rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col md:flex-row hover:shadow-md transition"
        >
          <div class="md:w-1/2 aspect-video md:aspect-auto bg-slate-100">
            <img
              :src="heroPost.featured_image || fallbackImage"
              :alt="heroPost.title"
              class="w-full h-full object-cover"
              loading="lazy"
            />
          </div>
          <div class="p-8 md:w-1/2 flex flex-col justify-between space-y-4">
            <div class="space-y-3">
              <div class="flex flex-wrap items-center gap-2">
                <span class="theme-badge px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase">
                  Hero {{ heroPost.type || 'post' }}
                </span>
                <span v-if="heroPost.category?.name" class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                  {{ heroPost.category.name }}
                </span>
                <span class="text-xs opacity-60">
                  {{ formatDate(heroPost) }}
                </span>
              </div>
              <Link :href="`/${heroPost.slug}`" class="blog-heading blog-title-link block text-2xl sm:text-3xl font-extrabold transition leading-tight">
                {{ heroPost.title }}
              </Link>
              <p class="text-sm opacity-80 line-clamp-3 leading-relaxed">
                {{ heroPost.excerpt || 'Read this featured article to explore the full architecture.' }}
              </p>
            </div>
            <div class="pt-2 flex items-center justify-between gap-4">
              <span class="text-xs opacity-60">By {{ heroPost.user?.name || 'Admin' }}</span>
              <Link :href="`/${heroPost.slug}`" class="theme-btn-primary text-xs shrink-0">
                Read Full Article &rarr;
              </Link>
            </div>
          </div>
        </section>

        <!-- 2. 4 Posts Grid -->
        <section v-if="style1GridPosts.length" class="space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="blog-heading text-xl font-bold">Latest Stories</h2>
            <span class="text-xs opacity-65 font-medium">
              {{ posts.total }} Published Articles
            </span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <article
              v-for="post in style1GridPosts"
              :key="post.id"
              class="blog-card rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col"
            >
              <div class="aspect-video bg-slate-100 overflow-hidden relative">
                <img
                  :src="post.featured_image || fallbackImage"
                  :alt="post.title"
                  class="w-full h-full object-cover hover:scale-105 transition duration-300"
                  loading="lazy"
                />
              </div>
              <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div class="space-y-1.5">
                  <div class="flex items-center justify-between text-[11px] opacity-60">
                    <span>{{ post.category?.name || 'General' }}</span>
                    <span>{{ formatDate(post) }}</span>
                  </div>
                  <Link :href="`/${post.slug}`" class="blog-heading blog-title-link block text-sm font-bold transition leading-snug line-clamp-2">
                    {{ post.title }}
                  </Link>
                  <p class="text-xs opacity-75 line-clamp-2 leading-relaxed">
                    {{ post.excerpt || 'Explore this article built with our zero-overhead CMS.' }}
                  </p>
                </div>
                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                  <span class="opacity-60 truncate">{{ post.user?.name || 'Admin' }}</span>
                  <Link :href="`/${post.slug}`" class="theme-read-link font-semibold shrink-0">
                    Read &rarr;
                  </Link>
                </div>
              </div>
            </article>
          </div>
        </section>

        <!-- 3. Highlighted Post Spotlight -->
        <section
          v-if="highlightedPost"
          class="blog-spotlight rounded-2xl text-white overflow-hidden shadow-lg"
        >
          <div class="grid grid-cols-1 md:grid-cols-12 items-center">
            <div class="md:col-span-7 p-8 sm:p-10 space-y-4">
              <div class="flex items-center gap-2.5">
                <span class="theme-accent-badge px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider">
                  Highlighted Post
                </span>
                <span class="text-xs text-white/75">
                  {{ formatDate(highlightedPost) }}
                </span>
              </div>
              <Link :href="`/${highlightedPost.slug}`" class="block text-2xl sm:text-3xl font-extrabold text-white hover:opacity-85 transition leading-snug">
                {{ highlightedPost.title }}
              </Link>
              <p class="text-sm text-white/80 line-clamp-3 leading-relaxed">
                {{ highlightedPost.excerpt || 'A standout publication selected for deeper reading.' }}
              </p>
              <div class="pt-2">
                <Link
                  :href="`/${highlightedPost.slug}`"
                  class="inline-flex items-center px-5 py-2.5 rounded-lg bg-white text-xs font-bold hover:opacity-90 transition"
                  :style="{ color: 'var(--theme-secondary, #0f172a)' }"
                >
                  Explore Highlight &rarr;
                </Link>
              </div>
            </div>
            <div class="md:col-span-5 h-56 md:h-full min-h-[240px] bg-black/20">
              <img
                :src="highlightedPost.featured_image || fallbackImage"
                :alt="highlightedPost.title"
                class="w-full h-full object-cover opacity-90"
                loading="lazy"
              />
            </div>
          </div>
        </section>

        <!-- 4. Remaining Posts (if any on current page) -->
        <section v-if="remainingPosts.length" class="space-y-6">
          <h3 class="blog-heading text-lg font-bold">More Articles</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <article
              v-for="post in remainingPosts"
              :key="post.id"
              class="blog-card rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col"
            >
              <div class="aspect-video bg-slate-100 overflow-hidden">
                <img
                  :src="post.featured_image || fallbackImage"
                  :alt="post.title"
                  class="w-full h-full object-cover hover:scale-105 transition duration-300"
                  loading="lazy"
                />
              </div>
              <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                <div class="space-y-2">
                  <div class="flex items-center justify-between text-[11px] opacity-60">
                    <span>{{ post.category?.name || 'General' }}</span>
                    <span>{{ formatDate(post) }}</span>
                  </div>
                  <Link :href="`/${post.slug}`" class="blog-heading blog-title-link block text-sm font-bold transition leading-snug">
                    {{ post.title }}
                  </Link>
                  <p class="text-xs opacity-75 line-clamp-2 leading-relaxed">
                    {{ post.excerpt || 'Explore this article built with our zero-overhead CMS.' }}
                  </p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                  <span class="opacity-60">By {{ post.user?.name || 'Admin' }}</span>
                  <Link :href="`/${post.slug}`" class="theme-read-link font-semibold">
                    Read &rarr;
                  </Link>
                </div>
              </div>
            </article>
          </div>
        </section>
      </template>

      <!-- STYLE 2: Classic 2-Column Grid + Sidebar -->
      <template v-else-if="layoutStyle === 'style_2'">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          <!-- Left: 2-Column Posts -->
          <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between">
              <h2 class="blog-heading text-xl font-bold">Latest Publications</h2>
              <span class="text-xs opacity-65 font-medium">
                {{ posts.total }} Articles
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <article
                v-for="post in allPosts"
                :key="post.id"
                class="blog-card rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col"
              >
                <div class="aspect-video bg-slate-100 overflow-hidden relative">
                  <img
                    :src="post.featured_image || fallbackImage"
                    :alt="post.title"
                    class="w-full h-full object-cover hover:scale-105 transition duration-300"
                    loading="lazy"
                  />
                  <span
                    class="absolute top-2 left-2 px-2 py-0.5 rounded text-white text-[10px] font-bold uppercase"
                    :style="{ backgroundColor: 'var(--theme-secondary, #0f172a)' }"
                  >
                    {{ post.category?.name || 'General' }}
                  </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                  <div class="space-y-2">
                    <span class="text-[11px] opacity-60 block">{{ formatDate(post) }}</span>
                    <Link :href="`/${post.slug}`" class="blog-heading blog-title-link block text-base font-bold transition leading-snug">
                      {{ post.title }}
                    </Link>
                    <p class="text-xs opacity-75 line-clamp-2 leading-relaxed">
                      {{ post.excerpt || 'Explore this article built with our zero-overhead CMS.' }}
                    </p>
                  </div>
                  <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="opacity-60">By {{ post.user?.name || 'Admin' }}</span>
                    <Link :href="`/${post.slug}`" class="theme-read-link font-semibold">
                      Read &rarr;
                    </Link>
                  </div>
                </div>
              </article>
            </div>
          </div>

          <!-- Right: Sidebar -->
          <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
            <!-- Highlighted Post Widget -->
            <div v-if="heroPost" class="blog-card rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
              <span class="theme-badge inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase">
                Highlighted Post
              </span>
              <div class="aspect-video rounded-xl overflow-hidden bg-slate-100">
                <img
                  :src="heroPost.featured_image || fallbackImage"
                  :alt="heroPost.title"
                  class="w-full h-full object-cover"
                  loading="lazy"
                />
              </div>
              <div class="space-y-2">
                <Link :href="`/${heroPost.slug}`" class="blog-heading blog-title-link block text-base font-bold transition leading-snug">
                  {{ heroPost.title }}
                </Link>
                <p class="text-xs opacity-75 line-clamp-3 leading-relaxed">
                  {{ heroPost.excerpt || 'Read this highlighted article.' }}
                </p>
                <Link :href="`/${heroPost.slug}`" class="theme-read-link inline-block text-xs font-bold pt-1">
                  Read More &rarr;
                </Link>
              </div>
            </div>

            <!-- Categories Widget -->
            <div v-if="categories?.length" class="blog-card rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3">
              <h3 class="blog-heading text-sm font-bold uppercase tracking-wider">Categories</h3>
              <ul class="divide-y divide-slate-100">
                <li
                  v-for="cat in categories"
                  :key="cat.id"
                  class="py-2.5 flex items-center justify-between text-xs"
                >
                  <span class="font-medium">{{ cat.name }}</span>
                  <span
                    v-if="cat.posts_count !== undefined"
                    class="theme-badge px-2 py-0.5 rounded-full font-semibold"
                  >
                    {{ cat.posts_count }}
                  </span>
                </li>
              </ul>
            </div>
          </aside>
        </div>
      </template>

      <!-- STYLE 3: Minimalist Horizontal List -->
      <template v-else>
        <div class="max-w-4xl mx-auto space-y-6">
          <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <h2 class="blog-heading text-xl font-bold">All Articles</h2>
            <span class="text-xs opacity-65 font-medium">
              {{ posts.total }} Published Posts
            </span>
          </div>

          <div class="space-y-5">
            <article
              v-for="post in allPosts"
              :key="post.id"
              class="blog-card rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col sm:flex-row"
            >
              <div class="sm:w-64 aspect-video sm:aspect-auto bg-slate-100 shrink-0 overflow-hidden">
                <img
                  :src="post.featured_image || fallbackImage"
                  :alt="post.title"
                  class="w-full h-full object-cover hover:scale-105 transition duration-300"
                  loading="lazy"
                />
              </div>
              <div class="p-6 flex-1 flex flex-col justify-between space-y-3">
                <div class="space-y-2">
                  <div class="flex items-center gap-2.5 text-[11px]">
                    <span class="theme-badge px-2.5 py-0.5 rounded-full font-semibold">
                      {{ post.category?.name || 'General' }}
                    </span>
                    <span class="opacity-60">{{ formatDate(post) }}</span>
                  </div>
                  <Link :href="`/${post.slug}`" class="blog-heading blog-title-link block text-lg font-bold transition leading-snug">
                    {{ post.title }}
                  </Link>
                  <p class="text-xs opacity-75 line-clamp-2 leading-relaxed">
                    {{ post.excerpt || 'Explore this article built with our zero-overhead CMS.' }}
                  </p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                  <span class="opacity-60">By {{ post.user?.name || 'Admin' }}</span>
                  <Link :href="`/${post.slug}`" class="theme-read-link font-bold">
                    Read Article &rarr;
                  </Link>
                </div>
              </div>
            </article>
          </div>
        </div>
      </template>

      <!-- Empty State -->
      <div v-if="!allPosts.length" class="blog-card text-center py-16 rounded-2xl border border-slate-200/80">
        <p class="text-sm opacity-65">No published posts found yet.</p>
      </div>

      <!-- Pagination -->
      <nav
        v-if="posts?.links?.length > 3"
        aria-label="Pagination"
        class="flex flex-wrap items-center justify-center gap-1.5 pt-4"
      >
        <template v-for="(link, idx) in posts.links" :key="idx">
          <Link
            v-if="link.url"
            :href="link.url"
            v-html="link.label"
            class="px-3.5 py-2 rounded-lg text-xs font-semibold border transition"
            :class="link.active ? 'shadow-xs' : 'hover:opacity-80'"
            :style="link.active
              ? { backgroundColor: 'var(--theme-primary, #4f46e5)', borderColor: 'var(--theme-primary, #4f46e5)', color: '#ffffff' }
              : { backgroundColor: 'var(--theme-surface, #ffffff)', borderColor: '#e2e8f0', color: 'var(--theme-text, #334155)' }"
          />
          <span
            v-else
            v-html="link.label"
            class="px-3.5 py-2 rounded-lg text-xs font-semibold border border-slate-200 opacity-45 cursor-not-allowed"
            :style="{ backgroundColor: 'var(--theme-surface, #ffffff)' }"
          />
        </template>
      </nav>

    </main>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
  posts: Object,
  featured: Object,
  categories: Array,
  seo: Object,
});

const page = usePage();
const fallbackImage = 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80';

const layoutStyle = computed(() => page.props.theme?.blog?.layout_style || 'style_1');
const allPosts = computed(() => props.posts?.data || []);

const heroPost = computed(() => props.featured || allPosts.value[0] || null);

const style1GridPosts = computed(() => {
  const heroId = heroPost.value?.id;
  return allPosts.value.filter((p) => p.id !== heroId).slice(0, 4);
});

const highlightedPost = computed(() => {
  const usedIds = new Set([heroPost.value?.id, ...style1GridPosts.value.map((p) => p.id)]);
  return allPosts.value.find((p) => !usedIds.has(p.id)) || props.featured || allPosts.value[0] || null;
});

const remainingPosts = computed(() => {
  const usedIds = new Set([
    heroPost.value?.id,
    ...style1GridPosts.value.map((p) => p.id),
    highlightedPost.value?.id,
  ]);
  return allPosts.value.filter((p) => !usedIds.has(p.id));
});

const formatDate = (post) => {
  const dateVal = post?.published_at || post?.created_at;
  return dateVal ? new Date(dateVal).toLocaleDateString() : '';
};
</script>

<style scoped>
.blog-card {
  background-color: var(--theme-surface, #ffffff);
  color: var(--theme-text, #1e293b);
}

.blog-heading {
  color: var(--theme-secondary, #0f172a);
  font-family: var(--theme-font-heading, inherit);
}

.blog-title-link:hover {
  color: var(--theme-primary, #4f46e5);
}

.theme-badge {
  background-color: color-mix(in srgb, var(--theme-primary, #4f46e5) 12%, transparent);
  color: var(--theme-primary, #4f46e5);
}

.theme-accent-badge {
  background-color: var(--theme-accent, #6366f1);
  color: #ffffff;
}

.theme-read-link {
  color: var(--theme-primary, #4f46e5);
}

.theme-read-link:hover {
  color: var(--theme-accent, #6366f1);
}

.blog-spotlight {
  background: linear-gradient(135deg, var(--theme-secondary, #0f172a) 0%, var(--theme-primary, #4f46e5) 100%);
}
</style>
