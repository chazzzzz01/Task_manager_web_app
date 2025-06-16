<script setup>
import { ref, reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

// Props
const props = defineProps({
    tasks: Array
});

// Form for creating new task
const form = useForm({
    title: '',
    description: '',
});

// Editing logic
const editingTaskId = ref(null);
const editForm = reactive({
    title: '',
    description: '',
});

// Toggle form visibility
const showForm = ref(false);
function toggleForm() {
    showForm.value = !showForm.value;
}

// Create Task
function submit() {
    form.post('/tasks', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
}

// Delete Task
function deleteTask(id) {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(`/tasks/${id}`, { preserveScroll: true });
    }
}

// Toggle Done
function toggleDone(task) {
    router.put(`/tasks/${task.id}`, {
        is_done: !task.is_done,
    }, { preserveScroll: true });
}

// Start Editing
function startEdit(task) {
    editingTaskId.value = task.id;
    editForm.title = task.title;
    editForm.description = task.description;
}

// Cancel Editing
function cancelEdit() {
    editingTaskId.value = null;
}

// Submit Edit
function updateTask(taskId) {
    router.put(`/tasks/${taskId}`, {
        title: editForm.title,
        description: editForm.description,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            editingTaskId.value = null;
        }
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- Create Toggle -->
                <div class="bg-white p-4 shadow sm:rounded-lg flex justify-start">
                    <button @click="toggleForm" class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-sm">
                        {{ showForm ? '− Hide Form' : '+ New Task' }}
                    </button>
                </div>

                <!-- Create Task Form -->
                <div v-if="showForm" class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold mb-4">Create a New Task</h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input v-model="form.title" type="text" id="title" class="w-full border-gray-300 rounded-md shadow-sm text-sm" />
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" id="description" class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Create Task
                        </button>
                    </form>
                </div>

                <!-- Task Table -->
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold mb-4">Your Tasks</h3>

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
                            <tbody>
                                <tr v-for="(task, index) in props.tasks" :key="task.id" class="hover:bg-gray-50">
    <td class="px-4 py-2 border">{{ index + 1 }}</td>

    <td class="px-4 py-2 border">
        <div v-if="editingTaskId === task.id">
            <input v-model="editForm.title" class="w-full border-gray-300 rounded-md shadow-sm text-sm" />
        </div>
        <span v-else :class="{ 'line-through text-gray-500': task.is_done }">
            {{ task.title }}
        </span>
    </td>

    <td class="px-4 py-2 border">
        <div v-if="editingTaskId === task.id">
            <textarea v-model="editForm.description" class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
        </div>
        <span v-else>{{ task.description || '—' }}</span>
    </td>

    <td class="px-4 py-2 border text-center">
        <input type="checkbox" :checked="task.is_done" @change="toggleDone(task)" class="cursor-pointer" />
    </td>

    <td class="px-4 py-2 border text-gray-500">
        {{ new Date(task.created_at).toLocaleString() }}
    </td>

    <td class="px-4 py-2 border text-gray-500">
        {{ new Date(task.updated_at).toLocaleString() }}
    </td>

    <td class="px-4 py-2 border text-center space-x-1">
        <template v-if="editingTaskId === task.id">
            <button @click="updateTask(task.id)" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                Save
            </button>
            <button @click="cancelEdit" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-xs">
                Cancel
            </button>
        </template>
        <template v-else>
            <button @click="startEdit(task)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-xs">
                Edit
            </button>
            <button @click="deleteTask(task.id)" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                Delete
            </button>
        </template>
    </td>
</tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.line-through {
    text-decoration: line-through;
}
</style>
