<template>
  <div class="min-h-screen bg-slate-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8 antialiased">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
      <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 text-white shadow-xl shadow-indigo-600/30 mb-4">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <h2 class="text-2xl font-bold tracking-tight text-white">
        Sign in to Modern CMS
      </h2>
      <p class="mt-2 text-xs text-slate-400">
        High-Performance Laravel + Vue 3 Administration
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-slate-800/90 py-8 px-6 shadow-2xl rounded-2xl sm:px-10 border border-slate-700/60 backdrop-blur-sm">
        
        <!-- Error Alert -->
        <div v-if="form.errors.email" class="mb-4 p-3 bg-rose-950/50 border border-rose-800 rounded-lg text-rose-200 text-xs flex items-center space-x-2">
          <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ form.errors.email }}</span>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-xs font-medium text-slate-300">
              Email Address
            </label>
            <div class="mt-1">
              <input 
                v-model="form.email" 
                type="email" 
                required 
                class="w-full px-3 py-2 text-xs bg-slate-900 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                placeholder="admin@cms.test"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-medium text-slate-300">
              Password
            </label>
            <div class="mt-1">
              <input 
                v-model="form.password" 
                type="password" 
                required 
                class="w-full px-3 py-2 text-xs bg-slate-900 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                placeholder="••••••••"
              />
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input 
                v-model="form.remember" 
                id="remember" 
                type="checkbox" 
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-700 rounded bg-slate-900"
              />
              <label for="remember" class="ml-2 block text-xs text-slate-400">
                Remember me
              </label>
            </div>
          </div>

          <div>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-xs text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition"
            >
              <span v-if="form.processing">Authenticating...</span>
              <span v-else>Log In to Dashboard</span>
            </button>
          </div>
        </form>

        <!-- Quick Demo Role Switcher -->
        <div class="mt-6 pt-6 border-t border-slate-700/60">
          <p class="text-[11px] font-medium text-slate-400 mb-2 text-center">
            Quick-Login Role Demo Credentials:
          </p>
          <div class="grid grid-cols-3 gap-2">
            <button 
              type="button"
              @click="quickLogin('admin@cms.test')"
              class="px-2 py-1.5 text-[10px] font-medium bg-slate-900 hover:bg-indigo-950/80 text-purple-300 border border-purple-800/60 rounded text-center transition"
            >
              Admin
            </button>
            <button 
              type="button"
              @click="quickLogin('editor@cms.test')"
              class="px-2 py-1.5 text-[10px] font-medium bg-slate-900 hover:bg-indigo-950/80 text-blue-300 border border-blue-800/60 rounded text-center transition"
            >
              Editor
            </button>
            <button 
              type="button"
              @click="quickLogin('author@cms.test')"
              class="px-2 py-1.5 text-[10px] font-medium bg-slate-900 hover:bg-indigo-950/80 text-emerald-300 border border-emerald-800/60 rounded text-center transition"
            >
              Author
            </button>
          </div>
          <p class="text-[10px] text-slate-500 text-center mt-2">
            Password: <code>password</code>
          </p>
        </div>

      </div>

      <div class="mt-4 text-center">
        <Link href="/" class="text-xs text-slate-400 hover:text-white transition">
          &larr; Return to public site
        </Link>
      </div>
    </div>

  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
  email: 'admin@cms.test',
  password: 'password',
  remember: true,
});

const quickLogin = (email) => {
  form.email = email;
  form.password = 'password';
};

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};
</script>

