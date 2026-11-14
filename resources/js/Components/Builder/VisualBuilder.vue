<template>
  <div class="flex flex-col lg:flex-row gap-4 bg-slate-100 p-2.5 rounded-xl border border-slate-300">
    
    <!-- Left Sidebar: Widgets, Inspector, Navigator -->
    <div class="w-full lg:w-84 shrink-0 bg-white rounded-xl border border-slate-200/90 shadow-xs flex flex-col h-[780px] overflow-hidden">
      
      <!-- Sidebar Tabs -->
      <div class="flex border-b border-slate-200 bg-slate-50 text-[11px] font-semibold">
        <button 
          type="button"
          @click="sidebarTab = 'elements'"
          :class="sidebarTab === 'elements' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800'"
          class="flex-1 py-3 text-center transition"
        >
          Widgets
        </button>

        <button 
          type="button"
          @click="sidebarTab = 'inspector'"
          :class="sidebarTab === 'inspector' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800'"
          class="flex-1 py-3 text-center transition flex items-center justify-center space-x-1"
        >
          <span>Inspector</span>
          <span v-if="selectedWidget || selectedSection || selectedColumn" class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
        </button>

        <button 
          type="button"
          @click="sidebarTab = 'navigator'"
          :class="sidebarTab === 'navigator' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800'"
          class="flex-1 py-3 text-center transition"
        >
          Navigator
        </button>
      </div>

      <!-- Sidebar Body -->
      <div class="flex-1 overflow-y-auto p-4">
        
        <!-- TAB 1: WIDGET INVENTORY (Searchable + Draggable) -->
        <div v-if="sidebarTab === 'elements'" class="space-y-4">
          <div class="relative">
            <input
              v-model="widgetSearch"
              type="text"
              placeholder="Search 18 Pro widgets..."
              class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-indigo-500"
            />
            <svg class="w-3.5 h-3.5 absolute left-2.5 top-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <div v-for="cat in filteredCategories" :key="cat.id" class="border border-slate-200/80 rounded-xl bg-slate-50/40 overflow-hidden">
            <button
              type="button"
              @click="toggleCategory(cat.id)"
              class="w-full px-3 py-2 flex items-center justify-between text-left hover:bg-slate-100/70 transition"
            >
              <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 flex items-center space-x-1.5">
                <span>{{ cat.label }}</span>
                <span class="px-1.5 py-0.2 rounded-full bg-slate-200/70 text-slate-500 text-[9px] font-semibold">
                  {{ cat.widgets.length }}
                </span>
              </span>
              <svg
                :class="isCategoryCollapsed(cat.id) ? '' : 'rotate-90'"
                class="w-3.5 h-3.5 text-slate-400 transition-transform duration-150"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>

            <draggable
              v-show="!isCategoryCollapsed(cat.id)"
              :list="cat.widgets"
              :group="{ name: 'builder-widgets', pull: 'clone', put: false }"
              :clone="cloneWidget"
              :sort="false"
              item-key="type"
              class="grid grid-cols-2 gap-2 p-2.5 pt-1 bg-white border-t border-slate-200/70"
            >
              <template #item="{ element }">
                <button
                  type="button"
                  @click="addWidgetToCurrent(element.type)"
                  class="flex flex-col items-center justify-center p-3 bg-slate-50 hover:bg-indigo-50/80 border border-slate-200 hover:border-indigo-300 rounded-xl transition text-center group cursor-grab active:cursor-grabbing"
                >
                  <span class="text-sm font-bold text-slate-600 group-hover:text-indigo-600 mb-1">
                    {{ element.badge }}
                  </span>
                  <span class="text-[11px] font-semibold text-slate-700 group-hover:text-indigo-900">
                    {{ element.label }}
                  </span>
                </button>
              </template>
            </draggable>
          </div>

          <!-- Section Column Presets -->
          <div class="pt-4 border-t border-slate-200 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Add Section Grid</p>
            <div class="grid grid-cols-2 gap-1.5 text-[11px]">
              <button type="button" @click="addSection([100])" class="py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium border border-slate-200">1 Col (100%)</button>
              <button type="button" @click="addSection([50, 50])" class="py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium border border-slate-200">2 Col (50/50)</button>
              <button type="button" @click="addSection([33.33, 66.66])" class="py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium border border-slate-200">2 Col (33/66)</button>
              <button type="button" @click="addSection([33.33, 33.33, 33.33])" class="py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium border border-slate-200">3 Col (33x3)</button>
              <button type="button" @click="addSection([25, 25, 25, 25])" class="col-span-2 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium border border-slate-200">4 Columns (25% &times; 4)</button>
            </div>
          </div>

          <!-- Pro Block Presets -->
          <div class="pt-3 border-t border-slate-200 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">1-Click Pro Block Templates</p>
            <div class="space-y-1.5 text-[11px]">
              <button type="button" @click="insertPreset('hero_cta')" class="w-full py-1.5 px-3 text-left bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-semibold rounded-lg border border-indigo-200 transition">
                + Hero Banner + CTA
              </button>
              <button type="button" @click="insertPreset('features_3col')" class="w-full py-1.5 px-3 text-left bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-semibold rounded-lg border border-indigo-200 transition">
                + 3-Column Feature Icons
              </button>
              <button type="button" @click="insertPreset('pricing_3col')" class="w-full py-1.5 px-3 text-left bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-semibold rounded-lg border border-indigo-200 transition">
                + 3-Tier Pricing Table
              </button>
            </div>
          </div>
        </div>

        <!-- TAB 2: LIVE PROPERTY INSPECTOR -->
        <WidgetInspector
          v-else-if="sidebarTab === 'inspector'"
          :selected-widget="selectedWidget"
          :selected-column="selectedColumn"
          :selected-section="selectedSection"
          @deselect="clearSelection"
          @delete-widget="deleteCurrentWidget"
          @duplicate-widget="duplicateCurrentWidget"
          @delete-section="deleteCurrentSection"
          @duplicate-section="duplicateCurrentSection"
          @set-section-columns="setSectionColumns"
          @open-media="openMediaForTarget"
        />

        <!-- TAB 3: NAVIGATOR STRUCTURE TREE -->
        <div v-else class="space-y-2 text-xs">
          <div class="flex items-center justify-between pb-2 border-b border-slate-200">
            <span class="font-bold text-slate-700 uppercase tracking-wider text-[11px]">Document Tree</span>
            <span class="text-[11px] text-slate-400">{{ layoutTree.length }} sections</span>
          </div>

          <div v-if="layoutTree.length === 0" class="py-12 text-center text-slate-400">
            No sections in layout yet.
          </div>

          <div v-for="(sec, sIdx) in layoutTree" :key="sec.id" class="border border-slate-200 rounded-lg overflow-hidden">
            <div
              @click="selectSection(sec)"
              :class="selectedSection?.id === sec.id ? 'bg-blue-50 text-blue-800 font-bold' : 'bg-slate-50 text-slate-700 font-semibold'"
              class="px-2.5 py-1.5 cursor-pointer flex items-center justify-between"
            >
              <span class="truncate">{{ sec.name || `Section ${sIdx + 1}` }}</span>
              <span class="text-[10px] text-slate-400">{{ sec.columns?.length || 0 }} cols</span>
            </div>
            <div class="p-1.5 space-y-1 bg-white">
              <div v-for="(col, cIdx) in sec.columns" :key="col.id" class="pl-2 border-l-2 border-slate-200 space-y-1">
                <div
                  @click="selectColumn(col, sec)"
                  :class="selectedColumn?.id === col.id && !selectedWidget ? 'text-indigo-600 font-bold' : 'text-slate-500'"
                  class="text-[10px] cursor-pointer hover:text-indigo-600"
                >
                  Col {{ cIdx + 1 }} ({{ col.width }})
                </div>
                <div
                  v-for="w in col.widgets"
                  :key="w.id"
                  @click="selectWidget(w, col, sec)"
                  :class="selectedWidget?.id === w.id ? 'bg-purple-50 text-purple-800 font-semibold' : 'text-slate-600 hover:bg-slate-50'"
                  class="pl-2 py-0.5 rounded text-[11px] cursor-pointer truncate"
                >
                  &bull; {{ w.type }}
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Right: Visual Tree Canvas -->
    <div class="flex-1 bg-white rounded-xl border border-slate-200/90 shadow-xs flex flex-col h-[780px] overflow-hidden">
      
      <!-- Top Studio Bar -->
      <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center space-x-2">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
            Elementor Pro Canvas
          </span>

          <!-- Undo / Redo -->
          <div class="flex items-center space-x-1 ml-2">
            <button
              type="button"
              @click="undo"
              :disabled="historyIndex <= 0"
              class="px-2 py-1 text-xs bg-white border border-slate-200 rounded hover:bg-slate-100 disabled:opacity-40"
              title="Undo"
            >
              ↶
            </button>
            <button
              type="button"
              @click="redo"
              :disabled="historyIndex >= historyStack.length - 1"
              class="px-2 py-1 text-xs bg-white border border-slate-200 rounded hover:bg-slate-100 disabled:opacity-40"
              title="Redo"
            >
              ↷
            </button>
          </div>
        </div>

        <!-- Responsive Viewport Switcher -->
        <div class="flex items-center space-x-1 bg-white border border-slate-200 p-0.5 rounded-lg text-[11px]">
          <button
            type="button"
            @click="viewport = 'desktop'"
            :class="viewport === 'desktop' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100'"
            class="px-2.5 py-1 rounded transition"
          >
            Desktop
          </button>
          <button
            type="button"
            @click="viewport = 'tablet'"
            :class="viewport === 'tablet' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100'"
            class="px-2.5 py-1 rounded transition"
          >
            Tablet
          </button>
          <button
            type="button"
            @click="viewport = 'mobile'"
            :class="viewport === 'mobile' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100'"
            class="px-2.5 py-1 rounded transition"
          >
            Mobile
          </button>
        </div>

        <div class="flex items-center space-x-2">
          <button 
            type="button"
            @click="previewMode = !previewMode"
            :class="previewMode ? 'bg-indigo-600 text-white' : 'bg-white text-slate-700 border border-slate-300'"
            class="px-3 py-1 text-xs font-semibold rounded-lg transition"
          >
            {{ previewMode ? 'Exit Preview' : 'Live Preview' }}
          </button>
        </div>
      </div>

      <!-- Canvas Scrollable Workspace -->
      <div class="flex-1 overflow-y-auto p-4 builder-canvas flex justify-center">
        <div
          :style="{ width: viewportWidth }"
          class="space-y-4 transition-all duration-300 mx-auto"
        >
          <!-- Empty State -->
          <div v-if="layoutTree.length === 0" class="py-24 text-center border-2 border-dashed border-slate-300 rounded-xl bg-white">
            <p class="text-sm font-semibold text-slate-700">Visual canvas is empty</p>
            <p class="text-xs text-slate-400 mt-1">Drag widgets, add a section grid, or insert a 1-click Pro block template from the left sidebar.</p>
            <div class="mt-4 flex items-center justify-center gap-2">
              <button
                type="button"
                @click="insertPreset('hero_cta')"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition"
              >
                + Insert Pro Hero Section
              </button>
              <button
                type="button"
                @click="addSection([50, 50])"
                class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-300 transition"
              >
                + Blank 2-Col Section
              </button>
            </div>
          </div>

          <!-- Render Sections (Draggable) -->
          <draggable
            v-model="layoutTree"
            item-key="id"
            handle=".section-drag-handle"
            :disabled="previewMode"
            @end="recordHistory"
            class="space-y-4"
          >
            <template #item="{ element: section, index: sIndex }">
              <div
                @click.stop="selectSection(section)"
                :class="[
                  !previewMode && selectedSection?.id === section.id ? 'builder-section-selected ring-2 ring-blue-500' : '',
                  !previewMode ? 'relative group border border-slate-200 hover:border-blue-400 rounded-xl transition overflow-hidden' : 'rounded-xl overflow-hidden'
                ]"
                :style="{
                  backgroundColor: section.backgroundColor || '#ffffff',
                  backgroundImage: section.backgroundGradient || 'none',
                  color: section.textColor || '#1e293b',
                  paddingTop: section.paddingTop || '32px',
                  paddingBottom: section.paddingBottom || '32px',
                }"
                class="transition shadow-2xs"
              >
                <!-- Section Controls Header -->
                <div
                  v-if="!previewMode"
                  class="absolute top-2 right-2 bg-slate-900/85 backdrop-blur-xs text-white px-2.5 py-1 rounded-md text-[10px] flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition z-20"
                >
                  <span class="section-drag-handle cursor-move text-indigo-300 font-bold" title="Drag to reorder section">⠿</span>
                  <span class="font-medium text-slate-200">{{ section.name || 'Section ' + (sIndex + 1) }}</span>
                  <button type="button" @click.stop="duplicateSection(sIndex)" class="hover:text-emerald-400" title="Duplicate section">⧉</button>
                  <button type="button" @click.stop="moveSection(sIndex, -1)" :disabled="sIndex === 0" class="hover:text-indigo-400 disabled:opacity-30">&uarr;</button>
                  <button type="button" @click.stop="moveSection(sIndex, 1)" :disabled="sIndex === layoutTree.length - 1" class="hover:text-indigo-400 disabled:opacity-30">&darr;</button>
                  <button type="button" @click.stop="removeSection(sIndex)" class="hover:text-rose-400">&times;</button>
                </div>

                <!-- Section Columns Container -->
                <div
                  :class="[
                    section.containerWidth === 'full' ? 'w-full px-4' : 'max-w-6xl mx-auto px-4',
                    (viewport === 'mobile' || section.stackColumns) ? 'flex-col' : 'flex-col md:flex-row'
                  ]"
                  class="flex gap-4"
                >
                  <div
                    v-for="column in section.columns"
                    :key="column.id"
                    @click.stop="selectColumn(column, section)"
                    :style="{
                      flex: (viewport === 'mobile' || section.stackColumns) ? '1 1 100%' : `1 1 ${column.width || '100%'}`,
                      width: (viewport === 'mobile' || section.stackColumns) ? '100%' : undefined,
                      backgroundColor: column.backgroundColor || 'transparent',
                      padding: column.padding || '10px',
                      borderRadius: column.borderRadius || '8px',
                    }"
                    :class="[
                      !previewMode && activeColumnId === column.id ? 'border-indigo-400 bg-indigo-50/15' : 'border-dashed border-slate-300/80 hover:border-slate-400',
                      !previewMode ? 'border min-h-[110px] transition relative' : '',
                      'min-w-0'
                    ]"
                  >
                    <span v-if="!previewMode" class="text-[9px] font-mono font-semibold text-slate-400 block mb-1">
                      Col ({{ column.width }})
                    </span>

                    <!-- Draggable Column Widgets -->
                    <draggable
                      v-model="column.widgets"
                      group="builder-widgets"
                      item-key="id"
                      :disabled="previewMode"
                      @change="recordHistory"
                      class="min-h-[60px] space-y-1"
                    >
                      <template #item="{ element: widget, index: wIndex }">
                        <div
                          @click.stop="selectWidget(widget, column, section)"
                          :class="[
                            !previewMode && selectedWidget?.id === widget.id ? 'builder-widget-selected ring-2 ring-purple-500' : '',
                            !previewMode ? 'relative group/widget hover:ring-1 hover:ring-purple-300 rounded p-1.5 transition cursor-pointer' : ''
                          ]"
                        >
                          <!-- Widget Quick Controls -->
                          <div
                            v-if="!previewMode"
                            class="absolute top-0 right-0 bg-purple-900/90 text-white px-1.5 py-0.5 rounded text-[9px] hidden group-hover/widget:flex items-center space-x-1.5 z-10"
                          >
                            <button type="button" @click.stop="duplicateWidget(column, wIndex)" title="Duplicate">⧉</button>
                            <button type="button" @click.stop="moveWidget(column, wIndex, -1)" :disabled="wIndex === 0">&uarr;</button>
                            <button type="button" @click.stop="moveWidget(column, wIndex, 1)" :disabled="wIndex === column.widgets.length - 1">&darr;</button>
                            <button type="button" @click.stop="deleteWidget(column, wIndex)" class="text-rose-300">&times;</button>
                          </div>

                          <BuilderWidgetRenderer :widget="widget" :is-editor="!previewMode" />
                        </div>
                      </template>
                    </draggable>

                    <div v-if="column.widgets.length === 0 && !previewMode" class="py-4 text-center text-slate-300 text-[11px] pointer-events-none">
                      Drag widget here or click from sidebar
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </draggable>

        </div>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import draggable from 'vuedraggable';
import {
  WIDGET_CATEGORIES,
  WIDGET_DEFINITIONS,
  createWidget,
  createSectionPreset,
} from './widgetRegistry';
import BuilderWidgetRenderer from './BuilderWidgetRenderer.vue';
import WidgetInspector from './WidgetInspector.vue';

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['update:modelValue', 'open-media']);

const layoutTree = ref(props.modelValue && props.modelValue.length > 0 ? JSON.parse(JSON.stringify(props.modelValue)) : []);
const sidebarTab = ref('elements');
const widgetSearch = ref('');
const previewMode = ref(false);
const viewport = ref('desktop');

const selectedSection = ref(null);
const selectedColumn = ref(null);
const selectedWidget = ref(null);
const activeColumnId = ref(null);
const activeSectionRef = ref(null);
const activeColumnRef = ref(null);
const mediaTargetRef = ref(null);

// Undo / Redo History
const historyStack = ref([JSON.stringify(layoutTree.value)]);
const historyIndex = ref(0);
let isRestoringHistory = false;

const recordHistory = () => {
  if (isRestoringHistory) return;
  const snapshot = JSON.stringify(layoutTree.value);
  if (historyStack.value[historyIndex.value] === snapshot) return;
  historyStack.value = historyStack.value.slice(0, historyIndex.value + 1);
  historyStack.value.push(snapshot);
  if (historyStack.value.length > 30) historyStack.value.shift();
  historyIndex.value = historyStack.value.length - 1;
};

const undo = () => {
  if (historyIndex.value > 0) {
    isRestoringHistory = true;
    historyIndex.value--;
    layoutTree.value = JSON.parse(historyStack.value[historyIndex.value]);
    clearSelection();
    setTimeout(() => { isRestoringHistory = false; }, 0);
  }
};

const redo = () => {
  if (historyIndex.value < historyStack.value.length - 1) {
    isRestoringHistory = true;
    historyIndex.value++;
    layoutTree.value = JSON.parse(historyStack.value[historyIndex.value]);
    clearSelection();
    setTimeout(() => { isRestoringHistory = false; }, 0);
  }
};

const viewportWidth = computed(() => {
  if (viewport.value === 'mobile') return '375px';
  if (viewport.value === 'tablet') return '768px';
  return '100%';
});

const collapsedCategories = ref(
  Object.fromEntries(WIDGET_CATEGORIES.map((cat, idx) => [cat.id, idx !== 0]))
);

const isCategoryCollapsed = (catId) => {
  if (widgetSearch.value.trim()) return false;
  return Boolean(collapsedCategories.value[catId]);
};

const toggleCategory = (catId) => {
  collapsedCategories.value = {
    ...collapsedCategories.value,
    [catId]: !collapsedCategories.value[catId],
  };
};

const filteredCategories = computed(() => {
  const q = widgetSearch.value.trim().toLowerCase();
  return WIDGET_CATEGORIES.map((cat) => {
    const widgets = WIDGET_DEFINITIONS.filter(
      (w) => w.category === cat.id && (!q || w.label.toLowerCase().includes(q) || w.type.includes(q))
    );
    return { ...cat, widgets };
  }).filter((cat) => cat.widgets.length > 0);
});

const cloneWidget = (def) => {
  return createWidget(def.type);
};

const clearSelection = () => {
  selectedSection.value = null;
  selectedColumn.value = null;
  selectedWidget.value = null;
};

const addSection = (colWidths = [100]) => {
  const sectionId = 'sec_' + Date.now();
  const columns = colWidths.map((w, idx) => ({
    id: `col_${sectionId}_${idx}`,
    width: `${w}%`,
    padding: '10px',
    widgets: [],
  }));

  const newSection = {
    id: sectionId,
    name: `Section (${colWidths.join('/')})`,
    containerWidth: 'boxed',
    backgroundColor: '#ffffff',
    backgroundGradient: '',
    textColor: '#1e293b',
    paddingTop: '32px',
    paddingBottom: '32px',
    columns,
  };

  layoutTree.value.push(newSection);
  selectColumn(columns[0], newSection);
  recordHistory();
};

const setSectionColumns = (widths) => {
  const section = selectedSection.value;
  if (!section || !Array.isArray(widths) || widths.length === 0) return;

  if (widths.length < section.columns.length) {
    const targetCol = section.columns[widths.length - 1];
    for (let i = widths.length; i < section.columns.length; i++) {
      if (Array.isArray(section.columns[i].widgets) && section.columns[i].widgets.length > 0) {
        targetCol.widgets.push(...section.columns[i].widgets);
      }
    }
    section.columns.length = widths.length;
  } else if (widths.length > section.columns.length) {
    for (let i = section.columns.length; i < widths.length; i++) {
      section.columns.push({
        id: `col_${section.id}_${Date.now()}_${i}`,
        width: `${widths[i]}%`,
        padding: '10px',
        widgets: [],
      });
    }
  }

  section.columns.forEach((col, i) => {
    col.width = `${widths[i]}%`;
  });
  section.stackColumns = false;
  recordHistory();
};

const insertPreset = (presetId) => {
  const section = createSectionPreset(presetId);
  layoutTree.value.push(section);
  selectSection(section);
  recordHistory();
};

const selectSection = (section) => {
  selectedSection.value = section;
  selectedColumn.value = null;
  selectedWidget.value = null;
  sidebarTab.value = 'inspector';
};

const selectColumn = (column, section) => {
  activeSectionRef.value = section;
  activeColumnRef.value = column;
  activeColumnId.value = column.id;
  selectedColumn.value = column;
  selectedSection.value = null;
  selectedWidget.value = null;
  sidebarTab.value = 'inspector';
};

const selectWidget = (widget, column, section) => {
  selectedWidget.value = widget;
  selectedSection.value = null;
  selectedColumn.value = column;
  activeColumnRef.value = column;
  activeSectionRef.value = section;
  activeColumnId.value = column.id;
  sidebarTab.value = 'inspector';
};

const addWidgetToCurrent = (type) => {
  if (!activeColumnRef.value) {
    if (layoutTree.value.length === 0) {
      addSection([100]);
    } else {
      activeSectionRef.value = layoutTree.value[0];
      activeColumnRef.value = layoutTree.value[0].columns[0];
      activeColumnId.value = activeColumnRef.value.id;
    }
  }

  const newWidget = createWidget(type);
  activeColumnRef.value.widgets.push(newWidget);
  selectWidget(newWidget, activeColumnRef.value, activeSectionRef.value);
  recordHistory();
};

const duplicateSection = (index) => {
  const orig = layoutTree.value[index];
  const copy = JSON.parse(JSON.stringify(orig));
  const ts = Date.now();
  copy.id = `sec_${ts}`;
  copy.name = `${copy.name} (Copy)`;
  copy.columns?.forEach((c, ci) => {
    c.id = `col_${ts}_${ci}`;
    c.widgets?.forEach((w, wi) => {
      w.id = `w_${ts}_${ci}_${wi}`;
    });
  });
  layoutTree.value.splice(index + 1, 0, copy);
  recordHistory();
};

const duplicateCurrentSection = () => {
  if (!selectedSection.value) return;
  const idx = layoutTree.value.findIndex((s) => s.id === selectedSection.value.id);
  if (idx !== -1) duplicateSection(idx);
};

const moveSection = (index, dir) => {
  const target = index + dir;
  if (target < 0 || target >= layoutTree.value.length) return;
  const item = layoutTree.value.splice(index, 1)[0];
  layoutTree.value.splice(target, 0, item);
  recordHistory();
};

const removeSection = (index) => {
  layoutTree.value.splice(index, 1);
  clearSelection();
  recordHistory();
};

const deleteCurrentSection = () => {
  if (selectedSection.value) {
    const idx = layoutTree.value.findIndex((s) => s.id === selectedSection.value.id);
    if (idx !== -1) removeSection(idx);
  }
};

const duplicateWidget = (column, index) => {
  const copy = JSON.parse(JSON.stringify(column.widgets[index]));
  copy.id = 'w_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
  column.widgets.splice(index + 1, 0, copy);
  recordHistory();
};

const duplicateCurrentWidget = () => {
  if (activeColumnRef.value && selectedWidget.value) {
    const idx = activeColumnRef.value.widgets.findIndex((w) => w.id === selectedWidget.value.id);
    if (idx !== -1) duplicateWidget(activeColumnRef.value, idx);
  }
};

const moveWidget = (column, index, dir) => {
  const target = index + dir;
  if (target < 0 || target >= column.widgets.length) return;
  const item = column.widgets.splice(index, 1)[0];
  column.widgets.splice(target, 0, item);
  recordHistory();
};

const deleteWidget = (column, index) => {
  column.widgets.splice(index, 1);
  selectedWidget.value = null;
  recordHistory();
};

const deleteCurrentWidget = () => {
  if (activeColumnRef.value && selectedWidget.value) {
    const idx = activeColumnRef.value.widgets.findIndex((w) => w.id === selectedWidget.value.id);
    if (idx !== -1) deleteWidget(activeColumnRef.value, idx);
  }
};

const openMediaForTarget = (target) => {
  mediaTargetRef.value = target;
  emit('open-media');
};

const setWidgetImage = (url, alt) => {
  if (mediaTargetRef.value && mediaTargetRef.value.obj) {
    mediaTargetRef.value.obj[mediaTargetRef.value.field] = url;
    if (mediaTargetRef.value.field === 'url' && alt !== undefined && mediaTargetRef.value.obj.alt !== undefined) {
      mediaTargetRef.value.obj.alt = alt;
    }
    recordHistory();
  }
};

defineExpose({ setWidgetImage });

watch(
  layoutTree,
  () => {
    emit('update:modelValue', layoutTree.value);
  },
  { deep: true }
);
</script>
