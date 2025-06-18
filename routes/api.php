<?php

use Illuminate\Http\Request;
// Import Route class for defining routes
use Illuminate\Support\Facades\Route;
// Import the TaskController where your task logic lives
use App\Http\Controllers\API\TaskController;

// Kinahanglan naka-login ang user para maka-access ani nga routes
Route::middleware('auth:sanctum')->group(function () {

    // GET /api/tasks → Get all tasks for the logged-in user
    Route::get('/tasks', [TaskController::class, 'index']);

    // POST /api/tasks → Add/create a new task
    Route::post('/tasks', [TaskController::class, 'store']);

    // PUT /api/tasks/{task} → Update an existing task
    Route::put('/tasks/{task}', [TaskController::class, 'update']);

    // DELETE /api/tasks/{task} → Delete a task
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});



/**
 * API routes are used to connect the frontend to the backend.
 * but because where using inertia.js, we don't need to use api routes.
 */

