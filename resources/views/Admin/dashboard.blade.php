@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-indigo-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">Total Tasks</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_tasks'] }}</p>
        <i class="fas fa-list-check text-indigo-400 opacity-40 float-right -mt-8 text-2xl"></i>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-yellow-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">Pending</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['pending'] }}</p>
        <i class="fas fa-clock text-yellow-400 opacity-40 float-right -mt-8 text-2xl"></i>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-blue-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">In Progress</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['in_progress'] }}</p>
        <i class="fas fa-spinner text-blue-400 opacity-40 float-right -mt-8 text-2xl"></i>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-green-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">Completed</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['completed'] }}</p>
        <i class="fas fa-check-circle text-green-400 opacity-40 float-right -mt-8 text-2xl"></i>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-red-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">Overdue</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['overdue'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-orange-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">High Priority</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['high_priority'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-purple-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">Workers</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_workers'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-pink-500">
        <p class="text-xs uppercase text-gray-400 font-semibold">Categories</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_categories'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Recent tasks --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800"><i class="fas fa-clock-rotate-left mr-2 text-indigo-500"></i>Recent Tasks</h3>
            <a href="{{ route('admin.tasks.index') }}" class="text-sm text-indigo-600 hover:underline">View all →</a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Task</th>
                    <th class="px-4 py-3 text-left">Priority</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Workers</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentTasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                            {{ $task->title }}
                        </a>
                        <div class="text-xs text-gray-400">{{ $task->category->name ?? '—' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($task->priority == 'urgent') bg-red-100 text-red-700
                            @elseif($task->priority == 'high') bg-orange-100 text-orange-700
                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($task->status == 'completed') bg-green-100 text-green-700
                            @elseif($task->status == 'in_progress') bg-blue-100 text-blue-700
                            @elseif($task->status == 'overdue') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ str_replace('_',' ',ucfirst($task->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @forelse($task->assignedUsers as $u)
                            <span class="inline-block bg-indigo-50 text-indigo-700 text-xs px-2 py-0.5 rounded-full">
                                {{ $u->name }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-400">Unassigned</span>
                        @endforelse
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">No tasks yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Top workers --}}
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">
            <i class="fas fa-trophy mr-2 text-yellow-500"></i>Top Workers
        </h3>
        <ul class="space-y-3">
            @forelse($workers as $w)
            <li class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                    {{ strtoupper(substr($w->name,0,1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $w->name }}</p>
                    <p class="text-xs text-gray-400">{{ $w->email }}</p>
                </div>
                <span class="text-sm font-bold text-indigo-600">{{ $w->tasks_count }}</span>
            </li>
            @empty
            <li class="text-center text-gray-400 text-sm py-4">No workers yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection