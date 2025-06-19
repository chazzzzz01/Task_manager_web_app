<script setup>
import { ref } from 'vue'; // gamit sa pag declare ug reactive variables (mo-update kung naay changes)

import { Head } from '@inertiajs/vue3'; // para sa page title (browser tab name)

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; 
// ➤ wrapper layout para sa mga naka-login nga users

import TaskForm from '@/Components/TaskForm.vue'; 
// ➤ component para sa task creation/editing form

import TaskTable from '@/Components/TaskTable.vue'; 
// ➤ component nga mo display sa table/list sa imong tasks

// ➤ props nga gikan sa Laravel controller
const props = defineProps({
  tasks: Array,      // list sa tasks gikan sa database
  flash: Object,     // message (like "Task Created!") human sa action
});

// para i-store ang flash message temporarily
const successMessage = ref(props.flash?.success || '');

// state kung i-open ba ang form (true = i-show)
const showForm = ref(false);

// function nga mo-handle sa flash message success
function handleSuccess(message) {
  successMessage.value = message; // set ang message
  setTimeout(() => successMessage.value = '', 3000); // mawala after 3 seconds
}
</script>


<template>
  <Head title="Dashboard" /> 
  <!-- I-set ang title sa browser tab -->

  <AuthenticatedLayout>
    <!-- Gamit nga layout kung naka-login ang user -->

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
      
      <!-- Flash success message -->
      <div v-if="successMessage" class="bg-green-100 text-green-800 px-4 py-2 rounded shadow text-sm">
        {{ successMessage }}
      </div>

      <!-- Button to open TaskForm -->
      <div class="flex justify-start">
        <button 
          @click="showForm = true" 
          class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-sm"
        >
          + New Task
        </button>
      </div>

      <!-- Display TaskForm only when showForm is true -->
      <TaskForm 
        v-if="showForm" 
        @close="showForm = false" 
        @success="handleSuccess" 
      />

      <!-- Display the task table -->
      <TaskTable 
        :tasks="props.tasks" 
        @success="handleSuccess" 
      />
    </div>
  </AuthenticatedLayout>
</template>


<!-- The Dashboard.vue is the main page for the authenticated users(the user who already login). From the
 laravel controller, the props tasks and flash will be send in here. Used of Inertia.js deritso mo render og vue
 components. I have the button "new task" if i click it, it wil lset the showForm = true, which will display the 
 TaskForm.vue component. The TaskForm.vue is the form for inputing new task title and description. If the task is successful,
 and @success event mo call sa handleSuccess(), and it will set a successMessage and it will display a green notif.
 The TaskTable.vue is the component that wil ldisplay the task with the use of props nga tasks. Evertime mag create, delete, or update
 a task, it will refresh the table.
-->