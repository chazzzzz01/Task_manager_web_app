<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-900 text-white">
    <div class="bg-gray-800 p-10 rounded-2xl shadow-lg w-full max-w-md text-center">
      <h1 class="text-3xl font-bold mb-6 tracking-wide">Welcome to Task Manager Web App!</h1>

      <p class="mb-8 text-gray-400">Please login or register to continue</p>

      <div v-if="!user" class="flex justify-center space-x-6">
        <Link 
          :href="route('login')" 
          class="px-6 py-2 rounded-full bg-blue-600 hover:bg-blue-700 transition duration-200"
        >
          Login
        </Link>
        <Link 
          :href="route('register')" 
          class="px-6 py-2 rounded-full bg-green-600 hover:bg-green-700 transition duration-200"
        >
          Register
        </Link>
      </div>
      <div v-else>
        <p class="text-green-400 font-semibold">You are logged in as {{ user.name }}!</p>
        <Link :href="route('dashboard')" class="mt-4 inline-block text-blue-400 underline">
          Go to Dashboard
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const { props } = usePage()
const user = props.auth.user
</script>
