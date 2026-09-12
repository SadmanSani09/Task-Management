<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Worker Panel | Office Task Manager</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col">
        <div class="p-5 border-b flex items-center gap-3">
            <div class="bg-indigo-600 text-white w-10 h-10 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <p class="font-bold text-gray-800 leading-tight">Worker Panel</p>
                <p class="text-xs text-gray-400">Office Task Manager</p>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1 text-sm">
            <p class="px-3 pt-3 pb-1 text-xs uppercase text-gray-400 font-semibold">My Work</p>

            <a href="{{ route('worker.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('worker.dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                <i class="fas fa-th-large w-5"></i> Dashboard
            </a>

            <p class="px-3 pt-4 pb-1 text-xs uppercase text-gray-400 font-semibold">Account</p>
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-globe w-5"></i> View Public Site
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
                    <i class="fas fa-sign-out-alt w-5"></i> Logout
                </button>
            </form>
        </nav>

        <div class="p-4 border-t flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                {{ strtoupper(substr(auth()->user()->name ?? 'W', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 truncate font-medium">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-y-auto">

        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-600"><i class="fas fa-bars text-xl"></i></button>
                <h2 class="text-lg font-semibold text-gray-800">
                    @yield('page-title', 'Worker Dashboard')
                </h2>
            </div>

            <span class="hidden md:inline-block text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-semibold">
                <i class="fas fa-user-tie mr-1"></i> WORKER
            </span>
        </header>

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