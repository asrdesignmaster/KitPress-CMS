<template>
  <div
    :class="isFullscreen ? 'fixed inset-4 z-50 flex flex-col shadow-2xl' : 'relative'"
    class="rich-paragraph-editor bg-white rounded-xl border border-slate-300 overflow-hidden focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition"
  >
    <link v-if="bunnyFontsHref" rel="stylesheet" :href="bunnyFontsHref" />
    <component :is="'style'">{{ editorTypographyCss }}</component>

    <!-- Top Mode Bar: Visual | Text + Fullscreen (Elementor-style) -->
    <div class="flex items-center justify-between px-2.5 py-1.5 bg-slate-950 text-slate-200 border-b border-slate-800 select-none text-[11px]">
      <span class="font-semibold tracking-wide text-slate-400 uppercase text-[10px]">Rich Text</span>
      <div class="flex items-center gap-1">
        <button
          type="button"
          @click="setMode(false)"
          :class="!showHtmlSource ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-white'"
          class="px-2.5 py-1 rounded transition cursor-pointer"
        >
          Visual
        </button>
        <button
          type="button"
          @click="setMode(true)"
          :class="showHtmlSource ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-white'"
          class="px-2.5 py-1 rounded transition cursor-pointer"
        >
          Text
        </button>
        <span class="w-px h-3.5 bg-slate-700 mx-1"></span>
        <button
          type="button"
          @click="isFullscreen = !isFullscreen"
          :class="isFullscreen ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800'"
          class="p-1 rounded transition cursor-pointer"
          title="Toggle Fullscreen"
        >
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Formatting Toolbar -->
    <div
      v-show="!showHtmlSource"
      class="flex flex-wrap items-center gap-1 px-2 py-1.5 bg-slate-900 text-slate-200 border-b border-slate-800 select-none text-xs"
    >
      <!-- Block Style Select -->
      <select
        :value="currentBlockType"
        @change="setBlockType($event.target.value)"
        class="px-2 py-1 bg-slate-800 text-slate-100 border border-slate-700 rounded text-[11px] focus:outline-hidden"
      >
        <option value="paragraph">Paragraph</option>
        <option value="h1">Heading 1</option>
        <option value="h2">Heading 2</option>
        <option value="h3">Heading 3</option>
        <option value="h4">Heading 4</option>
        <option value="blockquote">Blockquote</option>
      </select>

      <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

      <!-- Bold -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleBold().run()"
        :class="editor?.isActive('bold') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-2 py-1 rounded font-bold transition cursor-pointer"
        title="Bold"
      >
        B
      </button>

      <!-- Italic -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleItalic().run()"
        :class="editor?.isActive('italic') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-2 py-1 rounded italic font-serif transition cursor-pointer"
        title="Italic"
      >
        I
      </button>

      <!-- Underline -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleMark('underline').run()"
        :class="editor?.isActive('underline') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-2 py-1 rounded underline transition cursor-pointer"
        title="Underline"
      >
        U
      </button>

      <!-- Strikethrough -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleStrike().run()"
        :class="editor?.isActive('strike') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-2 py-1 rounded line-through transition cursor-pointer"
        title="Strikethrough"
      >
        S
      </button>

      <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

      <!-- Bullet List -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleBulletList().run()"
        :class="editor?.isActive('bulletList') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition cursor-pointer"
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
        :class="editor?.isActive('orderedList') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition cursor-pointer"
        title="Numbered List"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 6h12M9 12h12M9 18h12M4 6h.01M4 12h.01M4 18h.01" />
        </svg>
      </button>

      <!-- Blockquote -->
      <button
        type="button"
        @click="editor?.chain().focus().toggleBlockquote().run()"
        :class="editor?.isActive('blockquote') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="px-1.5 py-1 rounded font-serif font-bold transition cursor-pointer"
        title="Blockquote"
      >
        &ldquo;&rdquo;
      </button>

      <!-- Link -->
      <button
        type="button"
        @click="setLink"
        :class="editor?.isActive('link') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-200'"
        class="p-1.5 rounded transition cursor-pointer"
        title="Insert / Edit Link"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
        </svg>
      </button>

      <!-- Text Color -->
      <label
        class="relative flex items-center px-1.5 py-1 rounded hover:bg-slate-800 cursor-pointer"
        title="Text Color"
      >
        <span class="font-bold text-[11px] leading-none border-b-2" :style="{ borderColor: currentTextColor }">A</span>
        <input
          type="color"
          :value="currentTextColor"
          @input="setTextColor($event.target.value)"
          class="absolute inset-0 opacity-0 w-full h-full cursor-pointer"
        />
      </label>

      <!-- Clear Formatting -->
      <button
        type="button"
        @click="editor?.chain().focus().unsetAllMarks().clearNodes().run()"
        class="p-1.5 rounded hover:bg-slate-800 text-slate-300 transition cursor-pointer"
        title="Clear Formatting"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

      <!-- Undo -->
      <button
        type="button"
        @click="editor?.chain().focus().undo().run()"
        :disabled="!editor?.can().undo()"
        class="p-1.5 rounded hover:bg-slate-800 text-slate-200 transition disabled:opacity-30 cursor-pointer"
        title="Undo"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2M3 10l4-4m-4 4l4 4" />
        </svg>
      </button>

      <!-- Redo -->
      <button
        type="button"
        @click="editor?.chain().focus().redo().run()"
        :disabled="!editor?.can().redo()"
        class="p-1.5 rounded hover:bg-slate-800 text-slate-200 transition disabled:opacity-30 cursor-pointer"
        title="Redo"
      >
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m15-7l-4-4m4 4l-4 4" />
        </svg>
      </button>
    </div>

    <!-- Raw HTML Editor (Text Mode) -->
    <textarea
      v-if="showHtmlSource"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      rows="8"
      class="w-full p-3 font-mono text-xs bg-slate-950 text-emerald-300 focus:outline-hidden flex-1"
    ></textarea>

    <!-- TipTap WYSIWYG Editor (Visual Mode) -->
    <EditorContent
      v-else
      :editor="editor"
      :class="isFullscreen ? 'flex-1 overflow-y-auto' : 'min-h-[140px] max-h-[280px] overflow-y-auto'"
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
.rich-paragraph-editor .ProseMirror ol { list-style: decimal; padding-left: 1.25rem; }
.rich-paragraph-editor .ProseMirror blockquote { border-left: 3px solid #cbd5e1; padding-left: 0.75rem; color: #475569; }`;
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

const TextColorMark = Mark.create({
  name: 'textColor',
  addAttributes() {
    return {
      color: {
        default: null,
        parseHTML: (element) => element.style.color || null,
        renderHTML: (attributes) => {
          if (!attributes.color) return {};
          return { style: `color: ${attributes.color}` };
        },
      },
    };
  },
  parseHTML() {
    return [{ style: 'color' }];
  },
  renderHTML({ HTMLAttributes }) {
    return ['span', HTMLAttributes, 0];
  },
});

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    UnderlineMark,
    TextColorMark,
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
  if (editor.value.isActive('blockquote')) return 'blockquote';
  if (editor.value.isActive('heading', { level: 1 })) return 'h1';
  if (editor.value.isActive('heading', { level: 2 })) return 'h2';
  if (editor.value.isActive('heading', { level: 3 })) return 'h3';
  if (editor.value.isActive('heading', { level: 4 })) return 'h4';
  return 'paragraph';
});

const currentTextColor = computed(() => {
  return editor.value?.getAttributes('textColor')?.color || '#1e293b';
});

const setBlockType = (val) => {
  if (!editor.value) return;
  if (val === 'paragraph') {
    editor.value.chain().focus().setParagraph().run();
  } else if (val === 'blockquote') {
    editor.value.chain().focus().toggleBlockquote().run();
  } else {
    const level = Number(val.replace('h', ''));
    editor.value.chain().focus().setHeading({ level }).run();
  }
};

const setTextColor = (color) => {
  if (!editor.value) return;
  editor.value.chain().focus().setMark('textColor', { color }).run();
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

const setMode = (htmlMode) => {
  showHtmlSource.value = htmlMode;
  if (!htmlMode && editor.value) {
    editor.value.commands.setContent(props.modelValue || '', false);
  }
};

onBeforeUnmount(() => {
  editor.value?.destroy();
});
</script>
