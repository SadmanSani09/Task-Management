<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkerDashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();

        // Load worker's tasks with category + comments
        $tasks = $user->tasks()
            ->with(['category', 'comments'])
            ->latest()
            ->get();

        $total      = $tasks->count();
        $pending    = $tasks->where('status', 'pending')->count();
        $inProgress = $tasks->where('status', 'in_progress')->count();
        $completed  = $tasks->where('status', 'completed')->count();
        $overdue    = $tasks->where('status', 'overdue')->count();

        return view('worker.dashboard', compact(
            'tasks', 'total', 'pending', 'inProgress', 'completed', 'overdue'
        ));
    }
}