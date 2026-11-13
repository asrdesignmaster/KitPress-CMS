<template>
  <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
    
    <!-- Header -->
    <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <span class="w-3 h-3 rounded-full" :class="overallScoreClass"></span>
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
          Advanced SEO Suite
        </h3>
        <span class="px-2 py-0.5 text-[10px] font-semibold bg-indigo-100 text-indigo-700 rounded-full">
          Yoast Pro Engine
        </span>
      </div>

      <div class="flex items-center space-x-1 text-xs">
        <button 
          type="button"
          @click="activeSeoTab = 'general'"
          :class="activeSeoTab === 'general' ? 'bg-white shadow-2xs font-bold text-indigo-600' : 'text-slate-500 hover:text-slate-800'"
          class="px-2.5 py-1 rounded transition"
        >
          Snippet & Analysis
        </button>
        <button 
          type="button"
          @click="activeSeoTab = 'social'"
          :class="activeSeoTab === 'social' ? 'bg-white shadow-2xs font-bold text-indigo-600' : 'text-slate-500 hover:text-slate-800'"
          class="px-2.5 py-1 rounded transition"
        >
          Social (OG/Twitter)
        </button>
        <button 
          type="button"
          @click="activeSeoTab = 'robots'"
          :class="activeSeoTab === 'robots' ? 'bg-white shadow-2xs font-bold text-indigo-600' : 'text-slate-500 hover:text-slate-800'"
          class="px-2.5 py-1 rounded transition"
        >
          Robots & Index
        </button>
      </div>
    </div>

    <!-- Body -->
    <div class="p-5 space-y-4">
      
      <!-- Tab 1: Snippet & Analysis -->
      <div v-show="activeSeoTab === 'general'" class="space-y-5">
        
        <!-- Focus Keywords Input (Primary + Secondary) -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="text-xs font-bold text-slate-700">Focus Keywords</label>
            <span class="text-[11px] text-slate-400">Press Enter or comma to add multiple (up to 5)</span>
          </div>

          <!-- Keyword Pills -->
          <div v-if="keywordAnalyses.length > 0" class="flex flex-wrap gap-1.5">
            <button
              v-for="(item, idx) in keywordAnalyses"
              :key="item.keyword"
              type="button"
              @click="activeKeywordIndex = idx"
              :class="activeKeywordIndex === idx ? 'bg-indigo-600 text-white border-indigo-600 shadow-2xs' : 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200'"
              class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full border text-[11px] font-medium transition"
            >
              <span v-if="idx === 0" class="text-[9px] uppercase tracking-wider opacity-80 font-bold">Primary:</span>
              <span>{{ item.keyword }}</span>
              <span
                :class="activeKeywordIndex === idx ? 'bg-white/20 text-white' : (item.passed >= 5 ? 'bg-emerald-100 text-emerald-700' : item.passed >= 3 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')"
                class="px-1.5 py-0.2 rounded-full text-[10px] font-bold"
              >
                {{ item.passed }}/{{ item.total }}
              </span>
              <span
                @click.stop="removeKeyword(idx)"
                class="ml-0.5 hover:text-rose-300 font-bold cursor-pointer"
                title="Remove keyword"
              >&times;</span>
            </button>
          </div>

          <div class="flex items-center space-x-2">
            <input 
              v-model="newKeywordInput"
              @keydown.enter.prevent="addKeyword"
              @keydown.,.prevent="addKeyword"
              @blur="addKeyword"
              type="text" 
              :disabled="focusKeywords.length >= 5"
              placeholder="e.g. Modern CMS, Laravel Architecture"
              class="flex-1 px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:bg-white transition disabled:opacity-50"
            />
            <button
              type="button"
              @click="addKeyword"
              :disabled="!newKeywordInput.trim() || focusKeywords.length >= 5"
              class="px-3 py-2 text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg transition disabled:opacity-40"
            >
              + Add
            </button>
          </div>
        </div>

        <!-- Google SERP Live Preview Box -->
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
              Google Search Result Snippet Preview
            </span>
            <span class="text-[10px] text-slate-400">Desktop View</span>
          </div>

          <div class="p-3.5 bg-white rounded-lg border border-slate-200 font-sans shadow-2xs space-y-1">
            <div class="flex items-center space-x-1.5 text-xs text-slate-700 truncate">
              <span class="text-slate-900 font-medium">yourdomain.com</span>
              <span class="text-slate-400">&rsaquo;</span>
              <span class="text-slate-500">{{ slug || 'sample-post' }}</span>
            </div>
            <h4 class="text-base text-blue-800 hover:underline cursor-pointer font-medium leading-snug line-clamp-1">
              {{ form.seo_title || title || 'Post Title - Modern High-Performance CMS' }}
            </h4>
            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
              {{ form.seo_description || 'Customize your meta description snippet to give search engines a concise, high-impact summary of this page content.' }}
            </p>
          </div>
        </div>

        <!-- SEO Title with Character Progress Bar -->
        <div class="space-y-1">
          <div class="flex items-center justify-between text-xs">
            <label class="font-bold text-slate-700">SEO Title</label>
            <span :class="titleLengthColor" class="font-mono text-[11px]">
              {{ titleLength }} / 60 characters
            </span>
          </div>
          <input 
            v-model="form.seo_title" 
            type="text" 
            :placeholder="title || 'Enter custom SEO Title...'"
            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
          />
          <!-- Progress Bar -->
          <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
            <div 
              :style="{ width: `${Math.min(titleLength / 60 * 100, 100)}%` }"
              :class="titleBarColor"
              class="h-full transition-all duration-300"
            ></div>
          </div>
        </div>

        <!-- Meta Description with Character Progress Bar -->
        <div class="space-y-1">
          <div class="flex items-center justify-between text-xs">
            <label class="font-bold text-slate-700">Meta Description</label>
            <span :class="descLengthColor" class="font-mono text-[11px]">
              {{ descLength }} / 160 characters
            </span>
          </div>
          <textarea 
            v-model="form.seo_description" 
            rows="3" 
            placeholder="Concise, captivating meta description for Google SERP..."
            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
          ></textarea>
          <!-- Progress Bar -->
          <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
            <div 
              :style="{ width: `${Math.min(descLength / 160 * 100, 100)}%` }"
              :class="descBarColor"
              class="h-full transition-all duration-300"
            ></div>
          </div>
        </div>

        <!-- Real-Time SEO Content Analysis Checklist -->
        <div class="pt-4 border-t border-slate-200 space-y-3">
          <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center justify-between">
            <span>Content Analysis Checklist</span>
            <span class="text-[11px] font-normal text-slate-500">
              Score: <strong :class="overallScoreTextClass">{{ passedChecksCount }}/{{ analysisChecks.length }} Passed</strong>
            </span>
          </h4>

          <div class="space-y-2">
            <div 
              v-for="(check, idx) in analysisChecks" 
              :key="idx"
              class="flex items-start space-x-2.5 text-xs"
            >
              <span 
                :class="check.passed ? 'bg-emerald-500 text-white' : check.warning ? 'bg-amber-400 text-slate-900' : 'bg-rose-500 text-white'"
                class="w-4 h-4 rounded-full flex items-center justify-center shrink-0 text-[10px] font-bold mt-0.5"
              >
                {{ check.passed ? '✓' : check.warning ? '!' : '✕' }}
              </span>
              <div>
                <p :class="check.passed ? 'text-slate-700' : 'text-slate-800 font-medium'">
                  {{ check.label }}
                </p>
                <p class="text-[11px] text-slate-400">
                  {{ check.details }}
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Tab 2: Social Meta (OpenGraph & Twitter) -->
      <div v-show="activeSeoTab === 'social'" class="space-y-4">
        <p class="text-xs text-slate-500">
          Control how this content displays when shared across Twitter/X, Facebook, LinkedIn, and WhatsApp.
        </p>

        <div class="space-y-1">
          <label class="text-xs font-bold text-slate-700">Social Share Title (OG Title)</label>
          <input 
            v-model="form.seo_og_title" 
            type="text" 
            :placeholder="form.seo_title || title || 'Custom social share headline'"
            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg"
          />
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold text-slate-700">Social Share Description</label>
          <textarea 
            v-model="form.seo_og_description" 
            rows="2" 
            :placeholder="form.seo_description || 'Custom social share description'"
            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg"
          ></textarea>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold text-slate-700">Social Share Image (OG Image)</label>
          <div class="flex items-center space-x-2">
            <input 
              v-model="form.seo_og_image" 
              type="text" 
              placeholder="https://... or choose from Media"
              class="flex-1 px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg"
            />
            <button 
              type="button" 
              @click="$emit('open-media', 'og_image')"
              class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg border border-indigo-200 transition"
            >
              Browse Media
            </button>
          </div>
        </div>
      </div>

      <!-- Tab 3: Robots & Sitemap Indexing -->
      <div v-show="activeSeoTab === 'robots'" class="space-y-4">
        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-bold text-slate-800">Robots Indexing</p>
              <p class="text-[11px] text-slate-500">Allow search engine crawlers to index this page in search results.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input v-model="form.seo_robots_index" type="checkbox" class="sr-only peer" />
              <div class="w-9 h-5 bg-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
            </label>
          </div>

          <div class="flex items-center justify-between pt-3 border-t border-slate-200">
            <div>
              <p class="text-xs font-bold text-slate-800">Robots Follow</p>
              <p class="text-[11px] text-slate-500">Instruct search engine spiders to follow links found on this page.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input v-model="form.seo_robots_follow" type="checkbox" class="sr-only peer" />
              <div class="w-9 h-5 bg-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
            </label>
          </div>
        </div>

        <p class="text-[11px] text-slate-400">
          This post is automatically submitted to <code>/sitemap.xml</code> when indexing is enabled.
        </p>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, computed, watchEffect } from 'vue';

const props = defineProps({
  form: Object,
  title: String,
  slug: String,
  rawContent: String,
});

const emit = defineEmits(['open-media', 'score-change']);

const activeSeoTab = ref('general');
const newKeywordInput = ref('');
const activeKeywordIndex = ref(0);

const focusKeywords = computed(() => {
  const raw = String(props.form.seo_focus_keyword || '');
  const seen = new Set();
  return raw
    .split(',')
    .map((k) => k.trim())
    .filter((k) => {
      if (!k) return false;
      const lower = k.toLowerCase();
      if (seen.has(lower)) return false;
      seen.add(lower);
      return true;
    })
    .slice(0, 5);
});

const syncKeywords = (list) => {
  props.form.seo_focus_keyword = list.join(', ').slice(0, 255);
  if (activeKeywordIndex.value >= list.length) {
    activeKeywordIndex.value = Math.max(0, list.length - 1);
  }
};

const addKeyword = () => {
  const parts = newKeywordInput.value
    .split(',')
    .map((k) => k.trim())
    .filter(Boolean);
  if (parts.length === 0) return;

  const current = [...focusKeywords.value];
  const existingLower = new Set(current.map((k) => k.toLowerCase()));
  for (const part of parts) {
    if (current.length >= 5) break;
    if (!existingLower.has(part.toLowerCase())) {
      current.push(part);
      existingLower.add(part.toLowerCase());
    }
  }
  syncKeywords(current);
  newKeywordInput.value = '';
  activeKeywordIndex.value = current.length - 1;
};

const removeKeyword = (idx) => {
  const current = [...focusKeywords.value];
  current.splice(idx, 1);
  syncKeywords(current);
};

const titleLength = computed(() => (props.form.seo_title || props.title || '').length);
const descLength = computed(() => (props.form.seo_description || '').length);

const titleLengthColor = computed(() => {
  if (titleLength.value >= 40 && titleLength.value <= 60) return 'text-emerald-600 font-bold';
  if (titleLength.value > 60) return 'text-rose-600 font-bold';
  return 'text-amber-500';
});

const titleBarColor = computed(() => {
  if (titleLength.value >= 40 && titleLength.value <= 60) return 'bg-emerald-500';
  if (titleLength.value > 60) return 'bg-rose-500';
  return 'bg-amber-400';
});

const descLengthColor = computed(() => {
  if (descLength.value >= 120 && descLength.value <= 160) return 'text-emerald-600 font-bold';
  if (descLength.value > 160) return 'text-rose-600 font-bold';
  return 'text-amber-500';
});

const descBarColor = computed(() => {
  if (descLength.value >= 120 && descLength.value <= 160) return 'bg-emerald-500';
  if (descLength.value > 160) return 'bg-rose-500';
  return 'bg-amber-400';
});

// Real-Time SEO Analysis Checklist Computations (supports multiple focus keywords)
const evaluateKeyword = (rawKw) => {
  const kw = rawKw.trim().toLowerCase();
  const currentTitle = (props.form.seo_title || props.title || '').toLowerCase();
  const currentSlug = (props.slug || '').toLowerCase();
  const currentDesc = (props.form.seo_description || '').toLowerCase();
  const contentText = (props.rawContent || '').replace(/<[^>]*>?/gm, ' ').toLowerCase();

  const words = contentText.split(/\s+/).filter(Boolean);
  const wordCount = words.length;

  const inTitle = currentTitle.includes(kw);
  const slugCleanKw = kw.replace(/[^a-z0-9]+/g, '-');
  const inSlug = currentSlug.includes(slugCleanKw) || currentSlug.includes(kw.replace(/\s+/g, '-'));
  const inDesc = currentDesc.includes(kw);
  const introWords = words.slice(0, 100).join(' ');
  const inIntro = introWords.includes(kw);

  let kwOccurrences = 0;
  if (words.length > 0) {
    const kwRegex = new RegExp('\\b' + kw.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&') + '\\b', 'gi');
    kwOccurrences = (contentText.match(kwRegex) || []).length;
  }
  const density = words.length > 0 ? ((kwOccurrences / words.length) * 100).toFixed(1) : 0;
  const goodDensity = density >= 0.8 && density <= 3.0;

  return [
    {
      label: `Focus Keyword in SEO Title ("${rawKw}")`,
      passed: inTitle,
      details: inTitle ? 'Keyword appears in the title tag.' : 'Keyword was not detected in SEO title.',
    },
    {
      label: 'Focus Keyword in Page URL Slug',
      passed: inSlug,
      details: inSlug ? 'Slug contains focus keyword.' : 'Add your focus keyword to the slug for higher ranking authority.',
    },
    {
      label: 'Focus Keyword in Meta Description',
      passed: inDesc,
      details: inDesc ? 'Meta description contains the keyword.' : 'Search engines highlight matching keywords in meta descriptions.',
    },
    {
      label: 'Keyword in Introductory Paragraph',
      passed: inIntro,
      details: inIntro ? 'Keyword appears early in the first 100 words.' : 'Include your keyword in the introductory paragraph.',
    },
    {
      label: `Keyword Density (${density}%)`,
      passed: goodDensity,
      warning: kwOccurrences > 0 && !goodDensity,
      details: `${kwOccurrences} occurrences found in content. Optimal density is 1% to 2.5%.`,
    },
    {
      label: `Total Content Length (${wordCount} words)`,
      passed: wordCount >= 300,
      warning: wordCount >= 100,
      details: wordCount >= 300 ? 'Good length for indexation and authority.' : 'Content length is below 300 words.',
    },
  ];
};

const keywordAnalyses = computed(() => {
  return focusKeywords.value.map((kw) => {
    const checks = evaluateKeyword(kw);
    return {
      keyword: kw,
      checks,
      passed: checks.filter((c) => c.passed).length,
      total: checks.length,
    };
  });
});

const analysisChecks = computed(() => {
  if (keywordAnalyses.value.length === 0) {
    const contentText = (props.rawContent || '').replace(/<[^>]*>?/gm, ' ').toLowerCase();
    const wordCount = contentText.split(/\s+/).filter(Boolean).length;
    return [
      { label: 'Focus Keyword Defined', passed: false, warning: true, details: 'Add one or more focus keywords above to run real-time SEO content evaluations.' },
      { label: 'Content Word Count', passed: wordCount >= 300, warning: wordCount >= 100, details: `${wordCount} words detected. (300+ recommended)` },
    ];
  }

  const idx = Math.min(activeKeywordIndex.value, keywordAnalyses.value.length - 1);
  return keywordAnalyses.value[idx].checks;
});

const primaryChecks = computed(() => {
  if (keywordAnalyses.value.length > 0) {
    return keywordAnalyses.value[0].checks;
  }
  return analysisChecks.value;
});

const passedChecksCount = computed(() => primaryChecks.value.filter(c => c.passed).length);

const overallScoreClass = computed(() => {
  const count = passedChecksCount.value;
  if (count >= 5) return 'bg-emerald-500';
  if (count >= 3) return 'bg-amber-400';
  return 'bg-rose-500';
});

const overallScoreTextClass = computed(() => {
  const count = passedChecksCount.value;
  if (count >= 5) return 'text-emerald-600';
  if (count >= 3) return 'text-amber-600';
  return 'text-rose-600';
});

watchEffect(() => {
  emit('score-change', {
    passed: passedChecksCount.value,
    total: primaryChecks.value.length,
    dotClass: overallScoreClass.value,
    textClass: overallScoreTextClass.value,
  });
});
</script>

