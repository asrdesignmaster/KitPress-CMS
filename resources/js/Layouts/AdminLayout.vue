<template>
  <div class="min-h-screen bg-slate-100 flex flex-col antialiased">
    
    <!-- Top WordPress Toolbar -->
    <header class="h-12 bg-slate-900 text-slate-300 flex items-center justify-between px-4 z-40 fixed top-0 left-0 right-0 border-b border-slate-800 shadow-xs">
      <div class="flex items-center space-x-3">
        <!-- Sidebar Collapse / Mobile Menu Toggle -->
        <button
          type="button"
          @click="toggleSidebar"
          class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition focus:outline-hidden"
          :title="isSidebarCollapsed ? 'Expand side menu' : 'Collapse side menu'"
          aria-label="Toggle side menu"
        >
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <!-- Site Title & View Site Link -->
        <Link href="/admin" class="flex items-center space-x-2 text-white font-semibold text-sm hover:text-indigo-400 transition">
          <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
          <span class="tracking-tight">{{ siteName }}</span>
        </Link>

        <a href="/" target="_blank" class="hidden sm:flex items-center space-x-1 text-xs text-slate-400 hover:text-white transition px-2 py-1 rounded hover:bg-slate-800">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
          </svg>
          <span>Visit Public Site</span>
        </a>

        <!-- Quick Add Post Dropdown -->
        <div class="relative hidden md:block">
          <Link href="/admin/posts/create" class="flex items-center space-x-1 text-xs font-medium text-emerald-400 hover:text-emerald-300 transition px-2 py-1 bg-slate-800/80 rounded border border-slate-700/60">
            <span>+ New</span>
          </Link>
        </div>
      </div>

      <!-- User Menu & RBAC Role Badge -->
      <div class="flex items-center space-x-3">
        <!-- Role badge -->
        <span 
          :class="roleBadgeClasses"
          class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded border shadow-2xs"
        >
          {{ user?.role || 'Guest' }}
        </span>

        <span class="text-xs text-slate-300 font-medium hidden sm:inline">
          {{ user?.name }}
        </span>

        <button 
          @click="logout"
          class="text-xs text-slate-400 hover:text-rose-400 transition px-2 py-1 rounded hover:bg-slate-800 flex items-center space-x-1"
          title="Log out of CMS"
        >
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Logout</span>
        </button>
      </div>
    </header>

    <div class="flex flex-1 pt-12">
      <!-- Mobile Backdrop -->
      <div
        v-if="isMobileMenuOpen"
        @click="isMobileMenuOpen = false"
        class="fixed inset-0 top-12 bg-slate-900/60 backdrop-blur-xs z-20 md:hidden"
      ></div>

      <!-- Fixed Left Navigation Bar -->
      <aside
        :class="[
          isSidebarCollapsed ? 'md:w-16' : 'md:w-60',
          isMobileMenuOpen ? 'translate-x-0 w-60' : '-translate-x-full md:translate-x-0',
        ]"
        class="bg-slate-900 text-slate-300 shrink-0 border-r border-slate-800 flex flex-col justify-between fixed bottom-0 top-12 left-0 overflow-y-auto overflow-x-hidden z-30 transition-all duration-200 ease-in-out"
      >
        <div class="py-4 space-y-1">
          
          <!-- Dashboard -->
          <Link 
            href="/admin" 
            @click="closeMobileMenu"
            title="Dashboard"
            :class="[
              isActive('/admin') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
              isSidebarCollapsed ? 'md:justify-center md:px-0' : ''
            ]"
            class="flex items-center space-x-3 px-4 py-2.5 text-xs transition"
          >
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Dashboard</span>
          </Link>

          <!-- Posts Menu -->
          <div class="pt-2">
            <div v-show="!isSidebarCollapsed" class="px-4 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
              Content
            </div>
            <div v-show="isSidebarCollapsed" class="hidden md:block mx-3 my-1 border-t border-slate-800"></div>

            <Link 
              href="/admin/posts?type=post" 
              @click="closeMobileMenu"
              title="Posts"
              :class="[
                isActive('/admin/posts?type=post') || (isRoute('admin.posts.index') && currentUrl.includes('type=post')) ? 'bg-indigo-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
                isSidebarCollapsed ? 'md:justify-center md:px-0' : ''
              ]"
              class="flex items-center space-x-3 px-4 py-2 text-xs transition"
            >
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
              <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Posts</span>
            </Link>

            <Link 
              href="/admin/posts?type=page" 
              @click="closeMobileMenu"
              title="Pages"
              :class="[
                currentUrl.includes('type=page') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
                isSidebarCollapsed ? 'md:justify-center md:px-0' : ''
              ]"
              class="flex items-center space-x-3 px-4 py-2 text-xs transition"
            >
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Pages</span>
            </Link>

            <Link 
              href="/admin/media" 
              @click="closeMobileMenu"
              title="Media Library"
              :class="[
                isActive('/admin/media') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
                isSidebarCollapsed ? 'md:justify-center md:px-0' : ''
              ]"
              class="flex items-center space-x-3 px-4 py-2 text-xs transition"
            >
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Media Library</span>
            </Link>
          </div>

          <!-- Taxonomies (Admin & Editor only) -->
          <div v-if="user?.role !== 'author'" class="pt-2">
            <div v-show="!isSidebarCollapsed" class="px-4 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
              Organization
            </div>
            <div v-show="isSidebarCollapsed" class="hidden md:block mx-3 my-1 border-t border-slate-800"></div>

            <Link 
              href="/admin/categories" 
              @click="closeMobileMenu"
              title="Categories"
              :class="[
                isActive('/admin/categories') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
                isSidebarCollapsed ? 'md:justify-center md:px-0' : ''
              ]"
              class="flex items-center space-x-3 px-4 py-2 text-xs transition"
            >
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Categories</span>
            </Link>
            <Link 
              href="/admin/tags" 
              @click="closeMobileMenu"
              title="Tags"
              :class="[
                isActive('/admin/tags') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
                isSidebarCollapsed ? 'md:justify-center md:px-0' : ''
              ]"
              class="flex items-center space-x-3 px-4 py-2 text-xs transition"
            >
              <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
              </svg>
              <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Tags</span>
            </Link>
          </div>

          <!-- Site Settings & Optimization (Admin only) -->
          <div v-if="user?.role === 'admin'" class="pt-2">
            <div v-show="!isSidebarCollapsed" class="px-4 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
              System
            </div>
            <div v-show="isSidebarCollapsed" class="hidden md:block mx-3 my-1 border-t border-slate-800"></div>

            <!-- Collapsible Theme Menu -->
            <div>
              <button
                type="button"
                @click="toggleThemeMenu"
                title="Theme"
                :class="[
                  isActive('/admin/theme') ? 'text-white bg-slate-800/80 font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
                  isSidebarCollapsed ? 'md:justify-center md:px-0' : 'justify-between'
                ]"
                class="w-full flex items-center px-4 py-2 text-xs transition"
              >
                <div class="flex items-center space-x-3">
                  <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                  </svg>
                  <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Theme</span>
                </div>
                <svg
                  v-show="!isSidebarCollapsed"
                  :class="isThemeMenuOpen ? 'rotate-180' : ''"
                  class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <div v-show="isThemeMenuOpen && !isSidebarCollapsed" class="mt-0.5 space-y-0.5 bg-slate-950/40 py-1">
                <Link
                  href="/admin/theme/general"
                  @click="closeMobileMenu"
                  :class="isActive('/admin/theme/general') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                  class="flex items-center space-x-2.5 pl-11 pr-4 py-1.5 text-xs transition"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                  <span>General</span>
                </Link>
                <Link
                  href="/admin/theme/buttons"
                  @click="closeMobileMenu"
                  :class="isActive('/admin/theme/buttons') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                  class="flex items-center space-x-2.5 pl-11 pr-4 py-1.5 text-xs transition"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                  <span>Buttons</span>
                </Link>
                <Link
                  href="/admin/theme/header"
                  @click="closeMobileMenu"
                  :class="isActive('/admin/theme/header') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                  class="flex items-center space-x-2.5 pl-11 pr-4 py-1.5 text-xs transition"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                  <span>Header Builder</span>
                </Link>
                <Link
                  href="/admin/theme/footer"
                  @click="closeMobileMenu"
                  :class="isActive('/admin/theme/footer') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
                  class="flex items-center space-x-2.5 pl-11 pr-4 py-1.5 text-xs transition"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                  <span>Footer Builder</span>
                </Link>
              </div>
            </div>

            <Link 
              href="/admin/settings/optimization" 
              @click="closeMobileMenu"
              title="Optimization Panel"
              :class="[
                isActive('/admin/settings/optimization') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white',
                isSidebarCollapsed ? 'md:justify-center md:px-0' : ''
              ]"
              class="flex items-center space-x-3 px-4 py-2 text-xs transition"
            >
              <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <span v-show="!isSidebarCollapsed" class="whitespace-nowrap">Optimization Panel</span>
            </Link>
          </div>

          <!-- Future-Proof E-Commerce Integration (Requirement F) -->
          <div v-show="!isSidebarCollapsed" class="pt-4 border-t border-slate-800/80">
            <div class="px-4 py-1 flex items-center justify-between text-[10px] font-semibold uppercase tracking-wider text-indigo-400">
              <span>Commerce Ready</span>
              <span class="px-1.5 py-0.2 bg-indigo-900/60 text-indigo-300 rounded text-[9px]">Modular</span>
            </div>
            <div class="px-4 py-1 text-[11px] text-slate-400">
              Products, Variants & Orders schema active for instant store deployment.
            </div>
          </div>

        </div>

        <!-- Sidebar Footer & Collapse Button -->
        <div class="border-t border-slate-800">
          <button
            type="button"
            @click="toggleDesktopCollapse"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-4 justify-between'"
            class="hidden md:flex w-full items-center py-2.5 text-xs text-slate-400 hover:text-white hover:bg-slate-800 transition"
            :title="isSidebarCollapsed ? 'Expand menu' : 'Collapse menu'"
          >
            <span v-show="!isSidebarCollapsed" class="whitespace-nowrap font-medium">Collapse menu</span>
            <svg
              :class="isSidebarCollapsed ? 'rotate-180' : ''"
              class="w-4 h-4 shrink-0 transition-transform duration-200"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
          </button>

          <div v-show="!isSidebarCollapsed" class="p-3 border-t border-slate-800/70 text-[11px] text-slate-500 text-center whitespace-nowrap">
            Laravel v{{ $page.props.server_info?.laravel_version || '11' }} &bull; Vue 3
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <main
        :class="isSidebarCollapsed ? 'md:ml-16' : 'md:ml-60'"
        class="flex-1 ml-0 p-6 overflow-x-hidden min-h-[calc(100vh-48px)] transition-all duration-200 ease-in-out"
      >
        <!-- Slot Content -->
        <slot />
      </main>
    </div>

    <!-- Bottom-Right Toast Notifications -->
    <div class="fixed bottom-5 right-5 z-50 space-y-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0">
      <div
        v-if="toastSuccess"
        class="pointer-events-auto p-3.5 bg-white border-l-4 border-emerald-500 rounded-lg shadow-xl border border-slate-200 flex items-center justify-between gap-3"
      >
        <div class="flex items-center space-x-2.5 text-emerald-800 text-xs font-semibold">
          <span class="p-1 bg-emerald-100 text-emerald-600 rounded-full shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </span>
          <span>{{ toastSuccess }}</span>
        </div>
        <button type="button" @click="toastSuccess = ''" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
      </div>

      <div
        v-if="toastError"
        class="pointer-events-auto p-3.5 bg-white border-l-4 border-rose-500 rounded-lg shadow-xl border border-slate-200 flex items-center justify-between gap-3"
      >
        <div class="flex items-center space-x-2.5 text-rose-800 text-xs font-semibold">
          <span class="p-1 bg-rose-100 text-rose-600 rounded-full shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </span>
          <span>{{ toastError }}</span>
        </div>
        <button type="button" @click="toastError = ''" class="text-slate-400 hover:text-slate-600 text-sm leading-none">&times;</button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const SIDEBAR_STORAGE_KEY = 'cms_admin_sidebar_collapsed';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const siteName = computed(() => page.props.settings?.site_name || 'Modern CMS');
const currentUrl = computed(() => page.url);

const isSidebarCollapsed = ref(false);
const isMobileMenuOpen = ref(false);
const isThemeMenuOpen = ref(page.url.startsWith('/admin/theme'));
const toastSuccess = ref('');
const toastError = ref('');
let toastTimer = null;

watch(
  () => page.props.flash,
  (flash) => {
    if (flash?.success) {
      toastSuccess.value = flash.success;
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => { toastSuccess.value = ''; }, 4500);
    }
    if (flash?.error) {
      toastError.value = flash.error;
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => { toastError.value = ''; }, 5000);
    }
  },
  { deep: true, immediate: true }
);

const toggleThemeMenu = () => {
  if (isSidebarCollapsed.value) {
    isSidebarCollapsed.value = false;
    isThemeMenuOpen.value = true;
    return;
  }
  isThemeMenuOpen.value = !isThemeMenuOpen.value;
};

onMounted(() => {
  try {
    isSidebarCollapsed.value = localStorage.getItem(SIDEBAR_STORAGE_KEY) === 'true';
  } catch {
    isSidebarCollapsed.value = false;
  }
});

const toggleDesktopCollapse = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
  try {
    localStorage.setItem(SIDEBAR_STORAGE_KEY, String(isSidebarCollapsed.value));
  } catch {
    // Ignore storage errors in restricted environments
  }
};

const toggleSidebar = () => {
  if (window.innerWidth < 768) {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
  } else {
    toggleDesktopCollapse();
  }
};

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
};

const isRoute = (name) => {
  return page.component.includes(name);
};

const isActive = (path) => {
  if (path === '/admin') {
    return page.url === '/admin';
  }
  return page.url.startsWith(path);
};

const roleBadgeClasses = computed(() => {
  switch (user.value?.role) {
    case 'admin':
      return 'bg-purple-950/70 text-purple-300 border-purple-800';
    case 'editor':
      return 'bg-blue-950/70 text-blue-300 border-blue-800';
    case 'author':
      return 'bg-emerald-950/70 text-emerald-300 border-emerald-800';
    default:
      return 'bg-slate-800 text-slate-300 border-slate-700';
  }
});

const logout = () => {
  router.post('/logout');
};
</script>
