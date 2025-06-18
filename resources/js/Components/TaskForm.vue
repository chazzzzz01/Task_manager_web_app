<!-- TaskForm.vue -->
<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

// Emits
const emit = defineEmits(['success', 'close']);

// Form setup
const form = useForm({
  title: '',
  description: '',
});

const loading = ref(false);

// Submit handler
function submit() {
  form.clearErrors();

  if (!form.title.trim()) {
    form.errors.title = 'Title is required';
    return;
  }

  loading.value = true;

  form.post('/tasks', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      emit('success', 'Task created successfully!');
      emit('close');
    },
    onFinish: () => {
      loading.value = false;
    },
  });
}
</script>

<template>
  <!-- Centered Wrapper -->
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-800">Create New Task</h2>

      <form @submit.prevent="submit" class="space-y-4">

        <!-- Title Field -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
          <input
            v-model="form.title"
            type="text"
            id="title"
            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
          />
          <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
            {{ form.errors.title }}
          </div>
        </div>

        <!-- Description Field -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            v-model="form.description"
            id="description"
            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
          ></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2">
          <button
            type="button"
            @click="$emit('close')"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm"
            :disabled="loading"
          >
            {{ loading ? 'Saving...' : 'Create Task' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
