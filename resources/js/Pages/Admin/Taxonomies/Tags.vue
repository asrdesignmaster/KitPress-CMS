<template>
  <AdminLayout>
    <div class="space-y-6">
      <div class="pb-4 border-b border-slate-200">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tags</h1>
        <p class="text-xs text-slate-500 mt-1">Freeform tagging taxonomy for content discovery.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Add Tag Form -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-2xs space-y-4">
          <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Add New Tag</h3>
          <form @submit.prevent="submit" class="space-y-3 text-xs">
            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Name</label>
              <input v-model="form.name" type="text" required class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
            </div>

            <div class="space-y-1">
              <label class="font-semibold text-slate-700 block">Slug</label>
              <input v-model="form.slug" type="text" class="w-full px-2.5 py-1.5 border rounded-lg bg-slate-50" />
            </div>

            <button type="submit" :disabled="form.processing" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-xs transition">
              Add Tag
            </button>
          </form>
        </div>

        <!-- Tags Table -->
        <div class="md:col-span-2 bg-white rounded-xl border border-slate-200/90 shadow-2xs overflow-hidden">
          <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[10px]">
              <tr>
                <th class="px-5 py-3">Tag Name</th>
                <th class="px-4 py-3">Slug</th>
                <th class="px-4 py-3">Usage Count</th>
                <th class="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="tag in tags.data" :key="tag.id" class="hover:bg-slate-50">
                <td class="px-5 py-3 font-semibold text-slate-900">{{ tag.name }}</td>
                <td class="px-4 py-3 text-slate-500 font-mono">{{ tag.slug }}</td>
                <td class="px-4 py-3 text-slate-600">{{ tag.posts_count }}</td>
                <td class="px-4 py-3 text-right">
                  <button @click="deleteTag(tag.id)" class="text-rose-600 hover:text-rose-800">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  tags: Object,
});

const form = useForm({
  name: '',
  slug: '',
});

const submit = () => {
  form.post('/admin/tags', {
    onSuccess: () => form.reset(),
  });
};

const deleteTag = (id) => {
  if (confirm('Delete this tag?')) {
    router.delete(`/admin/tags/${id}`);
  }
};
</script>

