# Task Manager App

A simple task management web app built with Laravel 10 and Vue.js 3.

## Features
- Register/Login/Logout
- CRUD for personal tasks
- Toggle task completion
- Vue 3 frontend with Axios

## Setup Instructions

- composer create-project laravel/laravel task-manager
- cd task-manager
- composer require laravel/breeze --dev
- php artisan breeze:install vue
- npm install
- npm run dev (if the vite not found run "npm run build")
php artisan migrate
php artisan serve



