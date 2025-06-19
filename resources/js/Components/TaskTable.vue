<script setup>
// Imports
import { ref, reactive } from 'vue'; // ➤ ref for reactive single value, reactive for form object
import { router } from '@inertiajs/vue3'; // ➤ for making Inertia PUT/DELETE requests

// Props: Receive task data from parent (Dashboard.vue)
const props = defineProps({ tasks: Array });

// Emit success message back to Dashboard.vue after updates
const emit = defineEmits(['success']);

// This will hold the ID of the task currently being edited
const editingTaskId = ref(null);

// Editable form data
const editForm = reactive({
  title: '',
  description: ''
});

// ➤ Toggle if the task is done or not (PUT request to /tasks/{id})
function toggleDone(task) {
  router.put(`/tasks/${task.id}`, {
    is_done: !task.is_done
  }, {
    preserveScroll: true,
    onSuccess: () => emit('success', 'Task updated successfully!')
  });
}

// ➤ Delete a task with confirmation (DELETE request)
function deleteTask(id) {
  if (confirm('Are you sure you want to delete this task?')) {
    router.delete(`/tasks/${id}`, {
      preserveScroll: true,
      onSuccess: () => emit('success', 'Task deleted successfully!')
    });
  }
}

// Start editing a task: fill editForm and set editingTaskId
function startEdit(task) {
  editingTaskId.value = task.id;
  editForm.title = task.title;
  editForm.description = task.description;
}

// Cancel editing
function cancelEdit() {
  editingTaskId.value = null;
}

// Update the task using form data (PUT request)
function updateTask(taskId) {
  if (!editForm.title.trim()) return alert('Title is required.');

  router.put(`/tasks/${taskId}`, {
    title: editForm.title,
    description: editForm.description
  }, {
    preserveScroll: true,
    onSuccess: () => {
      editingTaskId.value = null;
      emit('success', 'Task updated successfully!');
    }
  });
}
</script>


<template>
  <!-- Container for the table -->
  <div class="bg-white p-6 shadow sm:rounded-lg">
    <h3 class="text-lg font-bold mb-4">Your Tasks</h3>

    <!-- Table wrapper -->
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto border border-gray-200 text-sm">
        <thead class="bg-gray-100 text-left font-semibold text-gray-700">
          <tr>
            <th class="px-4 py-2 border">#</th>
            <th class="px-4 py-2 border">Title</th>
            <th class="px-4 py-2 border">Description</th>
            <th class="px-4 py-2 border text-center">Done</th>
            <th class="px-4 py-2 border">Created At</th>
            <th class="px-4 py-2 border">Updated At</th>
            <th class="px-4 py-2 border text-center">Actions</th>
          </tr>
        </thead>

        <!-- List of tasks -->
        <tbody>
          <tr v-for="(task, index) in props.tasks" :key="task.id" class="hover:bg-gray-50">
            <!-- Row Number -->
            <td class="px-4 py-2 border">{{ index + 1 }}</td>

            <!-- Title -->
            <td class="px-4 py-2 border">
              <!-- If editing, show input -->
              <div v-if="editingTaskId === task.id">
                <input v-model="editForm.title" class="w-full border-gray-300 rounded-md shadow-sm text-sm" />
              </div>
              <!-- Else, show text (with line-through if done) -->
              <span v-else :class="{ 'line-through text-gray-500': task.is_done }">
                {{ task.title }}
              </span>
            </td>

            <!-- Description -->
            <td class="px-4 py-2 border">
              <div v-if="editingTaskId === task.id">
                <textarea v-model="editForm.description" class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
              </div>
              <span v-else>{{ task.description || '—' }}</span>
            </td>

            <!-- Done Checkbox -->
            <td class="px-4 py-2 border text-center">
              <input type="checkbox" :checked="task.is_done" @change="toggleDone(task)" class="cursor-pointer" />
            </td>

            <!-- Created/Updated Dates -->
            <td class="px-4 py-2 border text-gray-500">
              {{ new Date(task.created_at).toLocaleString() }}
            </td>
            <td class="px-4 py-2 border text-gray-500">
              {{ new Date(task.updated_at).toLocaleString() }}
            </td>

            <!-- Actions -->
            <td class="px-4 py-2 border text-center space-x-1">
              <template v-if="editingTaskId === task.id">
                <!-- Save/Cancel buttons if editing -->
                <button @click="updateTask(task.id)" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">Save</button>
                <button @click="cancelEdit" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-xs">Cancel</button>
              </template>
              <template v-else>
                <!-- Edit/Delete buttons if not editing -->
                <button @click="startEdit(task)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-xs">Edit</button>
                <button @click="deleteTask(task.id)" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">Delete</button>
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<!-- This component displays a table of tasks with options to edit, delete, and mark as done.
 The data is handles with the use if the router from the inertia.js, same with the logic of the laravel
 routes. but in frontend side. If i edit someting to the input filed, automatic send to the laravel. This
 is the main way to the user to manage their tasks.
 It has a reactive form for editing tasks, and it uses the Inertia router to send PUT/DELETE requests.
 It also emits a success event to the parent component (Dashboard.vue) to show a success message.


-->