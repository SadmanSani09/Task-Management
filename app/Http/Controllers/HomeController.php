<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $totalTasks     = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $pendingTasks   = Task::where('status', 'pending')->count();
        $inProgress     = Task::where('status', 'in_progress')->count();
        $overdueTasks   = Task::where('status', 'overdue')->count();
        $totalWorkers   = User::where('role', 'worker')->count();
        $totalCategories= Category::count();

        // Completion rate (0–100)
        $completionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        return view('welcome', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'inProgress',
            'overdueTasks',
            'totalWorkers',
            'totalCategories',
            'completionRate'
        ));
    }
}