# Task Manager App

A simple task management web app built with Laravel and Vue.js.

## Features
- User registration, login, and logout
- Create, update, mark as done, and delete tasks
- Built with Vue frontend (via Inertia.js + Laravel 
  Breeze)
- Tailwind CSS for styling

## Setup Instructions

- composer create-project laravel/laravel task-manager
- cd task-manager
- composer require laravel/breeze --dev
- php artisan breeze:install vue
- npm install
- npm run dev (if the vite not found run "npm run 
  build")
- php artisan migrate
- php artisan serve

I got an error SQLSTATE[HY000], this mean that my laravel app cant connect to the MYSQL database server, to fix this:

- I check .env file to make sure the value of each  
  database are correct.
- I check if MYSQL is running, open XAMMP and start 
  the apache and mysql.


## Assumption Made

- I'm using MySQL as the database.
- I already have PHP, Composer, Node.js, and npm    
  installed on my machine.
- I used Laravel Breeze because it's a fast and simple 
  way to get auth + Vue set up.
- Each user only sees their own tasks, no roles or 
  admin panel.
- The UI is clean but simple no fancy design yet,   
  just focused on functionality.
- Right now, my Mailpit and Docker setup is not 
  working properly, so if i want to reset my password it cannot recieved an email for the password reset link that will allow me to change it.






