<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class WorkerCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        // 🔒 Worker must be assigned
        $assigned = auth()->user()
            ->tasks()
            ->where('tasks.id', $task->id)
            ->exists();

        if (!$assigned) {
            abort(403, 'You are not assigned to this task.');
        }

        $validated = $request->validate([
            'body' => 'required|string|min:2|max:2000',
        ]);

        Comment::create([
            'task_id'          => $task->id,
            'user_id'          => auth()->id(),
            'body'             => $validated['body'],
            'is_read_by_admin' => false,
        ]);

        return back()->with('success', 'Your comment was sent to the admin.');
    }
}