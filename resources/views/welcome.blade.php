@extends('layouts.app')

@section('title', 'Office Task Manager — Organize Your Team')

@section('content')

{{-- ================= HERO SECTION ================= --}}
<section class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-500 to-purple-600 text-white">
    {{-- Decorative background blobs --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-20 md:py-28">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            {{-- Left: Text --}}
            <div>
                <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wide">
                    <i class="fas fa-bolt text-yellow-300"></i>
                    Built for modern teams
                </span>

                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mt-6">
                    Manage Tasks.<br>
                    <span class="text-yellow-300">Ship Faster.</span>
                </h1>

                <p class="mt-6 text-lg text-indigo-100 max-w-lg leading-relaxed">
                    Assign tasks, track progress, and keep your entire team in sync — all from one beautiful dashboard.
                    No spreadsheets. No chaos. Just results.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="bg-white text-indigo-700 hover:bg-indigo-50 px-7 py-3.5 rounded-xl font-semibold shadow-lg transition flex items-center gap-2">
                        <i class="fas fa-rocket"></i> Get Started Free
                    </a>
                    <a href="{{ route('login') }}"
                       class="border-2 border-white/40 hover:bg-white/10 px-7 py-3.5 rounded-xl font-semibold transition flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                </div>

                <div class="mt-8 flex items-center gap-6 text-sm text-indigo-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-300"></i> Free forever
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-green-300"></i> Secure
                    </div>
                </div>
            </div>

            {{-- Right: Illustration --}}
            <div class="hidden md:flex justify-center">
                <div class="relative">
                    <div class="w-80 h-80 bg-white/10 rounded-3xl backdrop-blur-sm flex items-center justify-center border border-white/20 shadow-2xl">
                        <i class="fas fa-list-check text-white text-[10rem] opacity-90"></i>
                    </div>
                    {{-- Floating badges --}}
                    <div class="absolute -top-4 -left-4 bg-white text-indigo-700 rounded-xl px-4 py-2 shadow-lg font-semibold text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i> {{ $completedTasks }} Done
                    </div>
                    <div class="absolute -bottom-4 -right-4 bg-white text-indigo-700 rounded-xl px-4 py-2 shadow-lg font-semibold text-sm flex items-center gap-2">
                        <i class="fas fa-spinner text-blue-500"></i> {{ $inProgress }} In Progress
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= LIVE STATS ================= --}}
<section class="max-w-7xl mx-auto px-6 -mt-12 relative z-10">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100 overflow-hidden">

        {{-- Total Tasks --}}
        <div class="p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-list-check text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalTasks }}</p>
            <p class="text-xs text-gray-500 uppercase font-semibold mt-1">Total Tasks</p>
        </div>

        {{-- Completed --}}
        <div class="p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $completedTasks }}</p>
            <p class="text-xs text-gray-500 uppercase font-semibold mt-1">Completed</p>
        </div>

        {{-- In Progress --}}
        <div class="p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-spinner text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $inProgress }}</p>
            <p class="text-xs text-gray-500 uppercase font-semibold mt-1">In Progress</p>
        </div>

        {{-- Workers --}}
        <div class="p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-users text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalWorkers }}</p>
            <p class="text-xs text-gray-500 uppercase font-semibold mt-1">Active Workers</p>
        </div>
    </div>
</section>

{{-- ================= PROGRESS + OVERVIEW ================= --}}
<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid md:grid-cols-3 gap-6">

        {{-- Progress Card --}}
        <div class="md:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Team Progress</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($totalTasks > 0)
                            {{ $completedTasks }} of {{ $totalTasks }} tasks completed across the team.
                        @else
                            No tasks yet — new tasks will show up here in real time.
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-4xl font-extrabold text-indigo-600">{{ $completionRate }}%</p>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Complete</p>
                </div>
            </div>

            {{-- Big progress bar --}}
            <div class="w-full bg-gray-100 rounded-full h-4 mt-6 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-700"
                     style="width: {{ $completionRate }}%"></div>
            </div>

            {{-- Breakdown row --}}
            <div class="grid grid-cols-3 gap-4 mt-8">
                <div class="bg-yellow-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-yellow-700 text-xs font-semibold uppercase">
                        <i class="fas fa-clock"></i> Pending
                    </div>
                    <p class="text-2xl font-bold text-yellow-800 mt-1">{{ $pendingTasks }}</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-blue-700 text-xs font-semibold uppercase">
                        <i class="fas fa-spinner"></i> In Progress
                    </div>
                    <p class="text-2xl font-bold text-blue-800 mt-1">{{ $inProgress }}</p>
                </div>
                <div class="bg-red-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-red-700 text-xs font-semibold uppercase">
                        <i class="fas fa-exclamation-triangle"></i> Overdue
                    </div>
                    <p class="text-2xl font-bold text-red-800 mt-1">{{ $overdueTasks }}</p>
                </div>
            </div>
        </div>

        {{-- Quick info card --}}
        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-3xl shadow-lg p-8 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-tags text-xl"></i>
                </div>
                <h3 class="text-lg font-bold">Categories</h3>
                <p class="text-3xl font-extrabold mt-2">{{ $totalCategories }}</p>
                <p class="text-indigo-100 text-sm mt-2">
                    Organize tasks by team, project, or priority.
                </p>
            </div>
            <a href="{{ route('register') }}"
               class="mt-6 inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm px-4 py-2.5 rounded-xl text-sm font-semibold transition">
                Get Started <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- ================= FEATURES ================= --}}
<section class="bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-20">

        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                Everything your team needs
            </h2>
            <p class="text-gray-500 mt-3 text-lg">
                Simple enough for small teams. Powerful enough for growing ones.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="text-center p-6">
                <div class="w-16 h-16 mx-auto bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-user-shield text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Role-Based Access</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Admins manage everything. Workers focus on their tasks. Guests browse safely.
                </p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 mx-auto bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-tasks text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Task Assignment</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Create tasks, set priorities and deadlines, then assign them to specific workers.
                </p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 mx-auto bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-comments text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Live Comments</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Workers report progress inside each task. Admins see every update instantly.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ================= HOW IT WORKS ================= --}}
<section class="max-w-7xl mx-auto px-6 py-20">

    <div class="text-center max-w-2xl mx-auto mb-14">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">How it works</h2>
        <p class="text-gray-500 mt-3 text-lg">Three simple steps to get your team running.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 relative">

        {{-- Step 1 --}}
        <div class="relative bg-gray-50 rounded-2xl p-6">
            <div class="absolute -top-4 left-6 w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold shadow-lg">
                1
            </div>
            <div class="mt-4">
                <i class="fas fa-user-plus text-indigo-600 text-2xl"></i>
                <h3 class="font-bold text-lg text-gray-800 mt-3">Register</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Sign up as a worker and get instant access to your personal dashboard.
                </p>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="relative bg-gray-50 rounded-2xl p-6">
            <div class="absolute -top-4 left-6 w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold shadow-lg">
                2
            </div>
            <div class="mt-4">
                <i class="fas fa-clipboard-check text-indigo-600 text-2xl"></i>
                <h3 class="font-bold text-lg text-gray-800 mt-3">Receive Tasks</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Admin assigns tasks with priority, deadline, and category to you.
                </p>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="relative bg-gray-50 rounded-2xl p-6">
            <div class="absolute -top-4 left-6 w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold shadow-lg">
                3
            </div>
            <div class="mt-4">
                <i class="fas fa-flag-checkered text-indigo-600 text-2xl"></i>
                <h3 class="font-bold text-lg text-gray-800 mt-3">Get It Done</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Start the task, update status, add comments, and mark it complete.
                </p>
            </div>
        </div>

    </div>
</section>

{{-- ================= FINAL CTA ================= --}}
<section class="max-w-7xl mx-auto px-6 pb-20">
    <div class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 rounded-3xl p-10 md:p-16 text-center text-white">
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-indigo-500/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-purple-500/30 rounded-full blur-3xl"></div>

        <div class="relative">
            <h2 class="text-3xl md:text-4xl font-bold">Ready to get your team organized?</h2>
            <p class="text-indigo-200 mt-4 max-w-xl mx-auto">
                Join now and start managing tasks the modern way. Free forever for small teams.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}"
                   class="bg-white text-indigo-700 hover:bg-indigo-50 px-7 py-3.5 rounded-xl font-semibold shadow-lg transition flex items-center gap-2">
                    <i class="fas fa-rocket"></i> Create Free Account
                </a>
                <a href="{{ route('login') }}"
                   class="border-2 border-white/40 hover:bg-white/10 px-7 py-3.5 rounded-xl font-semibold transition flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            </div>
        </div>
    </div>
</section>

@endsection