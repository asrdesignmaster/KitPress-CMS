<template>
  <AdminLayout>
    <div class="space-y-6">
      
      <!-- Top Action Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight capitalize">
            {{ type === 'page' ? 'Pages' : 'Posts' }}
          </h1>
          <p class="text-xs text-slate-500 mt-1">
            Manage your content with dual-mode Classic TipTap and Elementor visual tree editing.
          </p>
        </div>

        <div class="flex items-center space-x-2">
          <Link 
            :href="`/admin/posts/create?type=${type}&editor=classic`" 
            class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 shadow-2xs transition flex items-center space-x-1.5"
          >
            <span>+ Classic TipTap</span>
          </Link>

          <Link 
            :href="`/admin/posts/create?type=${type}&editor=builder`" 
            class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition flex items-center space-x-1.5"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
            </svg>
            <span>+ Visual Builder</span>
          </Link>
        </div>
      </div>

      <!-- Filters & Status Tabs -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center space-x-3 text-xs">
          <Link 
            :href="filterUrl({ status: null })"
            :class="!filters.status ? 'text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1' : 'text-slate-500 hover:text-slate-800 pb-1'"
            class="transition"
          >
            All <span class="text-slate-400 font-normal">({{ counts.all }})</span>
          </Link>
          <span class="text-slate-300">|</span>
          <Link 
            :href="filterUrl({ status: 'published' })"
            :class="filters.status === 'published' ? 'text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1' : 'text-slate-500 hover:text-slate-800 pb-1'"
            class="transition"
          >
            Published <span class="text-slate-400 font-normal">({{ counts.published }})</span>
          </Link>
          <span class="text-slate-300">|</span>
          <Link 
            :href="filterUrl({ status: 'draft' })"
            :class="filters.status === 'draft' ? 'text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1' : 'text-slate-500 hover:text-slate-800 pb-1'"
            class="transition"
          >
            Drafts <span class="text-slate-400 font-normal">({{ counts.draft }})</span>
          </Link>
        </div>

        <!-- Search & Category Filters -->
        <div class="flex items-center space-x-2">
          <!-- Category select if posts -->
          <select 
            v-if="type === 'post'"
            v-model="selectedCategory"
            @change="applyFilter"
            class="px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500"
          >
            <option :value="null">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
              {{ cat.name }}
            </option>
          </select>

          <!-- Search Input -->
          <div class="relative">
            <input 
              v-model="search"
              @keydown.enter="applyFilter"
              type="text" 
              placeholder="Search title or slug..."
              class="w-56 pl-8 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500"
            />
            <svg class="w-3.5 h-3.5 absolute left-2.5 top-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <button 
            @click="applyFilter"
            class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-medium rounded-lg transition"
          >
            Filter
          </button>
        </div>
      </div>

      <!-- Content Table -->
      <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
          <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider text-[10px]">
            <tr>
              <th scope="col" class="px-5 py-3.5">Title</th>
              <th scope="col" class="px-4 py-3.5">Author</th>
              <th scope="col" v-if="type === 'post'" class="px-4 py-3.5">Category</th>
              <th scope="col" class="px-4 py-3.5">Mode</th>
              <th scope="col" class="px-4 py-3.5">SEO Score</th>
              <th scope="col" class="px-4 py-3.5">Status</th>
              <th scope="col" class="px-4 py-3.5">Date</th>
              <th scope="col" class="px-5 py-3.5 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-700">
            <tr v-if="posts.data.length === 0">
              <td :colspan="type === 'post' ? 8 : 7" class="px-5 py-12 text-center text-slate-400">
                No {{ type }}s found matching current filters.
              </td>
            </tr>

            <tr 
              v-for="post in posts.data" 
              :key="post.id"
              class="hover:bg-slate-50/70 transition"
            >
              <!-- Title & Slug -->
              <td class="px-5 py-3.5">
                <div class="flex items-center space-x-2">
                  <Link 
                    :href="`/admin/posts/${post.id}/edit`" 
                    class="font-semibold text-slate-900 hover:text-indigo-600 block"
                  >
                    {{ post.title }}
                  </Link>
                  <span
                    v-if="homepagePostId && post.id === homepagePostId"
                    class="px-2 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full shrink-0"
                  >
                    Home Page
                  </span>
                </div>
                <div class="flex items-center space-x-2 text-[11px] text-slate-400 mt-0.5">
                  <span>/{{ post.slug }}</span>
                </div>
              </td>

              <!-- Author -->
              <td class="px-4 py-3.5 text-slate-600">
                {{ post.user?.name || 'Admin' }}
              </td>

              <!-- Category -->
              <td v-if="type === 'post'" class="px-4 py-3.5">
                <span v-if="post.category" class="inline-flex px-2 py-0.5 rounded text-[11px] bg-slate-100 text-slate-700 font-medium">
                  {{ post.category.name }}
                </span>
                <span v-else class="text-slate-400 text-[11px]">Uncategorized</span>
              </td>

              <!-- Mode / Editor Type -->
              <td class="px-4 py-3.5">
                <span 
                  :class="post.editor_type === 'builder' ? 'bg-purple-100 text-purple-800 border-purple-200' : 'bg-blue-100 text-blue-800 border-blue-200'"
                  class="px-2 py-0.5 text-[10px] font-bold rounded uppercase border"
                >
                  {{ post.editor_type }}
                </span>
              </td>

              <!-- SEO Score Indicator -->
              <td class="px-4 py-3.5">
                <div class="flex items-center space-x-1.5">
                  <span 
                    :class="getSeoBadgeClass(post)"
                    class="w-2.5 h-2.5 rounded-full"
                    :title="post.seo_focus_keyword ? 'Focus Keyword: ' + post.seo_focus_keyword : 'No keyword set'"
                  ></span>
                  <span class="text-[11px] font-medium text-slate-600">
                    {{ post.seo_focus_keyword ? 'Good' : 'Needs Setup' }}
                  </span>
                </div>
              </td>

              <!-- Status -->
              <td class="px-4 py-3.5">
                <span 
                  :class="post.status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                  class="px-2 py-0.5 text-[10px] font-semibold rounded-full"
                >
                  {{ post.status }}
                </span>
              </td>

              <!-- Date -->
              <td class="px-4 py-3.5 text-slate-500 text-[11px]">
                {{ new Date(post.created_at).toLocaleDateString() }}
              </td>

              <!-- Actions -->
              <td class="px-5 py-3.5 text-right space-x-2">
                <a 
                  :href="`/${post.slug}`" 
                  target="_blank" 
                  class="text-slate-400 hover:text-slate-700 text-xs"
                  title="View live page"
                >
                  View
                </a>
                <Link 
                  :href="`/admin/posts/${post.id}/edit`" 
                  class="text-indigo-600 hover:text-indigo-800 font-medium text-xs"
                >
                  Edit
                </Link>
                <button 
                  @click="duplicatePost(post.id)"
                  class="text-slate-500 hover:text-slate-800 text-xs"
                  title="Duplicate post"
                >
                  Clone
                </button>
                <button 
                  @click="deletePost(post.id, post.title)"
                  class="text-rose-500 hover:text-rose-700 text-xs"
                >
                  Trash
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="posts.links && posts.links.length > 3" class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
          <span class="text-xs text-slate-500">
            Showing {{ posts.from || 0 }} to {{ posts.to || 0 }} of {{ posts.total }} entries
          </span>
          <div class="flex items-center space-x-1">
            <Link 
              v-for="(link, i) in posts.links" 
              :key="i"
              :href="link.url || '#'"
              v-html="link.label"
              :class="[
                link.active ? 'bg-indigo-600 text-white font-medium' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200',
                !link.url ? 'opacity-40 cursor-not-allowed' : ''
              ]"
              class="px-2.5 py-1 text-xs rounded transition"
            />
          </div>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  posts: Object,
  filters: Object,
  categories: Array,
  counts: Object,
  type: String,
  homepagePostId: {
    type: Number,
    default: null,
  },
});

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category_id || null);

const filterUrl = (overrides) => {
  const params = new URLSearchParams({
    type: props.type,
    ...(props.filters.status && { status: props.filters.status }),
    ...(props.filters.category_id && { category_id: props.filters.category_id }),
    ...(props.filters.search && { search: props.filters.search }),
    ...overrides,
  });

  // Remove empty params
  for (const [key, value] of [...params.entries()]) {
    if (!value) params.delete(key);
  }

  return `/admin/posts?${params.toString()}`;
};

const applyFilter = () => {
  router.get('/admin/posts', {
    type: props.type,
    status: props.filters.status,
    category_id: selectedCategory.value,
    search: search.value,
  }, { preserveState: true });
};

const getSeoBadgeClass = (post) => {
  if (!post.seo_focus_keyword) return 'bg-amber-400';
  if (post.seo_title && post.seo_description) return 'bg-emerald-500';
  return 'bg-blue-400';
};

const duplicatePost = (id) => {
  router.post(`/admin/posts/${id}/duplicate`);
};

const deletePost = (id, title) => {
  if (confirm(`Are you sure you want to delete "${title}"?`)) {
    router.delete(`/admin/posts/${id}`);
  }
};
</script>

