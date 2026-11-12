<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-xs" @click="closeModal" aria-hidden="true"></div>

      <!-- Modal Panel -->
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="relative z-10 inline-block w-full max-w-5xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-xl shadow-2xl sm:align-middle">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
          <div class="flex items-center space-x-4">
            <h3 class="text-lg font-semibold text-slate-800" id="modal-title">
              Media Library
            </h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
              Auto WebP Optimization
            </span>
          </div>

          <!-- Tabs -->
          <div class="flex items-center space-x-2">
            <button 
              type="button"
              @click="activeTab = 'library'"
              :class="activeTab === 'library' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200'"
              class="px-3.5 py-1.5 text-xs font-medium rounded-lg transition"
            >
              Browse Library
            </button>
            <button 
              type="button"
              @click="activeTab = 'upload'"
              :class="activeTab === 'upload' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200'"
              class="px-3.5 py-1.5 text-xs font-medium rounded-lg transition flex items-center space-x-1"
            >
              <span>+ Upload WebP</span>
            </button>
            <button type="button" @click="closeModal" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-200 transition">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
          <!-- Upload Tab -->
          <div v-if="activeTab === 'upload'" class="space-y-4">
            <div 
              @dragover.prevent="isDragging = true"
              @dragleave.prevent="isDragging = false"
              @drop.prevent="handleDrop"
              :class="isDragging ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-300 bg-slate-50 hover:bg-slate-100/60'"
              class="flex flex-col items-center justify-center p-12 border-2 border-dashed rounded-xl transition cursor-pointer text-center"
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
              <div class="p-3 bg-white rounded-full shadow-xs mb-3 text-indigo-600">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              <p class="text-sm font-semibold text-slate-800">
                Click or drag images to upload
              </p>
              <p class="text-xs text-slate-500 mt-1">
                PNG, JPG, JPEG, GIF up to 20MB. Automatically transformed to WebP & responsive sizes.
              </p>
            </div>

            <!-- Uploading status bar -->
            <div v-if="isUploading" class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center space-x-3">
              <svg class="animate-spin h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
              </svg>
              <span class="text-sm font-medium text-indigo-900">
                Uploading and converting images to WebP...
              </span>
            </div>
          </div>

          <!-- Library Tab -->
          <div v-else class="space-y-4">
            <!-- Search & Filter -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div class="relative w-72">
                <input 
                  v-model="searchQuery" 
                  @input="debouncedSearch"
                  type="text" 
                  placeholder="Search media by name or alt text..."
                  class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:bg-white"
                />
                <svg class="w-4 h-4 absolute left-2.5 top-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <span class="text-xs text-slate-500">
                {{ mediaItems.length }} assets loaded
              </span>
            </div>

            <!-- Media Grid -->
            <div v-if="isLoading" class="py-16 text-center text-slate-400">
              <svg class="animate-spin h-8 w-8 mx-auto text-indigo-600 mb-2" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
              </svg>
              <p class="text-xs font-medium">Loading Media Library...</p>
            </div>

            <div v-else-if="mediaItems.length === 0" class="py-16 text-center text-slate-400">
              <p class="text-sm font-medium text-slate-600">No media found</p>
              <p class="text-xs text-slate-400 mt-1">Upload an image to start populating your library.</p>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 max-h-96 overflow-y-auto p-1">
              <div 
                v-for="item in mediaItems" 
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
                
                <!-- WebP Format Badge -->
                <span class="absolute top-1 left-1 px-1.5 py-0.5 text-[9px] font-bold bg-slate-900/70 backdrop-blur-xs text-white rounded">
                  WEBP
                </span>

                <!-- Hover Details overlay -->
                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition p-2 flex flex-col justify-end text-white text-[10px]">
                  <p class="font-medium truncate">{{ item.name }}</p>
                  <p class="text-slate-300 text-[9px]">{{ item.formatted_size }}</p>
                </div>
              </div>
            </div>

            <!-- Selected Media Details Bar -->
            <div v-if="selectedItem" class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="flex items-center space-x-3">
                <img 
                  :src="selectedItem.sizes?.thumbnail?.url || selectedItem.url" 
                  class="w-12 h-12 rounded object-cover border border-slate-300"
                />
                <div>
                  <h4 class="text-xs font-semibold text-slate-800">{{ selectedItem.name }}</h4>
                  <p class="text-[11px] text-slate-500">
                    {{ selectedItem.width }} x {{ selectedItem.height }} px &bull; {{ selectedItem.formatted_size }} &bull; WebP
                  </p>
                  <input 
                    v-model="selectedItem.alt_text" 
                    placeholder="Alt text for accessibility..."
                    class="mt-1 px-2 py-0.5 text-xs bg-white border border-slate-300 rounded text-slate-700 w-64"
                  />
                </div>
              </div>

              <div class="flex items-center space-x-2">
                <button 
                  type="button"
                  @click="confirmSelection"
                  class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg shadow-xs transition"
                >
                  Insert Selected Media
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'select']);

const activeTab = ref('library');
const isDragging = ref(false);
const isUploading = ref(false);
const isLoading = ref(false);
const fileInput = ref(null);
const searchQuery = ref('');
const mediaItems = ref([]);
const selectedItem = ref(null);

const fetchMedia = async () => {
  isLoading.value = true;
  try {
    const res = await axios.get('/admin/media', {
      params: { search: searchQuery.value },
      headers: { Accept: 'application/json' },
    });
    mediaItems.value = res.data.data || [];
    if (mediaItems.value.length > 0 && !selectedItem.value) {
      selectedItem.value = mediaItems.value[0];
    }
  } catch (err) {
    console.error('Failed to load media:', err);
  } finally {
    isLoading.value = false;
  }
};

let debounceTimer = null;
const debouncedSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchMedia();
  }, 350);
};

const triggerFileInput = () => {
  fileInput.value?.click();
};

const handleFileInput = (e) => {
  const files = e.target.files;
  if (files && files.length > 0) {
    uploadFiles(files);
  }
};

const handleDrop = (e) => {
  isDragging.value = false;
  const files = e.dataTransfer.files;
  if (files && files.length > 0) {
    uploadFiles(files);
  }
};

const uploadFiles = async (files) => {
  isUploading.value = true;
  const formData = new FormData();
  for (let i = 0; i < files.length; i++) {
    formData.append('files[]', files[i]);
  }

  try {
    const res = await axios.post('/admin/media', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
      },
    });

    if (res.data.success && res.data.media) {
      // Switch back to library and select the newly uploaded item
      activeTab.value = 'library';
      await fetchMedia();
      if (res.data.media.length > 0) {
        selectedItem.value = res.data.media[0];
      }
    }
  } catch (err) {
    alert('Upload failed: ' + (err.response?.data?.message || err.message));
  } finally {
    isUploading.value = false;
  }
};

const confirmSelection = () => {
  if (!selectedItem.value) return;
  emit('select', selectedItem.value);
  closeModal();
};

const closeModal = () => {
  emit('close');
};

watch(
  () => props.isOpen,
  (val) => {
    if (val) {
      fetchMedia();
    }
  }
);
</script>

