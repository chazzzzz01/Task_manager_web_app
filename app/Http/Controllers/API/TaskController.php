<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{    
    public function index()
    {
        
        return Auth::user()->tasks()->latest()->get();
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'is_done' => 'boolean',
    ]);

    $task->update($request->only('title', 'description', 'is_done'));

    
    $task->refresh();

    if ($request->wantsJson()) {
        return response()->json([
            'message' => 'Task updated successfully.',
            'task' => $task,
        ]);
    }

    return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
}


    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted successfully.');
    }
}
