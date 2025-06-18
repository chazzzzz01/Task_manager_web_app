<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

// Safely access the user
const user = computed(() => usePage().props.auth?.user ?? null)
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-white text-gray-800 px-4">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md text-center border border-gray-200">
      <h1 class="text-3xl font-bold mb-6 tracking-wide text-gray-900">
        Welcome to Task Manager Web App!
      </h1>

      <p class="mb-8 text-gray-600">Please login or register to continue</p>

      <div v-if="!user" class="flex justify-center space-x-4">
        <Link 
          :href="route('login')" 
          class="px-6 py-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-medium shadow transition duration-200"
        >
          Login
        </Link>
        <Link 
          :href="route('register')" 
          class="px-6 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white font-medium shadow transition duration-200"
        >
          Register
        </Link>
      </div>

      <div v-else>
        <p class="text-green-600 font-semibold">You are logged in as {{ user.name }}!</p>
        <Link 
          :href="route('dashboard')" 
          class="mt-4 inline-block text-blue-600 underline hover:text-blue-800 transition"
        >
          Go to Dashboard
        </Link>
      </div>
    </div>
  </div>
</template>
