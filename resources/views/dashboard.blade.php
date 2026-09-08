@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('content')
<div class="max-w-7xl mx-auto">
    @if(auth()->user()->isAdmin())
        <!-- Admin stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-indigo-500">
                <p class="text-sm text-gray-500">Total Tasks</p>
                <p class="text-2xl font-bold">{{ $totalTasks }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Completed</p>
                <p class="text-2xl font-bold">{{ $completed }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-2xl font-bold">{{ $pending }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">In Progress</p>
                <p class="text-2xl font-bold">{{ $inProgress }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-red-500">
                <p class="text-sm text-gray-500">Overdue</p>
                <p class="text-2xl font-bold">{{ $overdue }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-8">
            <p><strong>Workers:</strong> {{ $workers }}</p>
        </div>
    @else
        <!-- Worker stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-indigo-500">
                <p class="text-sm text-gray-500">Total Assigned</p>
                <p class="text-2xl font-bold">{{ $total }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-2xl font-bold">{{ $pending }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">In Progress</p>
                <p class="text-2xl font-bold">{{ $inProgress }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Completed</p>
                <p class="text-2xl font-bold">{{ $completed }}</p>
            </div>
        </div>
    @endif

    <!-- Task Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Tasks</h3>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('tasks.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                    <i class="fas fa-plus mr-1"></i> Add Task
                </a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        @if(auth()->user()->isAdmin())
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        @else
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tasks as $task)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $task->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $task->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($task->priority == 'urgent') bg-red-100 text-red-800
                                @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $task->deadline->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($task->status == 'completed') bg-green-100 text-green-800
                                @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                @elseif($task->status == 'overdue') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ str_replace('_', ' ', ucfirst($task->status)) }}
                            </span>
                        </td>
                        @if(auth()->user()->isAdmin())
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @foreach($task->assignedUsers as $user)
                                    <span class="inline-block bg-gray-200 rounded-full px-2 py-0.5 text-xs">{{ $user->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        @else
                            <td class="px-6 py-4 text-sm">
                                @if($task->status == 'pending')
                                    <form action="{{ route('tasks.progress', $task) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs">Start</button>
                                    </form>
                                @elseif($task->status == 'in_progress')
                                    <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-xs">Complete</button>
                                    </form>
                                @elseif($task->status == 'completed')
                                    <span class="text-gray-400 text-xs">Done</span>
                                @else
                                    <span class="text-red-400 text-xs">Overdue</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-4 text-center text-gray-400">No tasks available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection