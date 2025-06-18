<?php

// Define the folder (namespace) where this controller belongs.
namespace App\Http\Controllers;

// Import Laravel's Auth class to access the current logged-in user
use Illuminate\Support\Facades\Auth;
// Import Inertia — used to render Vue components from Laravel controllers
use Inertia\Inertia; 

// This controller handles loading the Dashboard page with tasks
class TaskPageController extends Controller
{
    // Load the Dashboard page and pass the user's tasks to it
    public function index()
    {
        return Inertia::render('Dashboard', [

            // Fetch all tasks for the currently logged-in user, newest first
            'tasks' => auth()->user()->tasks()->latest()->get(),
    'flash' => [
        'success' => session('success'),
    ],
        ]);
    }
}

/**
 * The TaskPageController is a simple Laravel controller that loads the Dashboard.vue component using Inertia.
 * When a logged-in user visits the dashboard route, this controller's index() method runs. 
 * It uses Auth::user()->tasks() to fetch all tasks belonging to the current user and orders them by the latest created first. 
 * The tasks are then passed to the Vue component named Dashboard, where they can be displayed in a table or list. 
 * This controller acts like a bridge between Laravel (back-end) and Vue (front-end).
 * 
 */
