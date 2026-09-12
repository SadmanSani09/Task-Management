<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    /* ---------- LIST ALL TASKS ---------- */
    public function index(Request $request)
    {
        $query = Task::with(['category', 'assignedUsers', 'creator']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('worker_id')) {
            $query->whereHas('assignedUsers', fn($q) => $q->where('users.id', $request->worker_id));
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks      = $query->latest()->paginate(10)->withQueryString();
        $workers    = User::where('role', 'worker')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.tasks.index', compact('tasks', 'workers', 'categories'));
    }

    /* ---------- CREATE FORM ---------- */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $workers    = User::where('role', 'worker')->orderBy('name')->get();

        return view('admin.tasks.create', compact('categories', 'workers'));
    }

    /* ---------- STORE NEW TASK ---------- */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'deadline'    => 'required|date',
            'status'      => 'required|in:pending,in_progress,completed,overdue',
            'category_id' => 'required|exists:categories,id',
            'worker_ids'  => 'required|array|min:1',
            'worker_ids.*'=> 'exists:users,id',
        ], [
            'worker_ids.required' => 'You must assign this task to at least one worker.',
        ]);

        $task = Task::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? '',
            'priority'    => $validated['priority'],
            'deadline'    => $validated['deadline'],
            'status'      => $validated['status'],
            'category_id' => $validated['category_id'],
            'created_by'  => auth()->id(),
        ]);

        // 🔥 Manual assignment to selected workers
        $task->assignedUsers()->sync($validated['worker_ids']);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', "Task \"{$task->title}\" created and assigned to " . count($validated['worker_ids']) . " worker(s).");
    }

    /* ---------- SHOW ---------- */
    public function show(Task $task)
    {
        $task->load(['category', 'assignedUsers', 'creator']);
        $workers = User::where('role', 'worker')->orderBy('name')->get();

        return view('admin.tasks.show', compact('task', 'workers'));
    }

    /* ---------- EDIT FORM ---------- */
    public function edit(Task $task)
    {
        $categories = Category::orderBy('name')->get();
        $workers    = User::where('role', 'worker')->orderBy('name')->get();

        return view('admin.tasks.edit', compact('task', 'categories', 'workers'));
    }

    /* ---------- UPDATE (from database) ---------- */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'deadline'    => 'required|date',
            'status'      => 'required|in:pending,in_progress,completed,overdue',
            'category_id' => 'required|exists:categories,id',
            'worker_ids'  => 'nullable|array',
            'worker_ids.*'=> 'exists:users,id',
        ]);

        $task->update($validated);

        // Sync workers (replaces old assignments)
        $task->assignedUsers()->sync($validated['worker_ids'] ?? []);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', "Task \"{$task->title}\" updated successfully.");
    }

    /* ---------- DELETE ---------- */
    public function destroy(Task $task)
    {
        $title = $task->title;

        // Detach workers first (pivot cleanup)
        $task->assignedUsers()->detach();
        $task->delete();

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', "Task \"{$title}\" deleted from database.");
    }

    /* ---------- QUICK UPDATE (inline edit) ---------- */
    public function quickUpdate(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status'   => 'sometimes|in:pending,in_progress,completed,overdue',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'deadline' => 'sometimes|date',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task updated from database.');
    }

    /* ---------- ASSIGN WORKER MANUALLY ---------- */
    public function assignWorker(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Attach if not already assigned
        if (!$task->assignedUsers()->where('users.id', $request->user_id)->exists()) {
            $task->assignedUsers()->attach($request->user_id);
            return back()->with('success', 'Worker assigned to task.');
        }

        return back()->with('error', 'Worker is already assigned.');
    }

    /* ---------- UNASSIGN WORKER ---------- */
    public function unassignWorker(Task $task, User $user)
    {
        $task->assignedUsers()->detach($user->id);

        return back()->with('success', 'Worker removed from task.');
    }
}