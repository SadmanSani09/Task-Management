<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class WorkerTaskController extends Controller
{
    /**
     * Worker task detail page (with comments).
     */
    public function show(Task $task)
    {
        $this->ensureAssigned($task);

        $task->load(['category', 'creator', 'comments.user']);

        return view('worker.tasks.show', compact('task'));
    }

    /**
     * Worker starts a task: pending → in_progress
     */
    public function markInProgress(Task $task)
    {
        $this->ensureAssigned($task);

        if ($task->status !== 'pending') {
            return back()->with('error', 'Task cannot be started (already in progress or completed).');
        }

        $task->update(['status' => 'in_progress']);

        return back()->with('success', 'Task marked as In Progress.');
    }

    /**
     * Worker completes a task: in_progress → completed
     */
    public function markComplete(Task $task)
    {
        $this->ensureAssigned($task);

        if ($task->status === 'completed') {
            return back()->with('error', 'Task is already completed.');
        }

        $task->update(['status' => 'completed']);

        // Update pivot
        auth()->user()->tasks()->updateExistingPivot($task->id, [
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Task marked as Completed. 🎉');
    }

    /**
     * 🔒 Ensure the current worker is assigned to the task.
     */
    protected function ensureAssigned(Task $task): void
    {
        $assigned = auth()->user()
            ->tasks()
            ->where('tasks.id', $task->id)
            ->exists();

        if (!$assigned) {
            abort(403, 'You are not assigned to this task.');
        }
    }
}