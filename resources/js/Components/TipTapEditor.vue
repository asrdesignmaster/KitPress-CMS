<template>
  <div class="bg-white rounded-xl border border-slate-300 shadow-2xs overflow-hidden focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition">
    
    <!-- TipTap Formatting Toolbar -->
    <div class="flex flex-wrap items-center gap-1 p-2 bg-slate-50 border-b border-slate-200 text-slate-700 select-none">
      
      <!-- Headings -->
      <button 
        type="button"
        @click="editor?.chain().focus().toggleHeading({ level: 1 }).run()"
        :class="{ 'bg-slate-200 text-indigo-700 font-bold': editor?.isActive('heading', { level: 1 }) }"
        class="px-2 py-1 text-xs font-semibold rounded hover:bg-slate-200 transition"
        title="Heading 1"
      >
        H1
      </button>
      <button 
        type="button"
        @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="{ 'bg-slate-200 text-indigo-700 font-bold': editor?.isActive('heading', { level: 2 }) }"
        class="px-2 py-1 text-xs font-semibold rounded hover:bg-slate-200 transition"
        title="Heading 2"
      >
        H2
      </button>
      <button 
        type="button"
        @click="editor?.chain().focus().toggleHeading({ level: 3 }).run()"
        :class="{ 'bg-slate-200 text-indigo-700 font-bold': editor?.isActive('heading', { level: 3 }) }"
        class="px-2 py-1 text-xs font-semibold rounded hover:bg-slate-200 transition"
        title="Heading 3"
      >
        H3
      </button>

      <span class="w-px h-4 bg-slate-300 mx-1"></span>

      <!-- Inline Formatting -->
      <button 
        type="button"
        @click="editor?.chain().focus().toggleBold().run()"
        :class="{ 'bg-slate-200 text-indigo-700 font-bold': editor?.isActive('bold') }"
        class="p-1.5 rounded hover:bg-slate-200 transition"
        title="Bold (Ctrl+B)"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 12h8a4 4 0 000-8H6v8zm0 0h9a4 4 0 010 8H6v-8z" />
        </svg>
      </button>
      <button 
        type="button"
        @click="editor?.chain().focus().toggleItalic().run()"
        :class="{ 'bg-slate-200 text-indigo-700 font-bold': editor?.isActive('italic') }"
        class="p-1.5 rounded hover:bg-slate-200 transition"
        title="Italic (Ctrl+I)"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 0h-6m2 16h-6" />
        </svg>
      </button>
      <button 
        type="button"
        @click="editor?.chain().focus().toggleStrike().run()"
        :class="{ 'bg-slate-200 text-indigo-700 font-bold': editor?.isActive('strike') }"
        class="p-1.5 rounded hover:bg-slate-200 transition"
        title="Strikethrough"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L15 9m-4 7l6-6M3 12h18" />
        </svg>
      </button>

      <span class="w-px h-4 bg-slate-300 mx-1"></span>

      <!-- Lists & Quotes -->
      <button 
        type="button"
        @click="editor?.chain().focus().toggleBulletList().run()"
        :class="{ 'bg-slate-200 text-indigo-700': editor?.isActive('bulletList') }"
        class="p-1.5 rounded hover:bg-slate-200 transition"
        title="Bullet List"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <button 
        type="button"
        @click="editor?.chain().focus().toggleOrderedList().run()"
        :class="{ 'bg-slate-200 text-indigo-700': editor?.isActive('orderedList') }"
        class="p-1.5 rounded hover:bg-slate-200 transition"
        title="Numbered List"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 6h.01M3 12h.01M3 18h.01" />
        </svg>
      </button>
      <button 
        type="button"
        @click="editor?.chain().focus().toggleBlockquote().run()"
        :class="{ 'bg-slate-200 text-indigo-700': editor?.isActive('blockquote') }"
        class="p-1.5 rounded hover:bg-slate-200 transition"
        title="Blockquote"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
      </button>

      <span class="w-px h-4 bg-slate-300 mx-1"></span>

      <!-- Insert Media / WebP Image -->
      <button 
        type="button"
        @click="$emit('open-media')"
        class="px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded transition flex items-center space-x-1"
        title="Insert WebP Image from Media Library"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>Add Media</span>
      </button>

      <!-- Clear Formatting / Undo / Redo -->
      <div class="ml-auto flex items-center space-x-1">
        <button 
          type="button"
          @click="editor?.chain().focus().undo().run()"
          :disabled="!editor?.can().undo()"
          class="p-1.5 rounded hover:bg-slate-200 disabled:opacity-30 transition"
          title="Undo (Ctrl+Z)"
        >
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2M3 10l6 6m-6-6l6-6" />
          </svg>
        </button>
        <button 
          type="button"
          @click="editor?.chain().focus().redo().run()"
          :disabled="!editor?.can().redo()"
          class="p-1.5 rounded hover:bg-slate-200 disabled:opacity-30 transition"
          title="Redo (Ctrl+Y)"
        >
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m15-7l-6-6m6 6l-6 6" />
          </svg>
        </button>
      </div>

    </div>

    <!-- TipTap Content Editable Area -->
    <EditorContent 
      :editor="editor" 
      class="p-6 prose max-w-none text-slate-800 focus:outline-hidden min-h-[360px]"
    />

  </div>
</template>

<script setup>
import { watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue', 'open-media']);

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit.configure({
      heading: {
        levels: [1, 2, 3],
      },
    }),
    Image.configure({
      inline: false,
      HTMLAttributes: {
        class: 'rounded-lg max-w-full h-auto my-4 shadow-xs',
      },
    }),
    Link.configure({
      openOnClick: false,
      HTMLAttributes: {
        class: 'text-indigo-600 underline',
      },
    }),
  ],
  editorProps: {
    attributes: {
      class: 'tiptap-content focus:outline-hidden',
    },
  },
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML());
  },
});

// Watch external changes
watch(
  () => props.modelValue,
  (newVal) => {
    if (editor.value && editor.value.getHTML() !== newVal) {
      editor.value.commands.setContent(newVal || '', false);
    }
  }
);

// Method to insert image from media modal
const insertImage = (url, alt) => {
  if (editor.value) {
    editor.value.chain().focus().setImage({ src: url, alt: alt || '' }).run();
  }
};

defineExpose({ insertImage });

onBeforeUnmount(() => {
  editor.value?.destroy();
});
</script>

