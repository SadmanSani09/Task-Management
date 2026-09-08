<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">
                    <i class="fas fa-tasks mr-2"></i>TaskFlow
                </a>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-50' : 'hover:bg-gray-100' }} rounded-lg transition">
                    <i class="fas fa-th-large w-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : '' }}"></i> Dashboard
                </a>
                @if(auth()->user() && auth()->user()->isAdmin())
                    <a href="{{ route('tasks.create') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-plus-circle w-5"></i> New Task
                    </a>
                    <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-tags w-5"></i> Categories
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-users w-5"></i> Workers
                    </a>
                @endif
                <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-list-check w-5"></i> Boards
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-comment-dots w-5"></i> Chat
                </a>
            </nav>
            <div class="p-4 border-t">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-gray-600"><i class="fas fa-bars text-xl"></i></button>
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-red-500 transition">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                            Register
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6 bg-gray-50 flex-1">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</body>
</html>