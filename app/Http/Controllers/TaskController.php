<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index()
    {
        // Not used directly; we show tasks on dashboard
        return redirect()->route('dashboard');
    }

    public function create()
    {
        $categories = Category::all();
        $workers = User::where('role', 'worker')->get();
        return view('tasks.create', compact('categories', 'workers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'required|date|after:today',
            'category_id' => 'required|exists:categories,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'],
            'category_id' => $validated['category_id'],
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]);

        if (!empty($validated['user_ids'])) {
            $task->assignedUsers()->attach($validated['user_ids']);
        }

        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $categories = Category::all();
        $workers = User::where('role', 'worker')->get();
        return view('tasks.edit', compact('task', 'categories', 'workers'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:pending,in_progress,completed,overdue',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task->update($validated);

        // Sync assigned workers
        $task->assignedUsers()->sync($validated['user_ids'] ?? []);

        return redirect()->route('dashboard')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('dashboard')->with('success', 'Task deleted.');
    }

    // Worker actions
    public function markComplete(Task $task)
    {
        // Ensure the worker is assigned to this task
        if (!auth()->user()->tasks()->where('task_id', $task->id)->exists()) {
            abort(403, 'You are not assigned to this task.');
        }

        $task->update(['status' => 'completed']);
        // Update pivot completed_at if needed
        auth()->user()->tasks()->updateExistingPivot($task->id, ['completed_at' => now()]);

        return redirect()->route('dashboard')->with('success', 'Task marked as completed.');
    }

    public function markInProgress(Task $task)
    {
        if (!auth()->user()->tasks()->where('task_id', $task->id)->exists()) {
            abort(403, 'You are not assigned to this task.');
        }
        $task->update(['status' => 'in_progress']);
        return redirect()->route('dashboard')->with('success', 'Task is now in progress.');
    }
}