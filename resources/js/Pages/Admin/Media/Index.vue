<template>
  <AdminLayout>
    <div class="space-y-6">
      
      <!-- Top Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Media Library
          </h1>
          <p class="text-xs text-slate-500 mt-1">
            Automated image pipeline: Every upload is automatically converted to WebP with responsive thumbnails.
          </p>
        </div>

        <div class="flex items-center space-x-2">
          <button 
            @click="showUploadZone = !showUploadZone"
            class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition flex items-center space-x-1.5"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>{{ showUploadZone ? 'Hide Uploader' : 'Add New Media' }}</span>
          </button>
        </div>
      </div>

      <!-- Drag & Drop Uploader Area (Collapsible) -->
      <div v-show="showUploadZone" class="p-6 bg-white rounded-xl border border-indigo-200 shadow-2xs space-y-4">
        <div 
          @dragover.prevent="isDragging = true"
          @dragleave.prevent="isDragging = false"
          @drop.prevent="handleDrop"
          :class="isDragging ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-300 bg-slate-50 hover:bg-slate-100/60'"
          class="flex flex-col items-center justify-center p-10 border-2 border-dashed rounded-xl transition cursor-pointer text-center"
          @click="triggerFileInput"
        >
          <input 
            ref="fileInput" 
            type="file" 
            multiple 
            accept="image/*" 
            class="hidden" 
            @change="handleFileInput"
          />
          <div class="p-3 bg-white rounded-full shadow-xs mb-2 text-indigo-600">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
          </div>
          <p class="text-sm font-semibold text-slate-800">
            Drop image files here to upload & optimize
          </p>
          <p class="text-xs text-slate-500 mt-1">
            JPG, PNG, GIF, or WebP. Server automatically optimizes and encodes to WebP format.
          </p>
        </div>

        <div v-if="isUploading" class="p-3 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center space-x-3">
          <svg class="animate-spin h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
          </svg>
          <span class="text-xs font-semibold text-indigo-900">
            Encoding image assets to optimized WebP format and generating responsive thumbnails...
          </span>
        </div>
      </div>

      <!-- Main Layout: Grid + Inspector Sidebar -->
      <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left: Searchable Media Grid -->
        <div class="flex-1 bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
          
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="relative w-72">
              <input 
                v-model="search" 
                @input="debouncedSearch"
                type="text" 
                placeholder="Search media..." 
                class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg text-slate-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:bg-white"
              />
              <svg class="w-3.5 h-3.5 absolute left-2.5 top-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <span class="text-xs text-slate-400">
              {{ media.total }} items total
            </span>
          </div>

          <div v-if="media.data.length === 0" class="py-20 text-center text-slate-400 text-xs">
            No media files found. Upload an image to get started.
          </div>

          <div v-else class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
            <div 
              v-for="item in media.data" 
              :key="item.id"
              @click="selectedItem = item"
              :class="selectedItem?.id === item.id ? 'ring-3 ring-indigo-600 ring-offset-2' : 'hover:opacity-90'"
              class="group relative aspect-square bg-slate-100 rounded-lg overflow-hidden border border-slate-200 cursor-pointer transition shadow-2xs"
            >
              <img 
                :src="item.sizes?.thumbnail?.url || item.url" 
                :alt="item.alt_text || item.name"
                class="w-full h-full object-cover"
                loading="lazy"
              />
              <span class="absolute top-1 left-1 px-1.5 py-0.5 text-[9px] font-bold bg-slate-900/80 text-white rounded">
                WEBP
              </span>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="media.links && media.links.length > 3" class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-400">Page {{ media.current_page }} of {{ media.last_page }}</span>
            <div class="flex items-center space-x-1">
              <Link 
                v-for="(link, i) in media.links" 
                :key="i"
                :href="link.url || '#'"
                v-html="link.label"
                :class="[
                  link.active ? 'bg-indigo-600 text-white font-medium' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200',
                  !link.url ? 'opacity-40 cursor-not-allowed' : ''
                ]"
                class="px-2 py-1 text-xs rounded transition"
              />
            </div>
          </div>

        </div>

        <!-- Right: Inspector Sidebar for Selected Asset -->
        <div class="w-full lg:w-80 shrink-0 bg-white rounded-xl border border-slate-200/90 shadow-2xs p-5 space-y-4">
          <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100">
            Attachment Details
          </h3>

          <div v-if="selectedItem" class="space-y-4 text-xs">
            <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden border border-slate-200">
              <img 
                :src="selectedItem.sizes?.medium?.url || selectedItem.url" 
                class="w-full h-full object-contain"
                alt="Selected preview"
              />
            </div>

            <div class="space-y-1">
              <p class="font-bold text-slate-800 break-all">{{ selectedItem.file_name }}</p>
              <p class="text-slate-400 text-[11px]">
                {{ selectedItem.width }} &times; {{ selectedItem.height }} px &bull; {{ selectedItem.formatted_size }}
              </p>
              <p class="text-emerald-600 font-semibold text-[11px]">
                WebP Compression Active
              </p>
            </div>

            <!-- Metadata Form -->
            <div class="space-y-3 pt-2 border-t border-slate-100">
              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Alt Text</label>
                <input 
                  v-model="selectedItem.alt_text" 
                  type="text" 
                  class="w-full px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded"
                />
              </div>

              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">Caption</label>
                <textarea 
                  v-model="selectedItem.caption" 
                  rows="2" 
                  class="w-full px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded"
                ></textarea>
              </div>

              <div class="space-y-1">
                <label class="font-semibold text-slate-700 block">WebP File URL</label>
                <div class="flex items-center space-x-1">
                  <input 
                    :value="selectedItem.url" 
                    readonly 
                    class="flex-1 px-2 py-1 text-[11px] font-mono bg-slate-100 border border-slate-200 rounded text-slate-600"
                  />
                  <button 
                    @click="copyUrl(selectedItem.url)"
                    class="px-2 py-1 text-xs bg-slate-200 hover:bg-slate-300 rounded font-medium text-slate-700"
                  >
                    Copy
                  </button>
                </div>
              </div>

              <!-- Available Responsive Sizes -->
              <div v-if="selectedItem.sizes && Object.keys(selectedItem.sizes).length" class="space-y-1 pt-2">
                <label class="font-semibold text-slate-700 block">Responsive WebP Sets</label>
                <div class="space-y-1 text-[11px]">
                  <div v-for="(size, name) in selectedItem.sizes" :key="name" class="flex justify-between items-center text-slate-500">
                    <span class="capitalize font-medium">{{ name }} ({{ size.width }}x{{ size.height }})</span>
                    <a :href="size.url" target="_blank" class="text-indigo-600 hover:underline">View</a>
                  </div>
                </div>
              </div>

              <div class="pt-3 flex items-center justify-between">
                <button 
                  type="button" 
                  @click="updateItem"
                  class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-xs transition"
                >
                  Save Meta
                </button>
                <button 
                  type="button" 
                  @click="deleteItem"
                  class="text-rose-600 hover:text-rose-800 text-xs font-medium"
                >
                  Delete File
                </button>
              </div>
            </div>
          </div>

          <div v-else class="py-12 text-center text-slate-400 text-xs">
            Select an image from the grid to inspect details and responsive WebP URLs.
          </div>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  media: Object,
  filters: Object,
});

const showUploadZone = ref(false);
const isDragging = ref(false);
const isUploading = ref(false);
const fileInput = ref(null);
const search = ref(props.filters?.search || '');
const selectedItem = ref(props.media?.data?.[0] || null);

let debounceTimer = null;
const debouncedSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    router.get('/admin/media', { search: search.value }, { preserveState: true });
  }, 350);
};

const triggerFileInput = () => {
  fileInput.value?.click();
};

const handleFileInput = (e) => {
  const files = e.target.files;
  if (files && files.length > 0) uploadFiles(files);
};

const handleDrop = (e) => {
  isDragging.value = false;
  const files = e.dataTransfer.files;
  if (files && files.length > 0) uploadFiles(files);
};

const uploadFiles = (files) => {
  isUploading.value = true;
  const formData = new FormData();
  for (let i = 0; i < files.length; i++) {
    formData.append('files[]', files[i]);
  }

  router.post('/admin/media', formData, {
    onFinish: () => {
      isUploading.value = false;
      showUploadZone.value = false;
    },
  });
};

const copyUrl = (url) => {
  navigator.clipboard.writeText(url);
  alert('Copied URL to clipboard!');
};

const updateItem = async () => {
  if (!selectedItem.value) return;
  await axios.put(`/admin/media/${selectedItem.value.id}`, {
    name: selectedItem.value.name,
    alt_text: selectedItem.value.alt_text,
    caption: selectedItem.value.caption,
  });
  alert('Media metadata updated.');
};

const deleteItem = () => {
  if (!selectedItem.value) return;
  if (confirm(`Permanently delete "${selectedItem.value.name}"?`)) {
    router.delete(`/admin/media/${selectedItem.value.id}`);
    selectedItem.value = null;
  }
};
</script>

