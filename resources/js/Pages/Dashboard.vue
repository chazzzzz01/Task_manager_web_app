<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskForm from '@/Components/TaskForm.vue'; // ✅ import TaskForm
import TaskTable from '@/Components/TaskTable.vue'; // ✅ import TaskTable

// Props passed from Laravel controller
const props = defineProps({
  tasks: Array,
  flash: Object,
});

const successMessage = ref(props.flash?.success || '');
const showForm = ref(false);

// Reusable success handler
function handleSuccess(message) {
  successMessage.value = message;
  setTimeout(() => successMessage.value = '', 3000);
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
      
      <!-- Flash message -->
      <div v-if="successMessage" class="bg-green-100 text-green-800 px-4 py-2 rounded shadow text-sm">
        {{ successMessage }}
      </div>

      <!-- Button to show the task form -->
      <div class="flex justify-start">
        <button @click="showForm = true" class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-sm">
          + New Task
        </button>
      </div>

      <!-- Show TaskForm.vue -->
      <TaskForm v-if="showForm" @close="showForm = false" @success="handleSuccess" />

      <!-- Show TaskTable.vue -->
      <TaskTable :tasks="props.tasks" @success="handleSuccess" />

    </div>
  </AuthenticatedLayout>
</template>
