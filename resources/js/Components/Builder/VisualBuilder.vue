<template>
  <div class="flex flex-col lg:flex-row gap-4 bg-slate-100 p-2 rounded-xl border border-slate-300">
    
    <!-- Left / Sidebar: Widgets Inventory & Live Inspector -->
    <div class="w-full lg:w-80 shrink-0 bg-white rounded-xl border border-slate-200/90 shadow-xs flex flex-col h-[750px] overflow-hidden">
      
      <!-- Sidebar Tabs -->
      <div class="flex border-b border-slate-200 bg-slate-50 text-xs font-semibold">
        <button 
          type="button"
          @click="sidebarTab = 'elements'"
          :class="sidebarTab === 'elements' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800'"
          class="flex-1 py-3 text-center transition flex items-center justify-center space-x-1.5"
        >
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
          <span>Widgets</span>
        </button>

        <button 
          type="button"
          @click="sidebarTab = 'inspector'"
          :class="sidebarTab === 'inspector' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800'"
          class="flex-1 py-3 text-center transition flex items-center justify-center space-x-1.5"
        >
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
          </svg>
          <span>Inspector</span>
          <span v-if="selectedWidget || selectedSection" class="w-2 h-2 rounded-full bg-indigo-600"></span>
        </button>
      </div>

      <!-- Sidebar Body -->
      <div class="flex-1 overflow-y-auto p-4">
        
        <!-- Tab 1: Widget Inventory -->
        <div v-if="sidebarTab === 'elements'" class="space-y-4">
          <p class="text-xs text-slate-500 font-medium">
            Click to add widgets into your active column, or insert new sections below.
          </p>

          <div class="grid grid-cols-2 gap-2.5">
            <button 
              type="button"
              v-for="widget in availableWidgets" 
              :key="widget.type"
              @click="addWidgetToCurrent(widget.type)"
              class="flex flex-col items-center justify-center p-3.5 bg-slate-50 hover:bg-indigo-50/70 border border-slate-200 hover:border-indigo-300 rounded-xl transition text-center group cursor-pointer"
            >
              <component :is="widget.icon" class="w-5 h-5 text-slate-600 group-hover:text-indigo-600 mb-1.5 transition" />
              <span class="text-xs font-semibold text-slate-700 group-hover:text-indigo-900">{{ widget.label }}</span>
            </button>
          </div>

          <!-- Section Presets -->
          <div class="pt-4 border-t border-slate-200">
            <p class="text-xs font-bold text-slate-700 mb-2">Add New Section Layout</p>
            <div class="grid grid-cols-2 gap-2">
              <button 
                type="button" 
                @click="addSection([100])"
                class="px-2.5 py-2 text-xs font-medium bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 border border-slate-300 transition text-center"
              >
                1 Column (100%)
              </button>
              <button 
                type="button" 
                @click="addSection([50, 50])"
                class="px-2.5 py-2 text-xs font-medium bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 border border-slate-300 transition text-center"
              >
                2 Columns (50/50)
              </button>
              <button 
                type="button" 
                @click="addSection([33.33, 66.66])"
                class="px-2.5 py-2 text-xs font-medium bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 border border-slate-300 transition text-center"
              >
                2 Cols (33 / 66)
              </button>
              <button 
                type="button" 
                @click="addSection([33.33, 33.33, 33.33])"
                class="px-2.5 py-2 text-xs font-medium bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 border border-slate-300 transition text-center"
              >
                3 Columns (33x3)
              </button>
            </div>
          </div>
        </div>

        <!-- Tab 2: Live Property Inspector -->
        <div v-else class="space-y-4">
          
          <!-- Selected Widget Inspector -->
          <div v-if="selectedWidget" class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
              <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">
                Edit {{ selectedWidget.type }}
              </span>
              <button 
                type="button" 
                @click="selectedWidget = null" 
                class="text-xs text-slate-400 hover:text-slate-600"
              >
                Deselect
              </button>
            </div>

            <!-- Content Inputs by Widget Type -->
            <div v-if="['heading', 'text', 'button'].includes(selectedWidget.type)" class="space-y-2">
              <label class="block text-xs font-semibold text-slate-700">Content / Text</label>
              <textarea 
                v-if="selectedWidget.type === 'text'"
                v-model="selectedWidget.content" 
                rows="4" 
                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-indigo-500"
              ></textarea>
              <input 
                v-else
                v-model="selectedWidget.content" 
                type="text" 
                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-indigo-500"
              />
            </div>

            <!-- Heading Tag -->
            <div v-if="selectedWidget.type === 'heading'" class="space-y-1">
              <label class="block text-xs font-semibold text-slate-700">HTML Tag</label>
              <select 
                v-model="selectedWidget.tag" 
                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg"
              >
                <option value="h1">H1 - Main Page Title</option>
                <option value="h2">H2 - Section Heading</option>
                <option value="h3">H3 - Sub Heading</option>
                <option value="h4">H4 - Minor Heading</option>
              </select>
            </div>

            <!-- Image Picker / URL -->
            <div v-if="selectedWidget.type === 'image'" class="space-y-2">
              <label class="block text-xs font-semibold text-slate-700">Image Source</label>
              <div class="flex items-center space-x-2">
                <input 
                  v-model="selectedWidget.url" 
                  type="text" 
                  placeholder="https://... or select WebP"
                  class="flex-1 px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg"
                />
                <button 
                  type="button" 
                  @click="openMediaForWidget(selectedWidget)"
                  class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg border border-indigo-200 transition"
                >
                  Media
                </button>
              </div>
              <input 
                v-model="selectedWidget.alt" 
                type="text" 
                placeholder="Alt text"
                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg"
              />
            </div>

            <!-- Button URL -->
            <div v-if="selectedWidget.type === 'button'" class="space-y-1">
              <label class="block text-xs font-semibold text-slate-700">Target URL</label>
              <input 
                v-model="selectedWidget.url" 
                type="text" 
                placeholder="/contact or https://..."
                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg"
              />
            </div>

            <!-- Typography & Styles -->
            <div class="space-y-3 pt-3 border-t border-slate-200">
              <p class="text-xs font-bold text-slate-700">Style & Appearance</p>

              <!-- Color -->
              <div v-if="selectedWidget.color !== undefined" class="flex items-center justify-between">
                <label class="text-xs text-slate-600">Text Color</label>
                <div class="flex items-center space-x-2">
                  <input v-model="selectedWidget.color" type="color" class="w-6 h-6 rounded cursor-pointer border" />
                  <input v-model="selectedWidget.color" type="text" class="w-20 px-1 py-0.5 text-xs border rounded font-mono" />
                </div>
              </div>

              <!-- Background Color for button/card -->
              <div v-if="selectedWidget.backgroundColor !== undefined" class="flex items-center justify-between">
                <label class="text-xs text-slate-600">Background Color</label>
                <div class="flex items-center space-x-2">
                  <input v-model="selectedWidget.backgroundColor" type="color" class="w-6 h-6 rounded cursor-pointer border" />
                  <input v-model="selectedWidget.backgroundColor" type="text" class="w-20 px-1 py-0.5 text-xs border rounded font-mono" />
                </div>
              </div>

              <!-- Alignment -->
              <div v-if="selectedWidget.textAlign !== undefined" class="flex items-center justify-between">
                <label class="text-xs text-slate-600">Alignment</label>
                <div class="flex items-center space-x-1 bg-slate-100 p-0.5 rounded-lg border">
                  <button 
                    type="button" 
                    @click="selectedWidget.textAlign = 'left'"
                    :class="selectedWidget.textAlign === 'left' ? 'bg-white shadow-xs text-indigo-600 font-bold' : 'text-slate-600'"
                    class="px-2 py-1 text-xs rounded"
                  >
                    L
                  </button>
                  <button 
                    type="button" 
                    @click="selectedWidget.textAlign = 'center'"
                    :class="selectedWidget.textAlign === 'center' ? 'bg-white shadow-xs text-indigo-600 font-bold' : 'text-slate-600'"
                    class="px-2 py-1 text-xs rounded"
                  >
                    C
                  </button>
                  <button 
                    type="button" 
                    @click="selectedWidget.textAlign = 'right'"
                    :class="selectedWidget.textAlign === 'right' ? 'bg-white shadow-xs text-indigo-600 font-bold' : 'text-slate-600'"
                    class="px-2 py-1 text-xs rounded"
                  >
                    R
                  </button>
                </div>
              </div>

              <!-- Font Size -->
              <div v-if="selectedWidget.fontSize !== undefined" class="space-y-1">
                <div class="flex justify-between text-xs text-slate-600">
                  <span>Font Size</span>
                  <span class="font-mono">{{ selectedWidget.fontSize }}</span>
                </div>
                <input 
                  v-model="selectedWidget.fontSize" 
                  type="text" 
                  placeholder="e.g. 24px or 1.5rem"
                  class="w-full px-2.5 py-1 text-xs border rounded"
                />
              </div>

              <!-- Spacer height -->
              <div v-if="selectedWidget.type === 'spacer'" class="space-y-1">
                <div class="flex justify-between text-xs text-slate-600">
                  <span>Spacer Height</span>
                  <span class="font-mono">{{ selectedWidget.height }}</span>
                </div>
                <input 
                  v-model="selectedWidget.height" 
                  type="text" 
                  placeholder="e.g. 40px"
                  class="w-full px-2.5 py-1 text-xs border rounded"
                />
              </div>
            </div>

            <!-- Delete Widget Button -->
            <div class="pt-4 border-t border-slate-200">
              <button 
                type="button" 
                @click="deleteCurrentWidget"
                class="w-full py-1.5 text-xs text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200 rounded-lg transition"
              >
                Delete Widget
              </button>
            </div>
          </div>

          <!-- Selected Section Inspector -->
          <div v-else-if="selectedSection" class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
              <span class="text-xs font-bold uppercase tracking-wider text-blue-700">
                Edit Section
              </span>
              <button type="button" @click="selectedSection = null" class="text-xs text-slate-400 hover:text-slate-600">
                Deselect
              </button>
            </div>

            <div class="space-y-2">
              <label class="block text-xs font-semibold text-slate-700">Section Label</label>
              <input 
                v-model="selectedSection.name" 
                type="text" 
                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg"
              />
            </div>

            <!-- Background Color -->
            <div class="flex items-center justify-between">
              <label class="text-xs text-slate-600">Background Color</label>
              <div class="flex items-center space-x-2">
                <input v-model="selectedSection.backgroundColor" type="color" class="w-6 h-6 rounded cursor-pointer border" />
                <input v-model="selectedSection.backgroundColor" type="text" class="w-20 px-1 py-0.5 text-xs border rounded font-mono" />
              </div>
            </div>

            <!-- Text Color -->
            <div class="flex items-center justify-between">
              <label class="text-xs text-slate-600">Default Text Color</label>
              <div class="flex items-center space-x-2">
                <input v-model="selectedSection.textColor" type="color" class="w-6 h-6 rounded cursor-pointer border" />
                <input v-model="selectedSection.textColor" type="text" class="w-20 px-1 py-0.5 text-xs border rounded font-mono" />
              </div>
            </div>

            <!-- Padding Top / Bottom -->
            <div class="grid grid-cols-2 gap-2">
              <div>
                <label class="text-xs text-slate-600 block">Padding Top</label>
                <input v-model="selectedSection.paddingTop" type="text" class="w-full px-2 py-1 text-xs border rounded mt-1" />
              </div>
              <div>
                <label class="text-xs text-slate-600 block">Padding Bottom</label>
                <input v-model="selectedSection.paddingBottom" type="text" class="w-full px-2 py-1 text-xs border rounded mt-1" />
              </div>
            </div>

            <div class="pt-4 border-t border-slate-200">
              <button 
                type="button" 
                @click="deleteCurrentSection"
                class="w-full py-1.5 text-xs text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200 rounded-lg transition"
              >
                Delete Section
              </button>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="py-16 text-center text-slate-400 text-xs">
            <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
            </svg>
            Click any section or widget on the canvas to inspect and edit its live properties.
          </div>

        </div>

      </div>

    </div>

    <!-- Right: Visual Tree Canvas -->
    <div class="flex-1 bg-white rounded-xl border border-slate-200/90 shadow-xs flex flex-col h-[750px] overflow-hidden">
      
      <!-- Canvas Bar -->
      <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center space-x-2">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
            Visual Tree Canvas
          </span>
          <span class="text-[11px] text-slate-400">
            ({{ layoutTree.length }} sections)
          </span>
        </div>

        <div class="flex items-center space-x-2">
          <button 
            type="button"
            @click="previewMode = !previewMode"
            :class="previewMode ? 'bg-indigo-600 text-white' : 'bg-white text-slate-700 border border-slate-300'"
            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition flex items-center space-x-1"
          >
            <span>{{ previewMode ? 'Exit Preview' : 'Live Preview' }}</span>
          </button>
        </div>
      </div>

      <!-- Canvas Scrollable Workspace -->
      <div class="flex-1 overflow-y-auto p-4 space-y-4 builder-canvas">
        
        <div v-if="layoutTree.length === 0" class="py-24 text-center border-2 border-dashed border-slate-300 rounded-xl">
          <p class="text-sm font-semibold text-slate-700">Visual canvas is empty</p>
          <p class="text-xs text-slate-400 mt-1">Select a section layout preset in the left sidebar to start building.</p>
          <button 
            type="button" 
            @click="addSection([50, 50])"
            class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition"
          >
            + Add 2-Column Hero Section
          </button>
        </div>

        <!-- Render Sections -->
        <div 
          v-for="(section, sIndex) in layoutTree" 
          :key="section.id"
          @click.stop="selectSection(section)"
          :class="[
            !previewMode && selectedSection?.id === section.id ? 'builder-section-selected ring-2 ring-blue-500' : '',
            !previewMode ? 'relative group border border-slate-200 hover:border-blue-400 rounded-xl transition overflow-hidden' : 'rounded-xl overflow-hidden'
          ]"
          :style="{
            backgroundColor: section.backgroundColor || '#ffffff',
            color: section.textColor || '#1e293b',
            paddingTop: section.paddingTop || '32px',
            paddingBottom: section.paddingBottom || '32px',
          }"
          class="transition shadow-2xs"
        >
          <!-- Section Controls Header (Editor only) -->
          <div 
            v-if="!previewMode" 
            class="absolute top-2 right-2 bg-slate-900/80 backdrop-blur-xs text-white px-2 py-1 rounded-md text-[10px] flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition z-20"
          >
            <span class="font-medium text-slate-200">{{ section.name || 'Section ' + (sIndex + 1) }}</span>
            <button type="button" @click.stop="moveSection(sIndex, -1)" :disabled="sIndex === 0" class="hover:text-indigo-400 disabled:opacity-30">&uarr;</button>
            <button type="button" @click.stop="moveSection(sIndex, 1)" :disabled="sIndex === layoutTree.length - 1" class="hover:text-indigo-400 disabled:opacity-30">&darr;</button>
            <button type="button" @click.stop="removeSection(sIndex)" class="hover:text-rose-400">&times;</button>
          </div>

          <!-- Section Columns Container -->
          <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row gap-4">
            
            <div 
              v-for="(column, cIndex) in section.columns" 
              :key="column.id"
              @click.stop="activateColumn(section, column)"
              :style="{ width: column.width || '100%' }"
              :class="[
                !previewMode && activeColumnId === column.id ? 'border-indigo-400 bg-indigo-50/20' : 'border-dashed border-slate-300 hover:border-slate-400',
                !previewMode ? 'border min-h-[120px] rounded-lg p-2.5 transition relative' : ''
              ]"
            >
              <!-- Column tag in edit mode -->
              <span v-if="!previewMode" class="text-[9px] font-mono font-semibold text-slate-400 block mb-1">
                Col ({{ column.width }})
              </span>

              <!-- Column Widgets -->
              <div v-if="column.widgets.length === 0 && !previewMode" class="py-6 text-center text-slate-300 text-[11px]">
                Drop / Click widget to add
              </div>

              <div 
                v-for="(widget, wIndex) in column.widgets" 
                :key="widget.id"
                @click.stop="selectWidget(widget, column, section)"
                :class="[
                  !previewMode && selectedWidget?.id === widget.id ? 'builder-widget-selected ring-2 ring-purple-500' : '',
                  !previewMode ? 'relative group/widget hover:ring-1 hover:ring-purple-300 rounded p-1.5 transition' : ''
                ]"
              >
                <!-- Widget Quick Controls (Move / Delete) -->
                <div 
                  v-if="!previewMode" 
                  class="absolute top-0 right-0 bg-purple-900/90 text-white px-1.5 py-0.5 rounded text-[9px] hidden group-hover/widget:flex items-center space-x-1.5 z-10"
                >
                  <button type="button" @click.stop="moveWidget(column, wIndex, -1)" :disabled="wIndex === 0">&uarr;</button>
                  <button type="button" @click.stop="moveWidget(column, wIndex, 1)" :disabled="wIndex === column.widgets.length - 1">&darr;</button>
                  <button type="button" @click.stop="deleteWidget(column, wIndex)" class="text-rose-300">&times;</button>
                </div>

                <!-- Render Widget Dynamically -->
                <!-- Heading Widget -->
                <component 
                  v-if="widget.type === 'heading'"
                  :is="widget.tag || 'h2'"
                  :style="{
                    color: widget.color || 'inherit',
                    fontSize: widget.fontSize,
                    textAlign: widget.textAlign || 'left',
                    marginBottom: widget.marginBottom || '0.5rem',
                  }"
                  class="font-bold tracking-tight"
                >
                  {{ widget.content || 'Heading Text' }}
                </component>

                <!-- Text Widget -->
                <p 
                  v-else-if="widget.type === 'text'"
                  :style="{
                    color: widget.color || 'inherit',
                    fontSize: widget.fontSize || '15px',
                    textAlign: widget.textAlign || 'left',
                    lineHeight: widget.lineHeight || '1.6',
                    marginBottom: widget.marginBottom || '0.5rem',
                  }"
                >
                  {{ widget.content || 'Lorem ipsum dolor sit amet...' }}
                </p>

                <!-- Image Widget -->
                <div 
                  v-else-if="widget.type === 'image'"
                  :style="{ textAlign: widget.textAlign || 'center' }"
                >
                  <img 
                    :src="widget.url || 'https://via.placeholder.com/600x300?text=WebP+Image'" 
                    :alt="widget.alt || 'Visual Builder Image'"
                    :style="{ borderRadius: widget.borderRadius || '8px' }"
                    class="inline-block max-w-full h-auto shadow-xs"
                    loading="lazy"
                  />
                </div>

                <!-- Button Widget -->
                <div 
                  v-else-if="widget.type === 'button'"
                  :style="{ textAlign: widget.textAlign || 'left' }"
                >
                  <a 
                    :href="widget.url || '#'" 
                    @click.prevent 
                    :style="{
                      backgroundColor: widget.backgroundColor || '#4f46e5',
                      color: widget.textColor || '#ffffff',
                      borderRadius: widget.borderRadius || '8px',
                      padding: '8px 20px',
                    }"
                    class="inline-block text-xs font-semibold shadow-xs hover:opacity-90 transition"
                  >
                    {{ widget.content || 'Click Me' }}
                  </a>
                </div>

                <!-- Spacer Widget -->
                <div 
                  v-else-if="widget.type === 'spacer'"
                  :style="{ height: widget.height || '32px' }"
                  class="w-full"
                ></div>

                <!-- Divider Widget -->
                <hr 
                  v-else-if="widget.type === 'divider'"
                  class="my-3 border-t border-slate-300"
                />

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, watch, h } from 'vue';

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['update:modelValue', 'open-media']);

const layoutTree = ref(props.modelValue && props.modelValue.length > 0 ? JSON.parse(JSON.stringify(props.modelValue)) : []);
const sidebarTab = ref('elements');
const previewMode = ref(false);

const selectedSection = ref(null);
const selectedWidget = ref(null);
const currentWidgetTarget = ref(null);
const activeColumnId = ref(null);
const activeSectionRef = ref(null);
const activeColumnRef = ref(null);

// Icons
const HeadingIcon = () => h('span', { class: 'font-bold text-xs' }, 'H');
const TextIcon = () => h('span', { class: 'text-xs' }, '¶');
const ImageIcon = () => h('span', { class: 'text-xs' }, '🖼');
const ButtonIcon = () => h('span', { class: 'text-xs font-semibold' }, '[BTN]');
const SpacerIcon = () => h('span', { class: 'text-xs' }, '↕');
const DividerIcon = () => h('span', { class: 'text-xs' }, '—');

const availableWidgets = [
  { type: 'heading', label: 'Heading', icon: HeadingIcon },
  { type: 'text', label: 'Paragraph', icon: TextIcon },
  { type: 'image', label: 'WebP Image', icon: ImageIcon },
  { type: 'button', label: 'Button', icon: ButtonIcon },
  { type: 'spacer', label: 'Spacer', icon: SpacerIcon },
  { type: 'divider', label: 'Divider', icon: DividerIcon },
];

// Add Section with Column Layout
const addSection = (colWidths = [100]) => {
  const sectionId = 'sec_' + Date.now();
  const columns = colWidths.map((w, idx) => ({
    id: `col_${sectionId}_${idx}`,
    width: `${w}%`,
    widgets: [],
  }));

  const newSection = {
    id: sectionId,
    name: `Section (${colWidths.join('/')})`,
    backgroundColor: '#ffffff',
    textColor: '#1e293b',
    paddingTop: '32px',
    paddingBottom: '32px',
    columns,
  };

  layoutTree.value.push(newSection);
  activateColumn(newSection, columns[0]);
  emitChange();
};

const activateColumn = (section, column) => {
  activeSectionRef.value = section;
  activeColumnRef.value = column;
  activeColumnId.value = column.id;
};

const selectSection = (section) => {
  selectedSection.value = section;
  selectedWidget.value = null;
  sidebarTab.value = 'inspector';
};

const selectWidget = (widget, column, section) => {
  selectedWidget.value = widget;
  selectedSection.value = null;
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
      activeColumnRef.value = layoutTree.value[0].columns[0];
      activeColumnId.value = activeColumnRef.value.id;
    }
  }

  const newWidget = createDefaultWidget(type);
  activeColumnRef.value.widgets.push(newWidget);
  selectWidget(newWidget, activeColumnRef.value, activeSectionRef.value);
  emitChange();
};

const createDefaultWidget = (type) => {
  const id = 'w_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
  switch (type) {
    case 'heading':
      return { id, type: 'heading', tag: 'h2', content: 'New Heading', fontSize: '28px', color: '#1e293b', textAlign: 'left' };
    case 'text':
      return { id, type: 'text', content: 'Add compelling paragraph description text here...', fontSize: '15px', color: '#475569', textAlign: 'left', lineHeight: '1.6' };
    case 'image':
      return { id, type: 'image', url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', alt: 'WebP Media', borderRadius: '8px', textAlign: 'center' };
    case 'button':
      return { id, type: 'button', content: 'Take Action Now', url: '#', backgroundColor: '#4f46e5', textColor: '#ffffff', borderRadius: '8px', textAlign: 'left' };
    case 'spacer':
      return { id, type: 'spacer', height: '32px' };
    case 'divider':
      return { id, type: 'divider' };
    default:
      return { id, type, content: '' };
  }
};

const moveSection = (index, dir) => {
  const target = index + dir;
  if (target < 0 || target >= layoutTree.value.length) return;
  const item = layoutTree.value.splice(index, 1)[0];
  layoutTree.value.splice(target, 0, item);
  emitChange();
};

const removeSection = (index) => {
  layoutTree.value.splice(index, 1);
  selectedSection.value = null;
  emitChange();
};

const moveWidget = (column, index, dir) => {
  const target = index + dir;
  if (target < 0 || target >= column.widgets.length) return;
  const item = column.widgets.splice(index, 1)[0];
  column.widgets.splice(target, 0, item);
  emitChange();
};

const deleteWidget = (column, index) => {
  column.widgets.splice(index, 1);
  selectedWidget.value = null;
  emitChange();
};

const deleteCurrentWidget = () => {
  if (activeColumnRef.value && selectedWidget.value) {
    const idx = activeColumnRef.value.widgets.findIndex(w => w.id === selectedWidget.value.id);
    if (idx !== -1) {
      activeColumnRef.value.widgets.splice(idx, 1);
      selectedWidget.value = null;
      emitChange();
    }
  }
};

const deleteCurrentSection = () => {
  if (selectedSection.value) {
    const idx = layoutTree.value.findIndex(s => s.id === selectedSection.value.id);
    if (idx !== -1) {
      layoutTree.value.splice(idx, 1);
      selectedSection.value = null;
      emitChange();
    }
  }
};

const openMediaForWidget = (widget) => {
  currentWidgetTarget.value = widget;
  emit('open-media');
};

const setWidgetImage = (url, alt) => {
  if (currentWidgetTarget.value) {
    currentWidgetTarget.value.url = url;
    currentWidgetTarget.value.alt = alt || '';
    emitChange();
  }
};

defineExpose({ setWidgetImage });

const emitChange = () => {
  emit('update:modelValue', layoutTree.value);
};

watch(
  layoutTree,
  () => {
    emitChange();
  },
  { deep: true }
);
</script>

