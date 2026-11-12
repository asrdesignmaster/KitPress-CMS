<template>
  <AdminLayout>
    <form @submit.prevent="savePost" class="space-y-6">
      
      <!-- Top Sticky Action Bar -->
      <div class="sticky top-12 z-20 -mx-6 px-6 py-3 bg-white/90 backdrop-blur-md border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
        <div class="flex items-center space-x-3">
          <Link :href="`/admin/posts?type=${type}`" class="text-xs text-slate-500 hover:text-slate-800 transition flex items-center space-x-1">
            <span>&larr; Back to {{ type }}s</span>
          </Link>
          <span class="text-slate-300">|</span>
          <span class="text-xs font-semibold text-slate-700 capitalize">
            {{ isEditing ? 'Edit ' + type : 'Add New ' + type }}
          </span>
          <span 
            :class="form.status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
            class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full"
          >
            {{ form.status }}
          </span>
        </div>

        <div class="flex items-center space-x-2">
          <!-- Live Preview / View Site -->
          <a 
            v-if="isEditing && post?.slug" 
            :href="`/${post.slug}`" 
            target="_blank" 
            class="px-3 py-1.5 text-xs text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition"
          >
            View on Site &nearr;
          </a>

          <!-- Mode Toggle Switcher -->
          <div class="inline-flex p-0.5 bg-slate-100 border border-slate-200 rounded-lg text-xs">
            <button 
              type="button" 
              @click="form.editor_type = 'classic'"
              :class="form.editor_type === 'classic' ? 'bg-white text-indigo-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3 py-1 rounded transition"
            >
              Classic TipTap
            </button>
            <button 
              type="button" 
              @click="form.editor_type = 'builder'"
              :class="form.editor_type === 'builder' ? 'bg-indigo-600 text-white font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3 py-1 rounded transition flex items-center space-x-1"
            >
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
              </svg>
              <span>Visual Builder</span>
            </button>
          </div>

          <!-- Save / Publish Buttons -->
          <button 
            type="button" 
            @click="publishWithStatus('draft')"
            :disabled="form.processing"
            class="px-3.5 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition"
          >
            Save Draft
          </button>

          <button 
            type="submit" 
            :disabled="form.processing"
            class="px-4 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition"
          >
            <span v-if="form.processing">Saving...</span>
            <span v-else>{{ form.status === 'published' ? 'Update ' + type : 'Publish ' + type }}</span>
          </button>
        </div>
      </div>

      <!-- Main Editor Columns -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left 8-9 Cols: Title, Editors & SEO Suite -->
        <div class="lg:col-span-8 xl:col-span-9 space-y-6">
          
          <!-- Title & Permalink Input -->
          <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-2xs space-y-3">
            <input 
              v-model="form.title" 
              @input="onTitleInput"
              type="text" 
              placeholder="Add Title..." 
              required
              class="w-full text-2xl font-bold tracking-tight text-slate-900 border-0 border-b border-transparent hover:border-slate-200 focus:border-indigo-500 focus:ring-0 focus:outline-hidden py-1 px-0 transition placeholder-slate-400"
            />

            <!-- Slug editor -->
            <div class="flex items-center space-x-2 text-xs text-slate-500">
              <span class="font-medium text-slate-400">Permalink:</span>
              <span class="text-slate-400">yourdomain.com/</span>
              <input 
                v-model="form.slug" 
                type="text" 
                class="px-2 py-0.5 text-xs font-mono bg-slate-50 border border-slate-200 rounded text-slate-800 w-64 focus:bg-white focus:outline-hidden focus:border-indigo-400"
              />
            </div>
          </div>

          <!-- Mode 1: Classic TipTap Editor -->
          <div v-show="form.editor_type === 'classic'" class="space-y-2">
            <div class="flex items-center justify-between px-1">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                ProseMirror TipTap Rich Text Editor
              </span>
              <span class="text-[11px] text-slate-400">Structured HTML / Prose Output</span>
            </div>
            <TipTapEditor 
              ref="tiptapRef"
              v-model="form.classic_content" 
              @open-media="openMediaModal('tiptap')"
            />
          </div>

          <!-- Mode 2: Elementor Pro Style Visual Builder -->
          <div v-show="form.editor_type === 'builder'" class="space-y-2">
            <div class="flex items-center justify-between px-1">
              <span class="text-xs font-bold uppercase tracking-wider text-purple-700">
                Elementor Pro Visual Tree Builder
              </span>
              <span class="text-[11px] text-slate-400">Sections &bull; Columns &bull; Reactive Widgets</span>
            </div>
            <VisualBuilder 
              ref="builderRef"
              v-model="form.builder_json"
              @open-media="openMediaModal('builder_widget')"
            />
          </div>

          <!-- Advanced SEO Suite Box (Requirement D) -->
          <SeoSuiteBox 
            :form="form"
            :title="form.title"
            :slug="form.slug"
            :raw-content="extractedContentText"
            @open-media="openMediaModal('seo_og_image')"
          />

        </div>

        <!-- Right 3-4 Cols: WordPress Sidebar Meta Boxes -->
        <div class="lg:col-span-4 xl:col-span-3 space-y-5">
          
          <!-- Meta Box 1: Publish Settings -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-800 uppercase tracking-wider">
              Publish
            </div>
            <div class="p-4 space-y-4 text-xs">
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Status:</span>
                <select 
                  v-model="form.status" 
                  class="px-2 py-1 bg-slate-50 border border-slate-300 rounded font-semibold text-slate-800"
                >
                  <option value="draft">Draft</option>
                  <option value="published">Published</option>
                </select>
              </div>

              <div class="flex items-center justify-between">
                <span class="text-slate-500">Visibility:</span>
                <span class="font-medium text-slate-800">Public</span>
              </div>

              <div v-if="post?.revisions?.length" class="flex items-center justify-between pt-2 border-t border-slate-100">
                <span class="text-slate-500">Revisions:</span>
                <button 
                  type="button" 
                  @click="showRevisionsModal = true"
                  class="text-indigo-600 hover:text-indigo-800 font-semibold"
                >
                  {{ post.revisions.length }} snapshots
                </button>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <button 
                  type="button" 
                  @click="publishWithStatus('published')"
                  class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-xs transition"
                >
                  {{ form.status === 'published' ? 'Update' : 'Publish Immediately' }}
                </button>
              </div>
            </div>
          </div>

          <!-- Meta Box 2: Taxonomies (Categories & Tags - for posts) -->
          <div v-if="type === 'post'" class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-800 uppercase tracking-wider">
              Categories & Tags
            </div>
            <div class="p-4 space-y-4 text-xs">
              <!-- Category -->
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Primary Category</label>
                <select 
                  v-model="form.category_id" 
                  class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-800"
                >
                  <option :value="null">Uncategorized</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>

              <!-- Tags -->
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Tags (comma-separated)</label>
                <input 
                  v-model="tagsInput" 
                  @change="updateTagsFromInput"
                  type="text" 
                  placeholder="e.g. Vue 3, Laravel, WebP"
                  class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-800"
                />
              </div>
            </div>
          </div>

          <!-- Meta Box 3: Featured WebP Image -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-800 uppercase tracking-wider">
              Featured Image
            </div>
            <div class="p-4 text-xs space-y-3">
              <div 
                v-if="form.featured_image" 
                class="relative aspect-video rounded-lg overflow-hidden border border-slate-300 group"
              >
                <img 
                  :src="form.featured_image" 
                  class="w-full h-full object-cover" 
                  alt="Featured WebP Image" 
                />
                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center space-x-2">
                  <button 
                    type="button" 
                    @click="openMediaModal('featured')"
                    class="px-2.5 py-1 bg-white text-slate-800 font-semibold rounded text-xs hover:bg-slate-100"
                  >
                    Replace
                  </button>
                  <button 
                    type="button" 
                    @click="removeFeaturedImage"
                    class="px-2.5 py-1 bg-rose-600 text-white font-semibold rounded text-xs hover:bg-rose-700"
                  >
                    Remove
                  </button>
                </div>
              </div>

              <button 
                v-else
                type="button" 
                @click="openMediaModal('featured')"
                class="w-full py-8 border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-lg text-center text-slate-500 hover:text-indigo-600 transition block"
              >
                <svg class="w-6 h-6 mx-auto mb-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="font-semibold text-xs">Set WebP Featured Image</span>
              </button>
            </div>
          </div>

          <!-- Meta Box 4: Excerpt -->
          <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-800 uppercase tracking-wider">
              Excerpt / Summary
            </div>
            <div class="p-4">
              <textarea 
                v-model="form.excerpt" 
                rows="3" 
                placeholder="Optional excerpt to show in cards and RSS feeds..."
                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg"
              ></textarea>
            </div>
          </div>

        </div>

      </div>
    </form>

    <!-- Global Media Modal -->
    <MediaModal 
      :is-open="mediaModalOpen" 
      @close="mediaModalOpen = false"
      @select="handleMediaSelected"
    />

    <!-- Revisions Snapshot History Modal -->
    <div v-if="showRevisionsModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 p-6">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="showRevisionsModal = false"></div>
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full relative z-10 space-y-4">
          <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-sm font-bold text-slate-800">Content Revision History</h3>
            <button @click="showRevisionsModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
          </div>
          <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 text-xs">
            <div 
              v-for="rev in post.revisions" 
              :key="rev.id"
              class="py-2.5 flex items-center justify-between"
            >
              <div>
                <p class="font-semibold text-slate-800">{{ rev.summary || 'Update' }}</p>
                <p class="text-[11px] text-slate-400">{{ new Date(rev.created_at).toLocaleString() }} &bull; Mode: {{ rev.editor_type }}</p>
              </div>
              <button 
                type="button" 
                @click="restoreRevision(rev)"
                class="px-2 py-1 text-xs text-indigo-600 hover:bg-indigo-50 border border-indigo-200 rounded"
              >
                Restore
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TipTapEditor from '@/Components/TipTapEditor.vue';
import VisualBuilder from '@/Components/Builder/VisualBuilder.vue';
import SeoSuiteBox from '@/Components/SeoSuiteBox.vue';
import MediaModal from '@/Components/MediaModal.vue';

const props = defineProps({
  post: Object,
  type: {
    type: String,
    default: 'post',
  },
  default_editor: {
    type: String,
    default: 'classic',
  },
  categories: Array,
  tags: Array,
});

const isEditing = computed(() => !!props.post?.id);

const form = useForm({
  title: props.post?.title || '',
  slug: props.post?.slug || '',
  type: props.type,
  editor_type: props.post?.editor_type || props.default_editor || 'classic',
  classic_content: props.post?.classic_content || '',
  builder_json: props.post?.builder_json || [],
  status: props.post?.status || 'draft',
  category_id: props.post?.category_id || null,
  featured_image: props.post?.featured_image || '',
  featured_image_id: props.post?.featured_image_id || null,
  excerpt: props.post?.excerpt || '',
  tags: props.post?.tags?.map(t => t.name) || [],
  // SEO Suite
  seo_focus_keyword: props.post?.seo_focus_keyword || '',
  seo_title: props.post?.seo_title || '',
  seo_description: props.post?.seo_description || '',
  seo_og_title: props.post?.seo_og_title || '',
  seo_og_description: props.post?.seo_og_description || '',
  seo_og_image: props.post?.seo_og_image || '',
  seo_robots_index: props.post?.seo_robots_index !== undefined ? Boolean(props.post.seo_robots_index) : true,
  seo_robots_follow: props.post?.seo_robots_follow !== undefined ? Boolean(props.post.seo_robots_follow) : true,
});

const tagsInput = ref(props.post?.tags?.map(t => t.name).join(', ') || '');
const mediaModalOpen = ref(false);
const mediaTarget = ref('featured'); // 'featured', 'tiptap', 'builder_widget', 'seo_og_image'
const showRevisionsModal = ref(false);

const tiptapRef = ref(null);
const builderRef = ref(null);

const onTitleInput = () => {
  if (!isEditing.value || !form.slug) {
    form.slug = form.title
      .toLowerCase()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_-]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }
};

const updateTagsFromInput = () => {
  form.tags = tagsInput.value.split(',').map(s => s.trim()).filter(Boolean);
};

const openMediaModal = (target) => {
  mediaTarget.value = target;
  mediaModalOpen.value = true;
};

const handleMediaSelected = (mediaItem) => {
  const url = mediaItem.sizes?.large?.url || mediaItem.url;
  
  if (mediaTarget.value === 'featured') {
    form.featured_image = url;
    form.featured_image_id = mediaItem.id;
  } else if (mediaTarget.value === 'seo_og_image') {
    form.seo_og_image = url;
  } else if (mediaTarget.value === 'tiptap') {
    tiptapRef.value?.insertImage(url, mediaItem.alt_text);
  } else if (mediaTarget.value === 'builder_widget') {
    builderRef.value?.setWidgetImage(url, mediaItem.alt_text);
  }
};

const removeFeaturedImage = () => {
  form.featured_image = '';
  form.featured_image_id = null;
};

const extractedContentText = computed(() => {
  if (form.editor_type === 'classic') {
    return form.classic_content;
  }
  // Extract text nodes from builder JSON
  let text = '';
  if (Array.isArray(form.builder_json)) {
    form.builder_json.forEach(sec => {
      sec.columns?.forEach(col => {
        col.widgets?.forEach(w => {
          if (w.content) text += ' ' + w.content;
        });
      });
    });
  }
  return text;
});

const publishWithStatus = (newStatus) => {
  form.status = newStatus;
  savePost();
};

const savePost = () => {
  updateTagsFromInput();
  if (isEditing.value) {
    form.put(`/admin/posts/${props.post.id}`);
  } else {
    form.post('/admin/posts');
  }
};

const restoreRevision = (revision) => {
  form.title = revision.title;
  form.editor_type = revision.editor_type;
  form.classic_content = revision.classic_content;
  form.builder_json = revision.builder_json || [];
  showRevisionsModal.value = false;
  alert('Revision snapshot loaded into editor.');
};
</script>

