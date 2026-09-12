@extends('layouts.admin')

@section('page-title', 'Task Details')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- ================= TASK HEADER ================= --}}
    <div class="bg-white rounded-2xl shadow-sm p-8 mb-5">

        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $task->title }}</h2>
                <p class="text-sm text-gray-400 mt-1">
                    Task #{{ $task->id }} •
                    Created by {{ $task->creator->name ?? 'Admin' }}
                    {{ $task->created_at->diffForHumans() }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.tasks.edit', $task) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('admin.tasks.index') }}"
                   class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            {{ $task->description ?: 'No description provided.' }}
        </p>

        {{-- Meta grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-5 border-t">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Category</p>
                <p class="font-medium text-gray-800 mt-1">{{ $task->category->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Priority</p>
                <p class="mt-1">
                    <span class="px-2 py-1 text-xs rounded-full font-medium
                        @if($task->priority == 'urgent') bg-red-100 text-red-700
                        @elseif($task->priority == 'high') bg-orange-100 text-orange-700
                        @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Deadline</p>
                <p class="font-medium text-gray-800 mt-1">
                    {{ $task->deadline->format('d M Y') }}
                    @if($task->deadline->isPast() && $task->status !== 'completed')
                        <span class="text-xs text-red-500 block">
                            <i class="fas fa-exclamation-circle"></i> Overdue
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Status</p>
                <p class="mt-1">
                    <span class="px-2 py-1 text-xs rounded-full font-medium
                        @if($task->status == 'completed') bg-green-100 text-green-700
                        @elseif($task->status == 'in_progress') bg-blue-100 text-blue-700
                        @elseif($task->status == 'overdue') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ str_replace('_', ' ', ucfirst($task->status)) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- ================= ASSIGNED WORKERS ================= --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-5">
        <h3 class="font-semibold text-gray-800 mb-4">
            <i class="fas fa-users mr-2 text-indigo-500"></i>
            Assigned Workers ({{ $task->assignedUsers->count() }})
        </h3>

        <div class="space-y-2">
            @forelse($task->assignedUsers as $u)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $u->name }}</p>
                        <p class="text-xs text-gray-400">{{ $u->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @php
                        $workerComments = $task->comments->where('user_id', $u->id)->count();
                    @endphp
                    @if($workerComments > 0)
                        <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full">
                            <i class="fas fa-comment"></i> {{ $workerComments }}
                        </span>
                    @endif

                    <form action="{{ route('admin.tasks.unassign', [$task, $u]) }}" method="POST"
                          onsubmit="return confirm('Remove this worker from the task?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 text-xs">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm">No workers assigned yet.</p>
            @endforelse
        </div>

        {{-- Quick assign form --}}
        @if($workers->whereNotIn('id', $task->assignedUsers->pluck('id'))->count() > 0)
        <form action="{{ route('admin.tasks.assign', $task) }}" method="POST"
              class="mt-4 pt-4 border-t flex gap-2">
            @csrf
            <select name="user_id" required
                    class="flex-1 border-gray-300 rounded-lg text-sm focus:ring-indigo-500">
                <option value="">-- Assign another worker --</option>
                @foreach($workers as $w)
                    @if(!$task->assignedUsers->contains($w->id))
                        <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->email }})</option>
                    @endif
                @endforeach
            </select>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap">
                <i class="fas fa-user-plus mr-1"></i> Assign
            </button>
        </form>
        @endif
    </div>

    {{-- ================= 💬 WORKER COMMENTS ================= --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">

        @php
            $totalComments = $task->comments->count();
            $unreadComments = $task->comments->where('is_read_by_admin', false)->count();
        @endphp

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">
                <i class="fas fa-comments mr-2 text-indigo-500"></i>
                Worker Comments ({{ $totalComments }})
                @if($unreadComments > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $unreadComments }} new
                    </span>
                @endif
            </h3>

            @if($unreadComments > 0)
                <form action="{{ route('admin.tasks.comments.read', $task) }}" method="POST">
                    @csrf
                    <button class="text-sm text-indigo-600 hover:underline">
                        <i class="fas fa-check-double mr-1"></i> Mark all as read
                    </button>
                </form>
            @endif
        </div>

        {{-- Comments thread --}}
        <div class="space-y-3">
            @forelse($task->comments as $comment)
                @php $isAdmin = $comment->user->role === 'admin'; @endphp
                <div class="p-4 rounded-lg border
                    {{ $isAdmin ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200' }}
                    {{ !$isAdmin && !$comment->is_read_by_admin ? 'ring-2 ring-red-100' : '' }}">

                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                {{ $isAdmin ? 'bg-yellow-500 text-white' : 'bg-indigo-600 text-white' }}">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $comment->user->name }}
                                    @if($isAdmin)
                                        <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full ml-1">Admin</span>
                                    @else
                                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full ml-1">Worker</span>
                                        @if(!$comment->is_read_by_admin)
                                            <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full ml-1">NEW</span>
                                        @endif
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $comment->user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400 whitespace-nowrap">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>

                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                  onsubmit="return confirm('Delete this comment?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-sm text-gray-700 whitespace-pre-line ml-10">{{ $comment->body }}</p>
                </div>
            @empty
                <div class="text-center py-10">
                    <i class="fas fa-comment-slash text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-400 text-sm">No comments from workers yet.</p>
                    <p class="text-xs text-gray-400 mt-1">Workers can post updates inside their task page.</p>
                </div>
            @endforelse
        </div>

        {{-- Admin reply --}}
        <form action="{{ route('admin.tasks.comments.store', $task) }}" method="POST"
              class="mt-6 pt-5 border-t">
            @csrf
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-reply mr-1 text-yellow-500"></i>
                Reply as Admin
            </label>
            <textarea name="body" rows="2" required maxlength="2000"
                      placeholder="Write a reply or instruction for the worker..."
                      class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('body') }}</textarea>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

            <div class="flex justify-end mt-2">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm">
                    <i class="fas fa-paper-plane mr-1"></i> Send Reply
                </button>
            </div>
        </form>
    </div>

</div>
@endsection