<template>
  <div
    :class="isFullscreen ? 'fixed inset-4 z-50 flex flex-col shadow-2xl' : 'relative'"
    class="rich-paragraph-editor bg-white rounded-xl border border-slate-300 overflow-hidden focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition"
  >
    <link v-if="bunnyFontsHref" rel="stylesheet" :href="bunnyFontsHref" />
    <component :is="'style'">{{ editorTypographyCss }}</component>
    <!-- Compact Rich Text Toolbar (matches Paragraph dropdown + B I U + Lists + Link + Fullscreen + Code) -->
    <div class="flex flex-wrap items-center gap-1 px-2.5 py-1.5 bg-slate-900 text-slate-200 border-b border-slate-700 select-none text-xs">
      <!-- Block Style Select -->
      <select
        :value="currentBlockType"
        @change="setBlockType($event.target.value)"
        :disabled="showHtmlSource"
        class="px-2 py-1 bg-slate-800 text-slate-100 border border-slate-600 rounded text-xs focus:outline-hidden disabled:opacity-40"
      >
        <option value="paragraph">Paragraph</option>
        <option value="h1">Heading 1</option>
        <option value="h2">Heading 2</option>
        <option value="h3">Heading 3</option>
        <option value="h4">Heading 4</option>
      </select>

      <span class="w-px h-4 bg-slate-700 mx-1"></span>

      <!-- Bold -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleBold().run()"
        :disabled="showHtmlSource"
        :class="editor?.isActive('bold') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-2 py-1 rounded font-bold transition disabled:opacity-40"
        title="Bold"
      >
        B
      </button>

      <!-- Italic -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleItalic().run()"
        :disabled="showHtmlSource"
        :class="editor?.isActive('italic') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-2 py-1 rounded italic font-serif transition disabled:opacity-40"
        title="Italic"
      >
        I
      </button>

      <!-- Underline -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleMark('underline').run()"
        :disabled="showHtmlSource"
        :class="editor?.isActive('underline') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-2 py-1 rounded underline transition disabled:opacity-40"
        title="Underline"
      >
        U
      </button>

      <span class="w-px h-4 bg-slate-700 mx-1"></span>

      <!-- Bullet List -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleBulletList().run()"
        :disabled="showHtmlSource"
        :class="editor?.isActive('bulletList') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition disabled:opacity-40"
        title="Bullet List"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
        </svg>
      </button>

      <!-- Ordered List -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleOrderedList().run()"
        :disabled="showHtmlSource"
        :class="editor?.isActive('orderedList') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition disabled:opacity-40"
        title="Numbered List"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 6h12M9 12h12M9 18h12M4 6h.01M4 12h.01M4 18h.01" />
        </svg>
      </button>

      <!-- Link -->
      <button
        type="button"
        @click="setLink"
        :disabled="showHtmlSource"
        :class="editor?.isActive('link') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition disabled:opacity-40"
        title="Insert / Edit Link"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
        </svg>
      </button>

      <span class="w-px h-4 bg-slate-700 mx-1"></span>

      <!-- Fullscreen Toggle -->
      <button
        type="button"
        @click="isFullscreen = !isFullscreen"
        :class="isFullscreen ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition"
        title="Toggle Fullscreen"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        </svg>
      </button>

      <!-- HTML Source Toggle -->
      <button
        type="button"
        @click="toggleHtmlSource"
        :class="showHtmlSource ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition"
        title="Toggle HTML Source"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
        </svg>
      </button>
    </div>

    <!-- Raw HTML Editor -->
    <textarea
      v-if="showHtmlSource"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      rows="6"
      class="w-full p-3 font-mono text-xs bg-slate-950 text-emerald-300 focus:outline-hidden flex-1"
    ></textarea>

    <!-- TipTap WYSIWYG Editor -->
    <EditorContent
      v-else
      :editor="editor"
      :class="isFullscreen ? 'flex-1 overflow-y-auto' : 'min-h-[120px] max-h-[260px] overflow-y-auto'"
      class="prose prose-sm max-w-none p-3 text-slate-800 focus:outline-hidden tiptap-editor-area"
    />
  </div>
</template>

<script setup>
import { ref, computed, watch, onBeforeUnmount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import { Mark } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import { getTypographyFontsHref, compileTypographyCss } from '@/utils/themeFonts';

const page = usePage();
const bunnyFontsHref = computed(() => getTypographyFontsHref(page.props.theme?.general?.typography || {}));
const editorTypographyCss = computed(() => {
  const typo = page.props.theme?.general?.typography || {};
  const colors = page.props.theme?.general?.colors || {};
  const rules = compileTypographyCss('.rich-paragraph-editor .ProseMirror', typo, colors);
  return `${rules}
.rich-paragraph-editor .ProseMirror:focus { outline: none; }
.rich-paragraph-editor .ProseMirror ul { list-style: disc; padding-left: 1.25rem; }
.rich-paragraph-editor .ProseMirror ol { list-style: decimal; padding-left: 1.25rem; }`;
});

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue']);

const isFullscreen = ref(false);
const showHtmlSource = ref(false);

const UnderlineMark = Mark.create({
  name: 'underline',
  parseHTML() {
    return [{ tag: 'u' }, { style: 'text-decoration=underline' }];
  },
  renderHTML() {
    return ['u', 0];
  },
});

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    UnderlineMark,
    Link.configure({
      openOnClick: false,
      HTMLAttributes: { class: 'text-indigo-600 underline' },
    }),
  ],
  onUpdate: ({ editor: ed }) => {
    emit('update:modelValue', ed.getHTML());
  },
});

watch(
  () => props.modelValue,
  (val) => {
    if (!editor.value) return;
    if (editor.value.getHTML() !== val) {
      editor.value.commands.setContent(val || '', false);
    }
  }
);

const currentBlockType = computed(() => {
  if (!editor.value) return 'paragraph';
  if (editor.value.isActive('heading', { level: 1 })) return 'h1';
  if (editor.value.isActive('heading', { level: 2 })) return 'h2';
  if (editor.value.isActive('heading', { level: 3 })) return 'h3';
  if (editor.value.isActive('heading', { level: 4 })) return 'h4';
  return 'paragraph';
});

const setBlockType = (val) => {
  if (!editor.value) return;
  if (val === 'paragraph') {
    editor.value.chain().focus().setParagraph().run();
  } else {
    const level = Number(val.replace('h', ''));
    editor.value.chain().focus().setHeading({ level }).run();
  }
};

const setLink = () => {
  if (!editor.value) return;
  const previousUrl = editor.value.getAttributes('link').href || '';
  const url = window.prompt('Enter URL:', previousUrl);
  if (url === null) return;
  if (url === '') {
    editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
    return;
  }
  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};

const toggleHtmlSource = () => {
  showHtmlSource.value = !showHtmlSource.value;
  if (!showHtmlSource.value && editor.value) {
    editor.value.commands.setContent(props.modelValue || '', false);
  }
};

onBeforeUnmount(() => {
  editor.value?.destroy();
});
</script>

