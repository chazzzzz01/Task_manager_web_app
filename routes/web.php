<?php

// Inertia is used to render Vue components from the backend
use Inertia\Inertia;

// Application gives access to app-level constants (like version)
use Illuminate\Foundation\Application;

// Auth is used to get the currently logged-in user

use Illuminate\Support\Facades\Auth;

// Gigamit para mag-declare sa mga routes
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskPageController;
use App\Http\Controllers\API\TaskController; 


// Route for homepage ("/") — this loads Home.vue using Inertia
Route::get('/', function () {
    return Inertia::render('Home', [
        
        // Check if login route exists
        'canLogin' => Route::has('login'),

        // Check if register route exists
        'canRegister' => Route::has('register'),

        
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,

        // Send authenticated user data to Vue
        'auth' => [
            'user' => auth()->user(),
        ],
    ]);
});

// Routes that require the user to be logged in and email-verified
Route::middleware(['auth', 'verified'])->group(function () {

    // dashboard
    Route::get('/dashboard', [TaskPageController::class, 'index'])->name('dashboard');

    // profile routing
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Task routing
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
});

require __DIR__.'/auth.php';


/**
 * This file defines all the web routes for the Laravel + Inertia.js app. 
 * Used Inertia para mo-render og Vue components like Home.vue ug Dashboard.vue. 
 * Grouped the routes under middleware to make sure only users who are logged in and verified can access pages like the dashboard, profile, and task features.
 * Naay mga routes para mag-create, update, ug delete sa tasks, and ang data kay gikan or paingon sa controller logic. 
 * Lastly, gi-load pud ang mga authentication routes gikan sa auth.php.
 */