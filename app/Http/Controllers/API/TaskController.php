<?php

// Define the folder (namespace) where this controller belongs
namespace App\Http\Controllers\API;

// Import necessary classes so I can use them below
use App\Http\Controllers\Controller; // The base Laravel controller
use App\Models\Task; // The Task model (represents 'tasks' table in DB)
use Illuminate\Http\Request; // Handles HTTP request data
use Illuminate\Support\Facades\Auth; // Helps get the currently logged-in user

// Define a new controller class to handle task operations
class TaskController extends Controller
{    
    // Show all tasks for the logged-in user
    public function index()
    {
     
        // Get tasks for the logged-in user, ordered by newest first
        // Example: if user has 3 tasks, this will return all of them
        return Auth::user()->tasks()->latest()->get();
    }

    // Store (save) a new task in the database
    public function store(Request $request)
    {

        // Validate the user input
        // 'title' is required and max 255 characters
        // 'description' is optional, means even if u add blank it won't throw an error
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Save the task for the current user
        // Example: You submit a form with title "Buy Milk" — this will save it
        Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // After saving, go back to dashboard and show success message
        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }

    // Update an existing task
    public function update(Request $request, Task $task)
    {

        // Check if user has permission to update this task
        $this->authorize('update', $task);

        // check ang input kung valid
        $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'is_done' => 'boolean',
    ]);

    // I-update ang task base sa mga bagong data nga gisubmit
    $task->update($request->only('title', 'description', 'is_done'));

    //Refresh the task to get the lates data
    $task->refresh();

    // If coming from an API or AJAX request, return JSON response
    if ($request->wantsJson()) {
        return response()->json([
            'message' => 'Task updated successfully.',
            'task' => $task,
        ]);
    }

    // Kung regular na form submission, redirect lang pabalik
    return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
}

    // Delete a task
    public function destroy(Task $task)
    {
        // Check authorization
        $this->authorize('delete', $task);

        // delete the task from the database
        $task->delete();
        
        // redirect back to the dashboard wit success message
        return redirect()->route('dashboard')->with('success', 'Task deleted successfully.');
    }
}




/**  The TaskController handles everything to manage the tasks. 
 * If a user logged in the controller will show all the tasks(index),
 * If the user add a new taskit will save it to the database(store).
 * If the user want to edit a task, it will update the task(update).
 * User can also delete a task from the database(destroy). This
 * controller is used by the TaskPageController to show the tasks.
 * **/

