<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tasks'    => Task::count(),
            'pending'        => Task::where('status', 'pending')->count(),
            'in_progress'    => Task::where('status', 'in_progress')->count(),
            'completed'      => Task::where('status', 'completed')->count(),
            'overdue'        => Task::where('status', 'overdue')->count(),
            'high_priority'  => Task::whereIn('priority', ['high', 'urgent'])->count(),
            'total_workers'  => User::where('role', 'worker')->count(),
            'total_categories' => Category::count(),
        ];

        // Recent tasks with assigned workers
        $recentTasks = Task::with(['category', 'assignedUsers', 'creator'])
            ->latest()
            ->take(8)
            ->get();

        // Worker productivity (task count per worker)
        $workers = User::where('role', 'worker')
            ->withCount('tasks')
            ->orderByDesc('tasks_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTasks', 'workers'));
    }
}