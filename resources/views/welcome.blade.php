@extends('layouts.app')

@section('page-title', 'Home')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Hero Section -->
    <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-2/3">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 leading-tight">
                    Daily Task Management
                </h1>
                <p class="text-gray-500 mt-3 text-lg max-w-2xl">
                    Quickly and easily set up new projects, assign and share tasks, add comments and notes,
                    share file library, send and receive notifications. All data is synchronized across all
                    your team members and devices.
                </p>
                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition shadow-md">
                        Get Started <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                        Discover Features
                    </a>
                </div>
            </div>
            <div class="md:w-1/3 mt-6 md:mt-0 flex justify-center">
                <div class="bg-indigo-100 p-6 rounded-full">
                    <i class="fas fa-tasks text-6xl text-indigo-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats & Dashboard Preview (Matches your image) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left Column: Sidebar preview -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-gray-400 text-sm font-medium">Dashboard</h3>
            <p class="text-xl font-bold text-gray-800 mt-1">Monday, 21 September 2020</p>
            <div class="mt-4 space-y-3">
                <div class="flex items-center text-gray-600"><i class="fas fa-briefcase w-6 text-indigo-500"></i> Workspace</div>
                <div class="flex items-center text-gray-600"><i class="fas fa-columns w-6 text-indigo-500"></i> Boards</div>
                <div class="flex items-center text-gray-600"><i class="fas fa-tasks w-6 text-indigo-500"></i> Tasks</div>
                <div class="flex items-center text-gray-600"><i class="fas fa-users w-6 text-indigo-500"></i> Meetings</div>
                <div class="flex items-center text-gray-600"><i class="fas fa-hourglass-half w-6 text-indigo-500"></i> Timesheets</div>
                <div class="flex items-center text-gray-600"><i class="fas fa-comments w-6 text-indigo-500"></i> Chat</div>
            </div>
        </div>

        <!-- Middle Column: Welcome Back / Progress -->
        <div class="bg-white rounded-2xl shadow-sm p-6 md:col-span-1">
            <h2 class="text-lg font-bold text-gray-800">Welcome Back, Jacob!</h2>
            <p class="text-gray-500 text-sm mt-1">You have 6 tasks to finish all tasks today.</p>
            <div class="mt-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Completed 50%</span>
                    <span>50%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 50%"></div>
                </div>
                <p class="text-xs text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> Your progress is very good.</p>
            </div>
            
            <!-- My Profile Stats -->
            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-400">Project Estimate</p>
                    <p class="text-xl font-bold text-gray-800">10</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-400">Total tasks</p>
                    <p class="text-xl font-bold text-gray-800">125</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Team Chat & Marketing -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800">Email marketing campaign</h3>
            <div class="flex flex-wrap gap-2 mt-3">
                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">Management</span>
                <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">Marketing</span>
                <span class="bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full">Human Resources</span>
            </div>
            
            <div class="mt-4 border-t pt-4">
                <h4 class="font-semibold text-gray-700 text-sm">Team Chat</h4>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div class="bg-green-50 p-2 rounded-lg text-center">
                        <p class="text-lg font-bold text-green-600">30%</p>
                        <p class="text-xs text-gray-500">Progress</p>
                    </div>
                    <div class="bg-yellow-50 p-2 rounded-lg text-center">
                        <p class="text-lg font-bold text-yellow-600">45%</p>
                        <p class="text-xs text-gray-500">Progress</p>
                    </div>
                    <div class="bg-red-50 p-2 rounded-lg text-center">
                        <p class="text-lg font-bold text-red-600">10+</p>
                        <p class="text-xs text-gray-500">Tasks</p>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg text-center">
                        <p class="text-lg font-bold text-blue-600">15+</p>
                        <p class="text-xs text-gray-500">Meetings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection