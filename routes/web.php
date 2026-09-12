<?php

use Illuminate\Support\Facades\Route;

// 🔐 Auth controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// 🛡️ Admin controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Controllers\Admin\AdminWorkerController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCommentController;

// 👷 Worker controllers
use App\Http\Controllers\Worker\WorkerDashboardController;
use App\Http\Controllers\Worker\WorkerTaskController;
use App\Http\Controllers\Worker\WorkerCommentController;

/*
|--------------------------------------------------------------------------
| 🌐 Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'))->name('home');

/*
|--------------------------------------------------------------------------
| 🔐 Auth Routes (guests only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',    [LoginController::class, 'login']);
    Route::get('/register',  [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| 👷 Worker Panel  (auth + role:worker)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:worker'])
    ->prefix('worker')
    ->name('worker.')
    ->group(function () {

        // 👇 Worker dashboard
        Route::get('/dashboard', [WorkerDashboardController::class, 'index'])
            ->name('dashboard');

        // Worker task actions
        Route::get('/tasks/{task}',            [WorkerTaskController::class, 'show'])->name('tasks.show');
        Route::patch('/tasks/{task}/progress', [WorkerTaskController::class, 'markInProgress'])->name('tasks.progress');
        Route::patch('/tasks/{task}/complete', [WorkerTaskController::class, 'markComplete'])->name('tasks.complete');

        // Worker comments
        Route::post('/tasks/{task}/comments', [WorkerCommentController::class, 'store'])->name('comments.store');
    });

/*
|--------------------------------------------------------------------------
| 🛡️ Admin Panel  (auth + role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 👇 Admin dashboard  →  admin.dashboard  ✅
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Tasks CRUD  →  admin.tasks.*
        Route::resource('tasks', AdminTaskController::class);

        // Extra task actions
        Route::patch('/tasks/{task}/quick-update',     [AdminTaskController::class, 'quickUpdate'])->name('tasks.quick-update');
        Route::post('/tasks/{task}/assign',            [AdminTaskController::class, 'assignWorker'])->name('tasks.assign');
        Route::delete('/tasks/{task}/unassign/{user}', [AdminTaskController::class, 'unassignWorker'])->name('tasks.unassign');

        // Workers CRUD  →  admin.workers.*
        Route::resource('workers', AdminWorkerController::class);

        // Categories CRUD  →  admin.categories.*
        Route::resource('categories', AdminCategoryController::class);

        // Admin comments
        Route::post('/tasks/{task}/comments/read', [AdminCommentController::class, 'markRead'])->name('tasks.comments.read');
        Route::post('/tasks/{task}/comments',      [AdminCommentController::class, 'store'])->name('tasks.comments.store');
        Route::delete('/comments/{comment}',       [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    });