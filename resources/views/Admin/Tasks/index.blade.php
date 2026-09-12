@extends('layouts.admin')

@section('page-title', 'All Tasks')

@section('content')

{{-- Filters --}}
<form method="GET" class="bg-white rounded-2xl shadow-sm p-4 mb-5 grid grid-cols-1 md:grid-cols-5 gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search title..."
           class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500">

    <select name="status" class="border-gray-300 rounded-lg text-sm">
        <option value="">All Status</option>
        @foreach(['pending','in_progress','completed','overdue'] as $s)
            <option value="{{ $s }}" @selected(request('status')==$s)>{{ str_replace('_',' ',ucfirst($s)) }}</option>
        @endforeach
    </select>

    <select name="priority" class="border-gray-300 rounded-lg text-sm">
        <option value="">All Priority</option>
        @foreach(['low','medium','high','urgent'] as $p)
            <option value="{{ $p }}" @selected(request('priority')==$p)>{{ ucfirst($p) }}</option>
        @endforeach
    </select>

    <select name="worker_id" class="border-gray-300 rounded-lg text-sm">
        <option value="">All Workers</option>
        @foreach($workers as $w)
            <option value="{{ $w->id }}" @selected(request('worker_id')==$w->id)>{{ $w->name }}</option>
        @endforeach
    </select>

    <div class="flex gap-2">
        <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm py-2">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.tasks.index') }}"
           class="flex-1 text-center border border-gray-300 hover:bg-gray-50 rounded-lg text-sm py-2">
            Reset
        </a>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">
            Tasks <span class="text-sm text-gray-400">({{ $tasks->total() }})</span>
        </h3>
        <a href="{{ route('admin.tasks.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-1"></i> New Task
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Task</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Priority</th>
                    <th class="px-4 py-3 text-left">Deadline</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Assigned Workers</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tasks.show', $task) }}"
                           class="font-medium text-gray-800 hover:text-indigo-600">
                            {{ $task->title }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $task->category->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($task->priority=='urgent') bg-red-100 text-red-700
                            @elseif($task->priority=='high') bg-orange-100 text-orange-700
                            @elseif($task->priority=='medium') bg-yellow-100 text-yellow-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $task->deadline->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($task->status=='completed') bg-green-100 text-green-700
                            @elseif($task->status=='in_progress') bg-blue-100 text-blue-700
                            @elseif($task->status=='overdue') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ str_replace('_',' ',ucfirst($task->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @forelse($task->assignedUsers as $u)
                            <span class="inline-block bg-indigo-50 text-indigo-700 text-xs px-2 py-0.5 rounded-full mb-1">
                                {{ $u->name }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-400">Unassigned</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.tasks.show', $task) }}"
                           class="text-gray-500 hover:text-gray-700 mr-2" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.tasks.edit', $task) }}"
                           class="text-indigo-600 hover:text-indigo-800 mr-2" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this task permanently?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400">No tasks found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t">{{ $tasks->links() }}</div>
</div>
@endsection