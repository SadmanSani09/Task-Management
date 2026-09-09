<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Admin dashboard stats
            $totalTasks = Task::count();
            $completed = Task::where('status', 'completed')->count();
            $pending = Task::where('status', 'pending')->count();
            $inProgress = Task::where('status', 'in_progress')->count();
            $overdue = Task::where('status', 'overdue')->count();
            $workers = User::where('role', 'worker')->count();

            $tasks = Task::with(['category', 'assignedUsers', 'creator'])->latest()->take(10)->get();

            return view('dashboard', compact(
                'totalTasks', 'completed', 'pending', 'inProgress', 'overdue', 'workers', 'tasks'
            ));
        } else {
            // Worker dashboard – only their assigned tasks
            $tasks = $user->tasks()->with('category')->latest()->get();
            $total = $tasks->count();
            $pending = $tasks->where('status', 'pending')->count();
            $inProgress = $tasks->where('status', 'in_progress')->count();
            $completed = $tasks->where('status', 'completed')->count();
            $overdue = $tasks->where('status', 'overdue')->count();

            return view('dashboard', compact('tasks', 'total', 'pending', 'inProgress', 'completed', 'overdue'));
        }
    }
}