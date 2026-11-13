<template>
  <div
    :id="widget.customId || undefined"
    :class="widget.customClass || ''"
    :style="{ marginBottom: widget.marginBottom || '0px' }"
  >
    
    <!-- 1. Heading -->
    <component
      v-if="widget.type === 'heading'"
      :is="widget.tag || 'h2'"
      :style="{
        color: widget.color || 'inherit',
        fontSize: widget.fontSize || '28px',
        fontWeight: widget.fontWeight || '700',
        textAlign: widget.textAlign || 'left',
      }"
      class="tracking-tight leading-tight"
    >
      {{ widget.content || 'Heading Text' }}
    </component>

    <!-- 2. Text / Paragraph -->
    <p
      v-else-if="widget.type === 'text'"
      :style="{
        color: widget.color || 'inherit',
        fontSize: widget.fontSize || '16px',
        textAlign: widget.textAlign || 'left',
        lineHeight: widget.lineHeight || '1.7',
      }"
      class="whitespace-pre-line"
    >
      {{ widget.content || 'Paragraph content...' }}
    </p>

    <!-- 3. Image -->
    <div
      v-else-if="widget.type === 'image'"
      :style="{ textAlign: widget.textAlign || 'center' }"
    >
      <img
        :src="widget.url || 'https://via.placeholder.com/800x400?text=WebP+Image'"
        :alt="widget.alt || 'Visual Builder Image'"
        :style="{
          width: widget.width || '100%',
          borderRadius: widget.borderRadius || '8px',
        }"
        :class="shadowClass(widget.shadow)"
        class="inline-block max-w-full h-auto object-cover"
        loading="lazy"
      />
    </div>

    <!-- 4. Button -->
    <div
      v-else-if="widget.type === 'button'"
      :style="{ textAlign: widget.textAlign || 'left' }"
    >
      <a
        :href="isEditor ? 'javascript:void(0)' : (widget.url || '#')"
        @click="isEditor && $event.preventDefault()"
        :style="{
          backgroundColor: widget.backgroundColor || '#4f46e5',
          color: widget.textColor || '#ffffff',
          borderRadius: widget.borderRadius || '8px',
        }"
        :class="buttonSizeClass(widget.size)"
        class="inline-flex items-center space-x-2 font-semibold shadow-xs hover:opacity-90 transition"
      >
        <span>{{ widget.content || 'Click Here' }}</span>
        <span v-if="widget.showArrow">&rarr;</span>
      </a>
    </div>

    <!-- 5. Spacer -->
    <div
      v-else-if="widget.type === 'spacer'"
      :style="{ height: widget.height || '32px' }"
      class="w-full"
    ></div>

    <!-- 6. Divider -->
    <div
      v-else-if="widget.type === 'divider'"
      :style="{ marginTop: widget.marginTop || '12px', marginBottom: widget.marginBottom || '12px' }"
      class="flex justify-center"
    >
      <hr
        :style="{
          width: widget.width || '100%',
          borderTopStyle: widget.style || 'solid',
          borderTopWidth: widget.thickness || '1px',
          borderTopColor: widget.color || '#cbd5e1',
        }"
        class="border-0"
      />
    </div>

    <!-- 7. Icon Box (Elementor Pro) -->
    <div
      v-else-if="widget.type === 'icon_box'"
      :style="{
        backgroundColor: widget.backgroundColor || '#ffffff',
        borderRadius: widget.borderRadius || '14px',
        padding: widget.padding || '24px',
        textAlign: widget.textAlign || 'left',
      }"
      class="border border-slate-200/80 shadow-2xs hover:shadow-md transition space-y-3"
    >
      <div
        :style="{
          backgroundColor: widget.iconBg || '#eef2ff',
          color: widget.iconColor || '#4f46e5',
        }"
        :class="widget.textAlign === 'center' ? 'mx-auto' : ''"
        class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-2xs"
      >
        {{ widget.icon || '⚡' }}
      </div>
      <h4
        :style="{ color: widget.titleColor || '#0f172a' }"
        class="text-base font-bold tracking-tight"
      >
        {{ widget.title }}
      </h4>
      <p
        :style="{ color: widget.textColor || '#64748b' }"
        class="text-xs leading-relaxed"
      >
        {{ widget.description }}
      </p>
    </div>

    <!-- 8. Image Box -->
    <div
      v-else-if="widget.type === 'image_box'"
      :style="{
        backgroundColor: widget.backgroundColor || '#ffffff',
        borderRadius: widget.borderRadius || '14px',
      }"
      class="border border-slate-200/80 shadow-2xs overflow-hidden hover:shadow-md transition"
    >
      <div class="aspect-video bg-slate-100 overflow-hidden">
        <img :src="widget.url" :alt="widget.title" class="w-full h-full object-cover" loading="lazy" />
      </div>
      <div class="p-5 space-y-2">
        <h4 class="text-base font-bold text-slate-900">{{ widget.title }}</h4>
        <p class="text-xs text-slate-600 leading-relaxed">{{ widget.description }}</p>
        <a
          v-if="widget.buttonText"
          :href="isEditor ? 'javascript:void(0)' : (widget.buttonUrl || '#')"
          @click="isEditor && $event.preventDefault()"
          class="inline-block pt-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800"
        >
          {{ widget.buttonText }} &rarr;
        </a>
      </div>
    </div>

    <!-- 9. Counter -->
    <div
      v-else-if="widget.type === 'counter'"
      :style="{ textAlign: widget.textAlign || 'center' }"
      class="p-4"
    >
      <div
        :style="{
          color: widget.numberColor || '#4f46e5',
          fontSize: widget.fontSize || '42px',
        }"
        class="font-extrabold tracking-tight leading-none"
      >
        <span>{{ widget.prefix }}</span><span>{{ widget.number }}</span><span>{{ widget.suffix }}</span>
      </div>
      <p
        :style="{ color: widget.titleColor || '#475569' }"
        class="text-xs font-semibold uppercase tracking-wider mt-2"
      >
        {{ widget.title }}
      </p>
    </div>

    <!-- 10. Progress Bar -->
    <div v-else-if="widget.type === 'progress_bar'" class="space-y-1.5">
      <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
        <span>{{ widget.title }}</span>
        <span v-if="widget.showPercentage" class="font-mono">{{ widget.percentage }}%</span>
      </div>
      <div
        :style="{ backgroundColor: widget.trackColor || '#e2e8f0' }"
        class="w-full h-3 rounded-full overflow-hidden"
      >
        <div
          :style="{
            width: `${Math.min(Math.max(Number(widget.percentage) || 0, 0), 100)}%`,
            backgroundColor: widget.barColor || '#4f46e5',
          }"
          class="h-full rounded-full transition-all duration-500"
        ></div>
      </div>
    </div>

    <!-- 11. Testimonial -->
    <div
      v-else-if="widget.type === 'testimonial'"
      :style="{
        backgroundColor: widget.backgroundColor || '#ffffff',
        color: widget.textColor || '#334155',
        borderRadius: widget.borderRadius || '16px',
      }"
      class="p-6 border border-slate-200/90 shadow-xs space-y-4"
    >
      <div class="flex items-center space-x-0.5 text-amber-400 text-sm">
        <span v-for="n in (Number(widget.rating) || 5)" :key="n">★</span>
      </div>
      <p class="text-sm italic leading-relaxed">
        "{{ widget.quote }}"
      </p>
      <div class="flex items-center space-x-3 pt-2 border-t border-slate-100">
        <img
          v-if="widget.avatarUrl"
          :src="widget.avatarUrl"
          :alt="widget.authorName"
          class="w-10 h-10 rounded-full object-cover border border-slate-200"
          loading="lazy"
        />
        <div>
          <p class="text-xs font-bold text-slate-900">{{ widget.authorName }}</p>
          <p class="text-[11px] text-slate-500">{{ widget.authorRole }}</p>
        </div>
      </div>
    </div>

    <!-- 12. Pricing Table (Elementor Pro) -->
    <div
      v-else-if="widget.type === 'pricing_table'"
      :style="{
        backgroundColor: widget.backgroundColor || '#ffffff',
        borderColor: widget.highlighted ? (widget.accentColor || '#4f46e5') : '#e2e8f0',
      }"
      :class="widget.highlighted ? 'ring-2 ring-offset-1 shadow-lg' : 'shadow-xs'"
      class="rounded-2xl border-2 p-6 flex flex-col justify-between space-y-5 relative overflow-hidden"
    >
      <span
        v-if="widget.badge"
        :style="{ backgroundColor: widget.accentColor || '#4f46e5' }"
        class="absolute top-3 right-3 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white rounded-full"
      >
        {{ widget.badge }}
      </span>

      <div class="space-y-2">
        <h4 class="text-lg font-bold text-slate-900">{{ widget.title }}</h4>
        <p class="text-xs text-slate-500">{{ widget.subtitle }}</p>
        <div class="pt-2 flex items-baseline space-x-1">
          <span class="text-lg font-bold text-slate-500">{{ widget.currency || '$' }}</span>
          <span class="text-4xl font-extrabold text-slate-900">{{ widget.price }}</span>
          <span class="text-xs text-slate-500">{{ widget.period }}</span>
        </div>
      </div>

      <ul class="space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-4">
        <li v-for="(feat, i) in (widget.features || [])" :key="i" class="flex items-start space-x-2">
          <span :style="{ color: widget.accentColor || '#4f46e5' }" class="font-bold">✓</span>
          <span>{{ feat }}</span>
        </li>
      </ul>

      <a
        :href="isEditor ? 'javascript:void(0)' : (widget.buttonUrl || '#')"
        @click="isEditor && $event.preventDefault()"
        :style="{ backgroundColor: widget.accentColor || '#4f46e5' }"
        class="block w-full py-2.5 text-center text-xs font-bold text-white rounded-lg shadow-xs hover:opacity-90 transition"
      >
        {{ widget.buttonText || 'Select Plan' }}
      </a>
    </div>

    <!-- 13. Alert / Notice -->
    <div
      v-else-if="widget.type === 'alert' && !alertDismissed"
      :class="alertVariantClasses(widget.variant)"
      class="p-4 rounded-xl border flex items-start justify-between gap-3 text-xs"
    >
      <div class="space-y-0.5">
        <p class="font-bold">{{ widget.title }}</p>
        <p class="opacity-90 leading-relaxed">{{ widget.description }}</p>
      </div>
      <button
        v-if="widget.dismissible"
        type="button"
        @click.stop="alertDismissed = true"
        class="text-base leading-none opacity-60 hover:opacity-100"
      >
        &times;
      </button>
    </div>

    <!-- 14. Accordion / FAQ -->
    <div v-else-if="widget.type === 'accordion'" class="divide-y divide-slate-200 border border-slate-200 rounded-xl bg-white overflow-hidden">
      <div v-for="(item, idx) in (widget.items || [])" :key="idx">
        <button
          type="button"
          @click.stop="activeAccordion = activeAccordion === idx ? -1 : idx"
          class="w-full px-4 py-3 text-left flex items-center justify-between text-xs font-bold text-slate-800 hover:bg-slate-50 transition"
        >
          <span>{{ item.title }}</span>
          <span :style="{ color: widget.accentColor || '#4f46e5' }" class="text-sm font-mono">
            {{ activeAccordion === idx ? '−' : '+' }}
          </span>
        </button>
        <div v-show="activeAccordion === idx" class="px-4 pb-3.5 text-xs text-slate-600 leading-relaxed bg-slate-50/50">
          {{ item.content }}
        </div>
      </div>
    </div>

    <!-- 15. Tabs -->
    <div v-else-if="widget.type === 'tabs'" class="border border-slate-200 rounded-xl bg-white overflow-hidden">
      <div class="flex flex-wrap border-b border-slate-200 bg-slate-50">
        <button
          v-for="(tab, idx) in (widget.items || [])"
          :key="idx"
          type="button"
          @click.stop="activeTab = idx"
          :style="activeTab === idx ? { borderBottomColor: widget.accentColor || '#4f46e5', color: widget.accentColor || '#4f46e5' } : {}"
          :class="activeTab === idx ? 'bg-white border-b-2 font-bold' : 'text-slate-600 hover:text-slate-900'"
          class="px-4 py-2.5 text-xs transition"
        >
          {{ tab.title }}
        </button>
      </div>
      <div class="p-4 text-xs text-slate-600 leading-relaxed">
        {{ widget.items?.[activeTab]?.content || '' }}
      </div>
    </div>

    <!-- 16. Interactive Carousel Slider -->
    <div
      v-else-if="widget.type === 'slider'"
      :style="{ height: widget.height || '320px', borderRadius: widget.borderRadius || '14px' }"
      class="relative overflow-hidden bg-slate-900 group/slider shadow-md"
    >
      <template v-if="widget.slides && widget.slides.length">
        <img
          :src="widget.slides[activeSlide]?.image"
          :alt="widget.slides[activeSlide]?.title"
          class="w-full h-full object-cover opacity-70 transition duration-500"
        />
        <div class="absolute inset-0 bg-linear-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-6 sm:p-8 text-white">
          <h3 class="text-xl sm:text-2xl font-bold">{{ widget.slides[activeSlide]?.title }}</h3>
          <p class="text-xs sm:text-sm text-slate-200 mt-1 max-w-xl">{{ widget.slides[activeSlide]?.subtitle }}</p>
          <div v-if="widget.slides[activeSlide]?.buttonText" class="mt-3">
            <a
              :href="isEditor ? 'javascript:void(0)' : (widget.slides[activeSlide]?.buttonUrl || '#')"
              @click="isEditor && $event.preventDefault()"
              class="inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition"
            >
              {{ widget.slides[activeSlide]?.buttonText }}
            </a>
          </div>
        </div>

        <!-- Prev / Next -->
        <button
          v-if="widget.slides.length > 1"
          type="button"
          @click.stop="prevSlide"
          class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center text-sm transition"
        >
          &lsaquo;
        </button>
        <button
          v-if="widget.slides.length > 1"
          type="button"
          @click.stop="nextSlide"
          class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center text-sm transition"
        >
          &rsaquo;
        </button>

        <!-- Dots -->
        <div v-if="widget.slides.length > 1" class="absolute bottom-3 right-4 flex items-center space-x-1.5">
          <button
            v-for="(_, idx) in widget.slides"
            :key="idx"
            type="button"
            @click.stop="activeSlide = idx"
            :class="activeSlide === idx ? 'bg-white w-4' : 'bg-white/40 w-2'"
            class="h-2 rounded-full transition-all"
          ></button>
        </div>
      </template>
    </div>

    <!-- 17. Video Embed -->
    <div
      v-else-if="widget.type === 'video'"
      :style="{
        aspectRatio: widget.aspectRatio || '16/9',
        borderRadius: widget.borderRadius || '12px',
      }"
      class="w-full overflow-hidden bg-slate-900 shadow-xs"
    >
      <iframe
        v-if="embedVideoUrl"
        :src="embedVideoUrl"
        class="w-full h-full border-0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
      <div v-else class="w-full h-full flex items-center justify-center text-xs text-slate-400">
        Enter a valid YouTube or Vimeo URL
      </div>
    </div>

    <!-- 18. Social Icons -->
    <div
      v-else-if="widget.type === 'social_icons'"
      :class="{
        'justify-start': widget.textAlign === 'left' || !widget.textAlign,
        'justify-center': widget.textAlign === 'center',
        'justify-end': widget.textAlign === 'right',
      }"
      class="flex flex-wrap items-center gap-2"
    >
      <a
        v-for="(item, idx) in (widget.items || [])"
        :key="idx"
        :href="isEditor ? 'javascript:void(0)' : (item.url || '#')"
        @click="isEditor && $event.preventDefault()"
        :target="isEditor ? undefined : '_blank'"
        :style="{ backgroundColor: item.color || '#0f172a' }"
        :class="widget.shape === 'circle' ? 'rounded-full' : widget.shape === 'square' ? 'rounded-none' : 'rounded-lg'"
        class="px-3 py-1.5 text-white text-xs font-semibold hover:opacity-90 transition shadow-2xs"
      >
        {{ item.label || item.platform }}
      </a>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  widget: {
    type: Object,
    required: true,
  },
  isEditor: {
    type: Boolean,
    default: false,
  },
});

const activeAccordion = ref(0);
const activeTab = ref(0);
const activeSlide = ref(0);
const alertDismissed = ref(false);

const nextSlide = () => {
  const len = props.widget.slides?.length || 0;
  if (len > 0) activeSlide.value = (activeSlide.value + 1) % len;
};

const prevSlide = () => {
  const len = props.widget.slides?.length || 0;
  if (len > 0) activeSlide.value = (activeSlide.value - 1 + len) % len;
};

const embedVideoUrl = computed(() => {
  const url = props.widget.url || '';
  if (!url) return '';

  // YouTube watch or short link
  const ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([A-Za-z0-9_-]{6,})/);
  if (ytMatch) {
    return `https://www.youtube.com/embed/${ytMatch[1]}`;
  }

  // Vimeo
  const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
  if (vimeoMatch) {
    return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
  }

  return url;
});

const shadowClass = (shadow) => {
  switch (shadow) {
    case 'none': return '';
    case 'sm': return 'shadow-xs';
    case 'lg': return 'shadow-xl';
    default: return 'shadow-md';
  }
};

const buttonSizeClass = (size) => {
  switch (size) {
    case 'sm': return 'px-3 py-1.5 text-xs';
    case 'lg': return 'px-6 py-3 text-sm';
    default: return 'px-5 py-2.5 text-xs';
  }
};

const alertVariantClasses = (variant) => {
  switch (variant) {
    case 'success': return 'bg-emerald-50 border-emerald-200 text-emerald-900';
    case 'warning': return 'bg-amber-50 border-amber-200 text-amber-900';
    case 'danger': return 'bg-rose-50 border-rose-200 text-rose-900';
    default: return 'bg-indigo-50 border-indigo-200 text-indigo-900';
  }
};
</script>

