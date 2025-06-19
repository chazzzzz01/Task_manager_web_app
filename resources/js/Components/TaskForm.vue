
<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3'; // Import sa Inertia form handler (para same sa Laravel form handling)

// Emits success and close event back to parent (Dashboard.vue)
const emit = defineEmits(['success', 'close']);

// Setup sa form inputs (title ug description) gamit useForm
const form = useForm({
  title: '',
  description: '',
});

// Loading state para i-disable ang button kung nag-submit
const loading = ref(false);

// Handle sa form submission
function submit() {
  form.clearErrors(); // ➤ I-clear una ang previous errors

  //  Validation: kung walay title, butangi og error
  if (!form.title.trim()) {
    form.errors.title = 'Title is required';
    return;
  }

  loading.value = true; //  I-activate ang loading state

  //  Send POST request to /tasks using Inertia
  form.post('/tasks', {
    preserveScroll: true, //  PaFra di mo-scroll to top after submit

    onSuccess: () => {
      form.reset(); //  I-clear ang form inputs
      emit('success', 'Task created successfully!'); // ➤ Send flash message to parent
      emit('close'); // Close the modal (TaskForm)
    },

    onFinish: () => {
      loading.value = false; //  Reset loading state
    },
  });
}
</script>


<template>
  <!-- Black overlay background for modal -->
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

    <!-- Modal card container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 space-y-4">

      <h2 class="text-lg font-semibold text-gray-800">Create New Task</h2>

      <!-- Form submission -->
      <form @submit.prevent="submit" class="space-y-4">

        <!-- Title Input Field -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
          <input
            v-model="form.title"
            type="text"
            id="title"
            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
          />
          <!-- Error message for title -->
          <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
            {{ form.errors.title }}
          </div>
        </div>

        <!-- Description Input Field -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            v-model="form.description"
            id="description"
            class="w-full border-gray-300 rounded-md shadow-sm text-sm"
          ></textarea>
        </div>

        <!-- Buttons: Cancel and Submit -->
        <div class="flex justify-end gap-2">
          <!-- Cancel Button -->
          <button
            type="button"
            @click="$emit('close')"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm"
          >
            Cancel
          </button>

          <!-- Submit Button -->
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


<!-- The TaskForm.vue component is a form to create a new task. if i submit the title and description, 
 it will use inertia.js to send the data to the backend (/tasks) and it will handle the response.
 it has an error check, loading state, and it will emit a success and close event back to the parent component Dashboad.vue.

-->