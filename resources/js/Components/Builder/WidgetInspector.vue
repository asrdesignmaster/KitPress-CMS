<template>
  <div class="space-y-4">
    
    <!-- WIDGET INSPECTOR -->
    <div v-if="selectedWidget" class="space-y-4">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">
          {{ selectedWidget.type.replace('_', ' ') }}
        </span>
        <div class="flex items-center space-x-2">
          <button type="button" @click="$emit('duplicate-widget')" class="text-[11px] text-indigo-600 hover:underline font-medium">
            Duplicate
          </button>
          <button type="button" @click="$emit('deselect')" class="text-xs text-slate-400 hover:text-slate-600">
            &times;
          </button>
        </div>
      </div>

      <!-- Content / Style Sub-tabs -->
      <div class="flex bg-slate-100 p-0.5 rounded-lg text-[11px] font-semibold">
        <button
          type="button"
          @click="inspectorSubTab = 'content'"
          :class="inspectorSubTab === 'content' ? 'bg-white text-indigo-700 shadow-2xs' : 'text-slate-600'"
          class="flex-1 py-1.5 rounded-md transition"
        >
          Content
        </button>
        <button
          type="button"
          @click="inspectorSubTab = 'style'"
          :class="inspectorSubTab === 'style' ? 'bg-white text-indigo-700 shadow-2xs' : 'text-slate-600'"
          class="flex-1 py-1.5 rounded-md transition"
        >
          Style & Spacing
        </button>
      </div>

      <!-- TAB 1: CONTENT -->
      <div v-show="inspectorSubTab === 'content'" class="space-y-3 text-xs">
        
        <!-- Heading / Text / Button content -->
        <div v-if="['heading', 'text', 'button'].includes(selectedWidget.type)" class="space-y-1">
          <label class="font-semibold text-slate-700 block">Text Content</label>
          <textarea
            v-if="selectedWidget.type === 'text'"
            v-model="selectedWidget.content"
            rows="4"
            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg"
          ></textarea>
          <input
            v-else
            v-model="selectedWidget.content"
            type="text"
            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg"
          />
        </div>

        <!-- Heading HTML Tag -->
        <div v-if="selectedWidget.type === 'heading'" class="space-y-1">
          <label class="font-semibold text-slate-700 block">HTML Tag</label>
          <select v-model="selectedWidget.tag" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
            <option value="h1">H1</option>
            <option value="h2">H2</option>
            <option value="h3">H3</option>
            <option value="h4">H4</option>
          </select>
        </div>

        <!-- Image / Image Box -->
        <div v-if="['image', 'image_box'].includes(selectedWidget.type)" class="space-y-2">
          <label class="font-semibold text-slate-700 block">Image URL</label>
          <div class="flex items-center space-x-1.5">
            <input v-model="selectedWidget.url" type="text" class="flex-1 px-2 py-1.5 bg-slate-50 border border-slate-300 rounded-lg" />
            <button
              type="button"
              @click="$emit('open-media', { obj: selectedWidget, field: 'url' })"
              class="px-2.5 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg font-medium"
            >
              Media
            </button>
          </div>
          <input v-if="selectedWidget.type === 'image'" v-model="selectedWidget.alt" type="text" placeholder="Alt text" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg" />
        </div>

        <!-- Button URL & options -->
        <div v-if="selectedWidget.type === 'button'" class="space-y-2">
          <label class="font-semibold text-slate-700 block">Target URL</label>
          <input v-model="selectedWidget.url" type="text" placeholder="https://..." class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg" />
          <label class="flex items-center space-x-2">
            <input v-model="selectedWidget.showArrow" type="checkbox" class="rounded text-indigo-600" />
            <span class="text-slate-700">Show arrow (&rarr;)</span>
          </label>
        </div>

        <!-- Icon Box & Image Box -->
        <div v-if="['icon_box', 'image_box'].includes(selectedWidget.type)" class="space-y-2">
          <div v-if="selectedWidget.type === 'icon_box'">
            <label class="font-semibold text-slate-700 block mb-1">Icon / Emoji</label>
            <input v-model="selectedWidget.icon" type="text" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg" />
          </div>
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Title</label>
            <input v-model="selectedWidget.title" type="text" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg" />
          </div>
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Description</label>
            <textarea v-model="selectedWidget.description" rows="3" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg"></textarea>
          </div>
          <div v-if="selectedWidget.type === 'image_box'" class="grid grid-cols-2 gap-2">
            <input v-model="selectedWidget.buttonText" placeholder="Link text" class="px-2 py-1 border rounded bg-slate-50" />
            <input v-model="selectedWidget.buttonUrl" placeholder="URL" class="px-2 py-1 border rounded bg-slate-50" />
          </div>
        </div>

        <!-- Counter -->
        <div v-if="selectedWidget.type === 'counter'" class="space-y-2">
          <div class="grid grid-cols-3 gap-1.5">
            <div>
              <label class="text-[11px] text-slate-500">Prefix</label>
              <input v-model="selectedWidget.prefix" type="text" class="w-full px-2 py-1 border rounded bg-slate-50" />
            </div>
            <div>
              <label class="text-[11px] text-slate-500">Number</label>
              <input v-model="selectedWidget.number" type="text" class="w-full px-2 py-1 border rounded bg-slate-50" />
            </div>
            <div>
              <label class="text-[11px] text-slate-500">Suffix</label>
              <input v-model="selectedWidget.suffix" type="text" class="w-full px-2 py-1 border rounded bg-slate-50" />
            </div>
          </div>
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Label</label>
            <input v-model="selectedWidget.title" type="text" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
          </div>
        </div>

        <!-- Progress Bar -->
        <div v-if="selectedWidget.type === 'progress_bar'" class="space-y-2">
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Title</label>
            <input v-model="selectedWidget.title" type="text" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
          </div>
          <div>
            <div class="flex justify-between mb-1">
              <label class="font-semibold text-slate-700">Percentage</label>
              <span class="font-mono">{{ selectedWidget.percentage }}%</span>
            </div>
            <input v-model.number="selectedWidget.percentage" type="range" min="0" max="100" class="w-full" />
          </div>
          <label class="flex items-center space-x-2">
            <input v-model="selectedWidget.showPercentage" type="checkbox" class="rounded text-indigo-600" />
            <span>Show percentage</span>
          </label>
        </div>

        <!-- Testimonial -->
        <div v-if="selectedWidget.type === 'testimonial'" class="space-y-2">
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Quote</label>
            <textarea v-model="selectedWidget.quote" rows="3" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50"></textarea>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <input v-model="selectedWidget.authorName" placeholder="Author Name" class="px-2 py-1.5 border rounded-lg bg-slate-50" />
            <input v-model="selectedWidget.authorRole" placeholder="Role / Company" class="px-2 py-1.5 border rounded-lg bg-slate-50" />
          </div>
          <div class="flex items-center space-x-1.5">
            <input v-model="selectedWidget.avatarUrl" placeholder="Avatar URL" class="flex-1 px-2 py-1.5 border rounded-lg bg-slate-50" />
            <button type="button" @click="$emit('open-media', { obj: selectedWidget, field: 'avatarUrl' })" class="px-2 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg">
              Media
            </button>
          </div>
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Rating (1-5)</label>
            <input v-model.number="selectedWidget.rating" type="number" min="1" max="5" class="w-20 px-2 py-1 border rounded bg-slate-50" />
          </div>
        </div>

        <!-- Pricing Table -->
        <div v-if="selectedWidget.type === 'pricing_table'" class="space-y-2">
          <div class="grid grid-cols-2 gap-2">
            <input v-model="selectedWidget.title" placeholder="Plan Name" class="px-2 py-1.5 border rounded bg-slate-50" />
            <input v-model="selectedWidget.badge" placeholder="Badge (e.g. Popular)" class="px-2 py-1.5 border rounded bg-slate-50" />
          </div>
          <input v-model="selectedWidget.subtitle" placeholder="Subtitle" class="w-full px-2 py-1.5 border rounded bg-slate-50" />
          <div class="grid grid-cols-3 gap-1.5">
            <input v-model="selectedWidget.currency" placeholder="$" class="px-2 py-1 border rounded bg-slate-50" />
            <input v-model="selectedWidget.price" placeholder="49" class="px-2 py-1 border rounded bg-slate-50" />
            <input v-model="selectedWidget.period" placeholder="/mo" class="px-2 py-1 border rounded bg-slate-50" />
          </div>
          <div>
            <label class="font-semibold text-slate-700 block mb-1">Features (one per line)</label>
            <textarea
              :value="(selectedWidget.features || []).join('\n')"
              @input="selectedWidget.features = $event.target.value.split('\n')"
              rows="4"
              class="w-full px-2 py-1.5 border rounded-lg bg-slate-50"
            ></textarea>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <input v-model="selectedWidget.buttonText" placeholder="Button Text" class="px-2 py-1 border rounded bg-slate-50" />
            <input v-model="selectedWidget.buttonUrl" placeholder="URL" class="px-2 py-1 border rounded bg-slate-50" />
          </div>
          <label class="flex items-center space-x-2">
            <input v-model="selectedWidget.highlighted" type="checkbox" class="rounded text-indigo-600" />
            <span>Highlight Plan</span>
          </label>
        </div>

        <!-- Alert -->
        <div v-if="selectedWidget.type === 'alert'" class="space-y-2">
          <select v-model="selectedWidget.variant" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50">
            <option value="info">Info (Indigo)</option>
            <option value="success">Success (Emerald)</option>
            <option value="warning">Warning (Amber)</option>
            <option value="danger">Danger (Rose)</option>
          </select>
          <input v-model="selectedWidget.title" placeholder="Title" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
          <textarea v-model="selectedWidget.description" rows="2" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50"></textarea>
          <label class="flex items-center space-x-2">
            <input v-model="selectedWidget.dismissible" type="checkbox" class="rounded text-indigo-600" />
            <span>Dismissible</span>
          </label>
        </div>

        <!-- Accordion & Tabs Repeater -->
        <div v-if="['accordion', 'tabs'].includes(selectedWidget.type)" class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="font-semibold text-slate-700">Items</label>
            <button
              type="button"
              @click="selectedWidget.items.push({ title: 'New Item', content: 'Item details...' })"
              class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-200 text-[11px] font-semibold"
            >
              + Add Item
            </button>
          </div>
          <div
            v-for="(item, idx) in selectedWidget.items"
            :key="idx"
            class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg space-y-1.5"
          >
            <div class="flex items-center justify-between gap-1">
              <input v-model="item.title" class="flex-1 px-2 py-1 border rounded bg-white font-semibold" />
              <button type="button" @click="selectedWidget.items.splice(idx, 1)" class="text-rose-500 px-1">&times;</button>
            </div>
            <textarea v-model="item.content" rows="2" class="w-full px-2 py-1 border rounded bg-white"></textarea>
          </div>
        </div>

        <!-- Slider Repeater -->
        <div v-if="selectedWidget.type === 'slider'" class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="font-semibold text-slate-700">Slides</label>
            <button
              type="button"
              @click="selectedWidget.slides.push({ image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1000&q=80', title: 'New Slide', subtitle: 'Slide subtitle', buttonText: 'Learn More', buttonUrl: '#' })"
              class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-200 text-[11px] font-semibold"
            >
              + Add Slide
            </button>
          </div>
          <div
            v-for="(slide, idx) in selectedWidget.slides"
            :key="idx"
            class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg space-y-1.5"
          >
            <div class="flex items-center justify-between">
              <span class="font-bold text-[10px] text-slate-500">Slide #{{ idx + 1 }}</span>
              <button type="button" @click="selectedWidget.slides.splice(idx, 1)" class="text-rose-500 text-xs">&times;</button>
            </div>
            <div class="flex items-center space-x-1">
              <input v-model="slide.image" placeholder="Image URL" class="flex-1 px-2 py-1 border rounded bg-white text-[11px]" />
              <button type="button" @click="$emit('open-media', { obj: slide, field: 'image' })" class="px-2 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded text-[10px]">
                Media
              </button>
            </div>
            <input v-model="slide.title" placeholder="Title" class="w-full px-2 py-1 border rounded bg-white" />
            <input v-model="slide.subtitle" placeholder="Subtitle" class="w-full px-2 py-1 border rounded bg-white" />
            <div class="grid grid-cols-2 gap-1">
              <input v-model="slide.buttonText" placeholder="Button" class="px-2 py-1 border rounded bg-white" />
              <input v-model="slide.buttonUrl" placeholder="URL" class="px-2 py-1 border rounded bg-white" />
            </div>
          </div>
        </div>

        <!-- Video -->
        <div v-if="selectedWidget.type === 'video'" class="space-y-2">
          <label class="font-semibold text-slate-700 block">YouTube or Vimeo URL</label>
          <input v-model="selectedWidget.url" type="text" placeholder="https://www.youtube.com/watch?v=..." class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
          <label class="font-semibold text-slate-700 block">Aspect Ratio</label>
          <select v-model="selectedWidget.aspectRatio" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50">
            <option value="16/9">16:9 (Widescreen)</option>
            <option value="4/3">4:3 (Classic)</option>
            <option value="1/1">1:1 (Square)</option>
          </select>
        </div>

        <!-- Social Icons Repeater -->
        <div v-if="selectedWidget.type === 'social_icons'" class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="font-semibold text-slate-700">Social Links</label>
            <button
              type="button"
              @click="selectedWidget.items.push({ platform: 'web', label: 'Website', url: 'https://', color: '#4f46e5' })"
              class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-200 text-[11px] font-semibold"
            >
              + Add
            </button>
          </div>
          <div v-for="(item, idx) in selectedWidget.items" :key="idx" class="p-2 bg-slate-50 border rounded-lg space-y-1">
            <div class="flex items-center gap-1">
              <input v-model="item.label" placeholder="Label" class="flex-1 px-2 py-1 border rounded bg-white" />
              <input v-model="item.color" type="color" class="w-6 h-6 rounded border cursor-pointer" />
              <button type="button" @click="selectedWidget.items.splice(idx, 1)" class="text-rose-500 px-1">&times;</button>
            </div>
            <input v-model="item.url" placeholder="https://..." class="w-full px-2 py-1 border rounded bg-white text-[11px]" />
          </div>
        </div>

        <!-- Spacer -->
        <div v-if="selectedWidget.type === 'spacer'" class="space-y-1">
          <label class="font-semibold text-slate-700 block">Height (px)</label>
          <input v-model="selectedWidget.height" type="text" placeholder="32px" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
        </div>

        <!-- Divider -->
        <div v-if="selectedWidget.type === 'divider'" class="space-y-2">
          <label class="font-semibold text-slate-700 block">Line Style</label>
          <select v-model="selectedWidget.style" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50">
            <option value="solid">Solid</option>
            <option value="dashed">Dashed</option>
            <option value="dotted">Dotted</option>
          </select>
          <label class="font-semibold text-slate-700 block">Width</label>
          <input v-model="selectedWidget.width" placeholder="100% or 50%" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
        </div>

      </div>

      <!-- TAB 2: STYLE & SPACING -->
      <div v-show="inspectorSubTab === 'style'" class="space-y-3 text-xs">
        
        <!-- Alignment -->
        <div v-if="selectedWidget.textAlign !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600 font-medium">Alignment</label>
          <div class="flex items-center space-x-1 bg-slate-100 p-0.5 rounded-lg border">
            <button type="button" @click="selectedWidget.textAlign = 'left'" :class="selectedWidget.textAlign === 'left' ? 'bg-white shadow-2xs text-indigo-600 font-bold' : 'text-slate-600'" class="px-2 py-1 rounded">L</button>
            <button type="button" @click="selectedWidget.textAlign = 'center'" :class="selectedWidget.textAlign === 'center' ? 'bg-white shadow-2xs text-indigo-600 font-bold' : 'text-slate-600'" class="px-2 py-1 rounded">C</button>
            <button type="button" @click="selectedWidget.textAlign = 'right'" :class="selectedWidget.textAlign === 'right' ? 'bg-white shadow-2xs text-indigo-600 font-bold' : 'text-slate-600'" class="px-2 py-1 rounded">R</button>
          </div>
        </div>

        <!-- Common Color Pickers -->
        <div v-if="selectedWidget.color !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Color</label>
          <div class="flex items-center space-x-1.5">
            <input v-model="selectedWidget.color" type="color" class="w-6 h-6 rounded border cursor-pointer" />
            <input v-model="selectedWidget.color" type="text" class="w-20 px-1.5 py-0.5 border rounded font-mono" />
          </div>
        </div>

        <div v-if="selectedWidget.backgroundColor !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Background</label>
          <div class="flex items-center space-x-1.5">
            <input v-model="selectedWidget.backgroundColor" type="color" class="w-6 h-6 rounded border cursor-pointer" />
            <input v-model="selectedWidget.backgroundColor" type="text" class="w-20 px-1.5 py-0.5 border rounded font-mono" />
          </div>
        </div>

        <div v-if="selectedWidget.accentColor !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Accent Color</label>
          <div class="flex items-center space-x-1.5">
            <input v-model="selectedWidget.accentColor" type="color" class="w-6 h-6 rounded border cursor-pointer" />
            <input v-model="selectedWidget.accentColor" type="text" class="w-20 px-1.5 py-0.5 border rounded font-mono" />
          </div>
        </div>

        <div v-if="selectedWidget.numberColor !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Number Color</label>
          <input v-model="selectedWidget.numberColor" type="color" class="w-6 h-6 rounded border cursor-pointer" />
        </div>

        <div v-if="selectedWidget.barColor !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Bar Color</label>
          <input v-model="selectedWidget.barColor" type="color" class="w-6 h-6 rounded border cursor-pointer" />
        </div>

        <div v-if="selectedWidget.fontSize !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Font Size</label>
          <input v-model="selectedWidget.fontSize" type="text" placeholder="24px" class="w-24 px-2 py-1 border rounded font-mono" />
        </div>

        <div v-if="selectedWidget.borderRadius !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Border Radius</label>
          <input v-model="selectedWidget.borderRadius" type="text" placeholder="12px" class="w-24 px-2 py-1 border rounded font-mono" />
        </div>

        <div v-if="selectedWidget.marginBottom !== undefined" class="flex items-center justify-between">
          <label class="text-slate-600">Margin Bottom</label>
          <input v-model="selectedWidget.marginBottom" type="text" placeholder="16px" class="w-24 px-2 py-1 border rounded font-mono" />
        </div>
      </div>

      <div class="pt-3 border-t border-slate-200">
        <button
          type="button"
          @click="$emit('delete-widget')"
          class="w-full py-1.5 text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-lg font-medium transition"
        >
          Delete Widget
        </button>
      </div>
    </div>

    <!-- COLUMN INSPECTOR -->
    <div v-else-if="selectedColumn && !selectedSection" class="space-y-4 text-xs">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="font-bold uppercase tracking-wider text-indigo-700">Edit Column</span>
        <button type="button" @click="$emit('deselect')" class="text-slate-400 hover:text-slate-600">&times;</button>
      </div>

      <div class="space-y-1">
        <label class="font-semibold text-slate-700 block">Width (%)</label>
        <div class="flex gap-1 flex-wrap mb-1">
          <button v-for="w in ['25%', '33.33%', '50%', '66.66%', '100%']" :key="w" type="button" @click="selectedColumn.width = w" class="px-2 py-0.5 bg-slate-100 hover:bg-indigo-50 border rounded text-[10px]">
            {{ w }}
          </button>
        </div>
        <input v-model="selectedColumn.width" type="text" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50 font-mono" />
      </div>

      <div class="flex items-center justify-between">
        <label class="text-slate-600">Background</label>
        <input v-model="selectedColumn.backgroundColor" type="color" class="w-6 h-6 rounded border cursor-pointer" />
      </div>

      <div class="flex items-center justify-between">
        <label class="text-slate-600">Padding</label>
        <input v-model="selectedColumn.padding" placeholder="12px" class="w-24 px-2 py-1 border rounded font-mono" />
      </div>

      <div class="flex items-center justify-between">
        <label class="text-slate-600">Border Radius</label>
        <input v-model="selectedColumn.borderRadius" placeholder="8px" class="w-24 px-2 py-1 border rounded font-mono" />
      </div>
    </div>

    <!-- SECTION INSPECTOR -->
    <div v-else-if="selectedSection" class="space-y-4 text-xs">
      <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <span class="font-bold uppercase tracking-wider text-blue-700">Edit Section</span>
        <div class="flex items-center space-x-2">
          <button type="button" @click="$emit('duplicate-section')" class="text-[11px] text-indigo-600 hover:underline font-medium">
            Duplicate
          </button>
          <button type="button" @click="$emit('deselect')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-semibold text-slate-700 block">Section Label</label>
        <input v-model="selectedSection.name" type="text" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg" />
      </div>

      <div class="space-y-1">
        <label class="font-semibold text-slate-700 block">Container Width</label>
        <select v-model="selectedSection.containerWidth" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
          <option value="boxed">Boxed (max-w-6xl)</option>
          <option value="full">Full Width (100%)</option>
        </select>
      </div>

      <div class="flex items-center justify-between">
        <label class="text-slate-600">Background Color</label>
        <div class="flex items-center space-x-1.5">
          <input v-model="selectedSection.backgroundColor" type="color" class="w-6 h-6 rounded border cursor-pointer" />
          <input v-model="selectedSection.backgroundColor" type="text" class="w-20 px-1.5 py-0.5 border rounded font-mono" />
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-semibold text-slate-700 block">CSS Gradient (Optional)</label>
        <input v-model="selectedSection.backgroundGradient" type="text" placeholder="linear-gradient(135deg, #4f46e5, #0f172a)" class="w-full px-2 py-1 bg-slate-50 border rounded font-mono text-[11px]" />
      </div>

      <div class="flex items-center justify-between">
        <label class="text-slate-600">Default Text Color</label>
        <div class="flex items-center space-x-1.5">
          <input v-model="selectedSection.textColor" type="color" class="w-6 h-6 rounded border cursor-pointer" />
          <input v-model="selectedSection.textColor" type="text" class="w-20 px-1.5 py-0.5 border rounded font-mono" />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="text-slate-600 block">Padding Top</label>
          <input v-model="selectedSection.paddingTop" type="text" class="w-full px-2 py-1 border rounded mt-1 font-mono" />
        </div>
        <div>
          <label class="text-slate-600 block">Padding Bottom</label>
          <input v-model="selectedSection.paddingBottom" type="text" class="w-full px-2 py-1 border rounded mt-1 font-mono" />
        </div>
      </div>

      <div class="pt-3 border-t border-slate-200">
        <button
          type="button"
          @click="$emit('delete-section')"
          class="w-full py-1.5 text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-lg font-medium transition"
        >
          Delete Section
        </button>
      </div>
    </div>

    <!-- EMPTY STATE -->
    <div v-else class="py-16 text-center text-slate-400 text-xs">
      Click any section, column, or widget on the canvas to inspect and edit its live properties.
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  selectedWidget: Object,
  selectedColumn: Object,
  selectedSection: Object,
});

defineEmits([
  'deselect',
  'delete-widget',
  'duplicate-widget',
  'delete-section',
  'duplicate-section',
  'open-media',
]);

const inspectorSubTab = ref('content');
</script>

