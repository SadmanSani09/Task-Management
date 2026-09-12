<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Office Task Manager')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen flex flex-col">

    {{-- ================== TOP NAVBAR ================== --}}
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 flex items-center gap-2">
                <i class="fas fa-tasks"></i> TaskFlow
            </a>


            {{-- Auth buttons --}}
            <div class="flex items-center gap-3">
                @auth
                    <span class="text-sm text-gray-500 hidden sm:block">
                        Hi, {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-600 hover:text-red-500 transition">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ================== PAGE CONTENT ================== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ================== FOOTER ================== --}}
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-6 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} Office Task Manager. All rights reserved.
        </div>
    </footer>

</body>
</html>