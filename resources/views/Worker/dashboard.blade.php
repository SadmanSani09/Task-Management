@extends('layouts.worker')

@section('page-title', 'My Dashboard')

@section('content')

{{-- ================= WELCOME BANNER ================= --}}
<div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-2xl shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-indigo-100 mt-1 text-sm">
                @if($pending > 0)
                    You have <strong>{{ $pending }}</strong> pending task{{ $pending > 1 ? 's' : '' }} to finish today.
                @elseif($inProgress > 0)
                    You have <strong>{{ $inProgress }}</strong> task{{ $inProgress > 1 ? 's' : '' }} in progress. Keep going!
                @else
                    You're all caught up. Nice work! 🎉
                @endif
            </p>
        </div>

        @php
            $completionRate = $total > 0 ? round(($completed / $total) * 100) : 0;
        @endphp
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3 text-center">
            <p class="text-xs text-indigo-100 uppercase">Completion Rate</p>
            <p class="text-3xl font-bold">{{ $completionRate }}%</p>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="mt-4">
        <div class="w-full bg-white/20 rounded-full h-2.5">
            <div class="bg-white h-2.5 rounded-full transition-all"
                 style="width: {{ $completionRate }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-indigo-100 mt-1">
            <span>{{ $completed }} of {{ $total }} tasks completed</span>
            <span>{{ $completionRate }}%</span>
        </div>
    </div>
</div>

{{-- ================= STAT CARDS ================= --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold">Total Assigned</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $total }}</p>
            </div>
            <i class="fas fa-clipboard-list text-3xl text-indigo-400 opacity-40"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold">Pending</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pending }}</p>
            </div>
            <i class="fas fa-clock text-3xl text-yellow-400 opacity-40"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold">In Progress</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $inProgress }}</p>
            </div>
            <i class="fas fa-spinner text-3xl text-blue-400 opacity-40"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-gray-400 font-semibold">Completed</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $completed }}</p>
            </div>
            <i class="fas fa-check-circle text-3xl text-green-400 opacity-40"></i>
        </div>
    </div>
</div>

{{-- Overdue alert (if any) --}}
@if($overdue > 0)
<div class="bg-red-50 border-l-4 border-red-500 text-red-800 rounded-2xl p-4 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <i class="fas fa-exclamation-triangle text-2xl"></i>
        <div>
            <p class="font-semibold">You have {{ $overdue }} overdue task{{ $overdue > 1 ? 's' : '' }}!</p>
            <p class="text-sm text-red-600">Please prioritize these tasks or contact the admin.</p>
        </div>
    </div>
</div>
@endif

{{-- ================= TASK LIST ================= --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">
            <i class="fas fa-list-check mr-2 text-indigo-500"></i>
            My Assigned Tasks
        </h3>
        <span class="text-xs text-gray-400">{{ $tasks->count() }} task(s)</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3 text-left">Task</th>
                    <th class="px-5 py-3 text-left">Category</th>
                    <th class="px-5 py-3 text-left">Priority</th>
                    <th class="px-5 py-3 text-left">Deadline</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50 transition">
                    {{-- Title (link to detail) --}}
                    <td class="px-5 py-4">
                        <a href="{{ route('worker.tasks.show', $task) }}"
                           class="font-medium text-gray-800 hover:text-indigo-600 hover:underline flex items-center gap-2">
                            {{ $task->title }}
                            @php
                                $myCommentCount = $task->comments->where('user_id', auth()->id())->count();
                            @endphp
                            @if($myCommentCount > 0)
                                <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full">
                                    <i class="fas fa-comment"></i> {{ $myCommentCount }}
                                </span>
                            @endif
                        </a>
                    </td>

                    {{-- Category --}}
                    <td class="px-5 py-4 text-gray-600">
                        {{ $task->category->name ?? '—' }}
                    </td>

                    {{-- Priority --}}
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($task->priority == 'urgent') bg-red-100 text-red-700
                            @elseif($task->priority == 'high') bg-orange-100 text-orange-700
                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>

                    {{-- Deadline --}}
                    <td class="px-5 py-4 text-gray-600">
                        <div>{{ $task->deadline->format('d M Y') }}</div>
                        @if($task->deadline->isPast() && $task->status !== 'completed')
                            <span class="text-xs text-red-500 font-medium">
                                <i class="fas fa-exclamation-circle"></i> Overdue
                            </span>
                        @elseif($task->deadline->isToday())
                            <span class="text-xs text-yellow-600 font-medium">
                                <i class="fas fa-clock"></i> Due today
                            </span>
                        @elseif($task->deadline->diffInDays(now()) <= 2)
                            <span class="text-xs text-orange-500 font-medium">
                                <i class="fas fa-hourglass-half"></i> Due soon
                            </span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($task->status == 'completed') bg-green-100 text-green-700
                            @elseif($task->status == 'in_progress') bg-blue-100 text-blue-700
                            @elseif($task->status == 'overdue') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ str_replace('_', ' ', ucfirst($task->status)) }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        @if($task->status == 'pending')
                            <form action="{{ route('worker.tasks.progress', $task) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-play mr-1"></i> Start
                                </button>
                            </form>
                        @elseif($task->status == 'in_progress')
                            <form action="{{ route('worker.tasks.complete', $task) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-check mr-1"></i> Complete
                                </button>
                            </form>
                        @elseif($task->status == 'completed')
                            <span class="text-xs text-green-600 font-medium">
                                <i class="fas fa-check-circle mr-1"></i> Done
                            </span>
                        @else
                            <span class="text-xs text-red-500 font-medium">
                                <i class="fas fa-exclamation-circle mr-1"></i> Overdue
                            </span>
                        @endif

                        <a href="{{ route('worker.tasks.show', $task) }}"
                           class="text-indigo-600 hover:text-indigo-800 text-xs ml-2"
                           title="View & Comment">
                            <i class="fas fa-comment"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3 block opacity-50"></i>
                        <p class="text-sm">No tasks assigned yet.</p>
                        <p class="text-xs mt-1">The admin will assign tasks to you soon.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection