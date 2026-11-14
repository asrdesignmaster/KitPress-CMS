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
        <section v-if="heroPost" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col md:flex-row hover:shadow-md transition">
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
              <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-indigo-100 text-indigo-800">
                  Hero {{ heroPost.type || 'post' }}
                </span>
                <span v-if="heroPost.category?.name" class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                  {{ heroPost.category.name }}
                </span>
                <span class="text-xs text-slate-400">
                  {{ formatDate(heroPost) }}
                </span>
              </div>
              <Link :href="`/${heroPost.slug}`" class="block text-2xl sm:text-3xl font-extrabold text-slate-900 hover:text-indigo-600 transition leading-tight">
                {{ heroPost.title }}
              </Link>
              <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">
                {{ heroPost.excerpt || 'Read this featured article to explore the full architecture.' }}
              </p>
            </div>
            <div class="pt-2 flex items-center justify-between">
              <span class="text-xs text-slate-400">By {{ heroPost.user?.name || 'Admin' }}</span>
              <Link :href="`/${heroPost.slug}`" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition">
                Read Full Article &rarr;
              </Link>
            </div>
          </div>
        </section>

        <!-- 2. 4 Posts Grid -->
        <section v-if="style1GridPosts.length" class="space-y-6">
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-900">Latest Stories</h2>
            <span class="text-xs text-slate-500 font-medium">
              {{ posts.total }} Published Articles
            </span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <article
              v-for="post in style1GridPosts"
              :key="post.id"
              class="bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col"
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
                  <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ post.category?.name || 'General' }}</span>
                    <span>{{ formatDate(post) }}</span>
                  </div>
                  <Link :href="`/${post.slug}`" class="block text-sm font-bold text-slate-900 hover:text-indigo-600 transition leading-snug line-clamp-2">
                    {{ post.title }}
                  </Link>
                  <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                    {{ post.excerpt || 'Explore this article built with our zero-overhead CMS.' }}
                  </p>
                </div>
                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                  <span class="text-slate-400 truncate">{{ post.user?.name || 'Admin' }}</span>
                  <Link :href="`/${post.slug}`" class="text-indigo-600 hover:text-indigo-800 font-semibold shrink-0">
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
          class="rounded-2xl bg-linear-to-r from-slate-900 via-indigo-950 to-slate-900 text-white overflow-hidden shadow-lg border border-indigo-900/50"
        >
          <div class="grid grid-cols-1 md:grid-cols-12 items-center">
            <div class="md:col-span-7 p-8 sm:p-10 space-y-4">
              <div class="flex items-center gap-2.5">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-400 text-slate-950">
                  Highlighted Post
                </span>
                <span class="text-xs text-indigo-200">
                  {{ formatDate(highlightedPost) }}
                </span>
              </div>
              <Link :href="`/${highlightedPost.slug}`" class="block text-2xl sm:text-3xl font-extrabold text-white hover:text-amber-300 transition leading-snug">
                {{ highlightedPost.title }}
              </Link>
              <p class="text-sm text-slate-300 line-clamp-3 leading-relaxed">
                {{ highlightedPost.excerpt || 'A standout publication selected for deeper reading.' }}
              </p>
              <div class="pt-2">
                <Link
                  :href="`/${highlightedPost.slug}`"
                  class="inline-flex items-center px-5 py-2.5 rounded-lg bg-white text-slate-900 text-xs font-bold hover:bg-amber-300 transition"
                >
                  Explore Highlight &rarr;
                </Link>
              </div>
            </div>
            <div class="md:col-span-5 h-56 md:h-full min-h-[240px] bg-slate-800">
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
          <h3 class="text-lg font-bold text-slate-900">More Articles</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <article
              v-for="post in remainingPosts"
              :key="post.id"
              class="bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col"
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
                  <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ post.category?.name || 'General' }}</span>
                    <span>{{ formatDate(post) }}</span>
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
              <h2 class="text-xl font-bold text-slate-900">Latest Publications</h2>
              <span class="text-xs text-slate-500 font-medium">
                {{ posts.total }} Articles
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <article
                v-for="post in allPosts"
                :key="post.id"
                class="bg-white rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col"
              >
                <div class="aspect-video bg-slate-100 overflow-hidden relative">
                  <img
                    :src="post.featured_image || fallbackImage"
                    :alt="post.title"
                    class="w-full h-full object-cover hover:scale-105 transition duration-300"
                    loading="lazy"
                  />
                  <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-slate-900/80 text-white text-[10px] font-bold uppercase">
                    {{ post.category?.name || 'General' }}
                  </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                  <div class="space-y-2">
                    <span class="text-[11px] text-slate-400 block">{{ formatDate(post) }}</span>
                    <Link :href="`/${post.slug}`" class="block text-base font-bold text-slate-900 hover:text-indigo-600 transition leading-snug">
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
              </article>
            </div>
          </div>

          <!-- Right: Sidebar -->
          <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
            <!-- Highlighted Post Widget -->
            <div v-if="heroPost" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
              <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-indigo-100 text-indigo-700">
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
                <Link :href="`/${heroPost.slug}`" class="block text-base font-bold text-slate-900 hover:text-indigo-600 transition leading-snug">
                  {{ heroPost.title }}
                </Link>
                <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                  {{ heroPost.excerpt || 'Read this highlighted article.' }}
                </p>
                <Link :href="`/${heroPost.slug}`" class="inline-block text-xs font-bold text-indigo-600 hover:text-indigo-800 pt-1">
                  Read More &rarr;
                </Link>
              </div>
            </div>

            <!-- Categories Widget -->
            <div v-if="categories?.length" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
              <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Categories</h3>
              <ul class="divide-y divide-slate-100">
                <li
                  v-for="cat in categories"
                  :key="cat.id"
                  class="py-2.5 flex items-center justify-between text-xs"
                >
                  <span class="font-medium text-slate-700">{{ cat.name }}</span>
                  <span v-if="cat.posts_count !== undefined" class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">
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
            <h2 class="text-xl font-bold text-slate-900">All Articles</h2>
            <span class="text-xs text-slate-500 font-medium">
              {{ posts.total }} Published Posts
            </span>
          </div>

          <div class="space-y-5">
            <article
              v-for="post in allPosts"
              :key="post.id"
              class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:shadow-md transition overflow-hidden flex flex-col sm:flex-row"
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
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-semibold">
                      {{ post.category?.name || 'General' }}
                    </span>
                    <span class="text-slate-400">{{ formatDate(post) }}</span>
                  </div>
                  <Link :href="`/${post.slug}`" class="block text-lg font-bold text-slate-900 hover:text-indigo-600 transition leading-snug">
                    {{ post.title }}
                  </Link>
                  <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                    {{ post.excerpt || 'Explore this article built with our zero-overhead CMS.' }}
                  </p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                  <span class="text-slate-400">By {{ post.user?.name || 'Admin' }}</span>
                  <Link :href="`/${post.slug}`" class="text-indigo-600 hover:text-indigo-800 font-bold">
                    Read Article &rarr;
                  </Link>
                </div>
              </div>
            </article>
          </div>
        </div>
      </template>

      <!-- Empty State -->
      <div v-if="!allPosts.length" class="text-center py-16 bg-white rounded-2xl border border-slate-200">
        <p class="text-sm text-slate-500">No published posts found yet.</p>
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
            :class="[
              'px-3.5 py-2 rounded-lg text-xs font-semibold border transition',
              link.active
                ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs'
                : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50',
            ]"
          />
          <span
            v-else
            v-html="link.label"
            class="px-3.5 py-2 rounded-lg text-xs font-semibold border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed"
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
