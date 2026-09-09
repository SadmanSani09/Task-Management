<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes are provided by Breeze – we keep them.

Route::middleware(['auth'])->group(function () {

    // Dashboard for all authenticated users (shows different content based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        // Task CRUD
        Route::resource('tasks', TaskController::class)->except(['show']);

        // Category CRUD
        Route::resource('categories', CategoryController::class)->except(['show']);

        // User management (only workers list)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        // Optionally edit users
    });

    // Worker-specific: mark task as complete (or change status)
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'markComplete'])->name('tasks.complete');
    Route::patch('/tasks/{task}/progress', [TaskController::class, 'markInProgress'])->name('tasks.progress');
});

require __DIR__.'/auth.php';