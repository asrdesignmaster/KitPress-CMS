<template>
  <svg
    :class="sizeClass"
    viewBox="0 0 40 40"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    aria-label="KitPress Logo"
  >
    <defs>
      <linearGradient id="kitpress-grad" x1="4" y1="4" x2="36" y2="36" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#6366F1" />
        <stop offset="100%" stop-color="#4338CA" />
      </linearGradient>
    </defs>

    <!-- Rounded Modular Badge -->
    <rect x="2" y="2" width="36" height="36" rx="10" fill="url(#kitpress-grad)" />

    <!-- Subtle Modular Toolkit Frame -->
    <rect x="4.5" y="4.5" width="31" height="31" rx="8" stroke="white" stroke-opacity="0.2" stroke-width="1" />

    <!-- Stylized Geometric 'K' formed from modular publishing blocks -->
    <!-- Left Pillar -->
    <rect x="11" y="10" width="4.5" height="20" rx="1.75" fill="white" />
    <!-- Upper Wing -->
    <path d="M17.5 19.2L25.8 10.6C26.4 10 27.4 10 28.1 10.6L29.1 11.6C29.7 12.2 29.7 13.2 29.1 13.9L21.5 21.6L17.5 19.2Z" fill="white" />
    <!-- Lower Wing + Accent Corner Block -->
    <path d="M19.8 19.6L29.1 26.6C29.7 27.1 29.8 28.1 29.2 28.8L28.3 29.7C27.7 30.3 26.7 30.3 26 29.7L17.2 21.8L19.8 19.6Z" fill="#E0E7FF" />
    <!-- Modular Dot / AI Spark -->
    <circle cx="28" cy="19.8" r="2.1" fill="#34D399" />
  </svg>
</template>

<script setup>
defineProps({
  sizeClass: {
    type: String,
    default: 'w-7 h-7',
  },
});
</script>

