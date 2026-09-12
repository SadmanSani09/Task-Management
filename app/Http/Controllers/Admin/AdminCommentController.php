<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    /** Mark all comments on a task as read by admin. */
    public function markRead(Task $task)
    {
        $task->comments()->update(['is_read_by_admin' => true]);

        return back()->with('success', 'All comments marked as read.');
    }

    /** Admin posts a reply comment. */
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:2|max:2000',
        ]);

        Comment::create([
            'task_id'          => $task->id,
            'user_id'          => auth()->id(),
            'body'             => $validated['body'],
            'is_read_by_admin' => true,
        ]);

        return back()->with('success', 'Reply added.');
    }

    /** Delete a comment. */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}