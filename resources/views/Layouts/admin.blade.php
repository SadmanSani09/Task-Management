<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel | Office Task Manager</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="w-64 bg-slate-900 text-slate-200 flex-shrink-0 hidden md:flex flex-col">
        <div class="p-5 border-b border-slate-700 flex items-center gap-3">
            <div class="bg-indigo-600 text-white w-10 h-10 rounded-lg flex items-center justify-center">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <p class="font-bold text-white leading-tight">Admin Panel</p>
                <p class="text-xs text-slate-400">Office Task Manager</p>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1 overflow-y-auto text-sm">

            <p class="px-3 pt-3 pb-1 text-xs uppercase text-slate-500 font-semibold">Main</p>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">
                <i class="fas fa-chart-line w-5"></i> Dashboard
            </a>

            <p class="px-3 pt-4 pb-1 text-xs uppercase text-slate-500 font-semibold">Management</p>
            <a href="{{ route('admin.tasks.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.tasks.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">
                <i class="fas fa-list-check w-5"></i> All Tasks
            </a>
            <a href="{{ route('admin.tasks.create') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-slate-800">
                <i class="fas fa-plus-circle w-5"></i> Create Task
            </a>
            <a href="{{ route('admin.workers.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.workers.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">
                <i class="fas fa-users w-5"></i> Workers
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">
                <i class="fas fa-tags w-5"></i> Categories
            </a>

            <p class="px-3 pt-4 pb-1 text-xs uppercase text-slate-500 font-semibold">System</p>
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                <i class="fas fa-globe w-5"></i> View Public Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-600 transition text-left">
                    <i class="fas fa-sign-out-alt w-5"></i> Logout
                </button>
            </form>
        </nav>

        <div class="p-4 border-t border-slate-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </aside>

    {{-- ================= MAIN ================= --}}
    <div class="flex-1 flex flex-col overflow-y-auto">

        {{-- Top bar --}}
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-600"><i class="fas fa-bars text-xl"></i></button>
                <h2 class="text-lg font-semibold text-gray-800">
                    @yield('page-title', 'Admin Dashboard')
                </h2>
            </div>

            <div class="flex items-center gap-4">
                <span class="hidden md:inline-block text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-semibold">
                    <i class="fas fa-shield-alt mr-1"></i> ADMIN
                </span>
                <a href="{{ route('admin.tasks.create') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-1"></i> New Task
                </a>
            </div>
        </header>

        {{-- Flash messages --}}
        <main class="p-6 flex-1">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-lg flex items-center justify-between">
                    <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg flex items-center justify-between">
                    <span><i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>