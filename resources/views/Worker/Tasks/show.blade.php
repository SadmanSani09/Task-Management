@extends('layouts.app')

@section('page-title', 'Task Details')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Task Header --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-5">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $task->title }}</h2>
                <p class="text-sm text-gray-400 mt-1">
                    Task #{{ $task->id }} • Assigned by {{ $task->creator->name ?? 'Admin' }}
                    {{ $task->created_at->diffForHumans() }}
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-indigo-600 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        <p class="text-gray-600 leading-relaxed">
            {{ $task->description ?: 'No description provided.' }}
        </p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-sm">
            <div>
                <p class="text-xs text-gray-400 uppercase">Category</p>
                <p class="font-medium text-gray-800">{{ $task->category->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Priority</p>
                <p class="font-medium text-gray-800">{{ ucfirst($task->priority) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Deadline</p>
                <p class="font-medium text-gray-800">{{ $task->deadline->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Status</p>
                <p class="font-medium text-gray-800">{{ str_replace('_',' ',ucfirst($task->status)) }}</p>
            </div>
        </div>

        {{-- Quick status buttons --}}
        <div class="mt-6 pt-4 border-t flex gap-3">
            @if($task->status === 'pending')
                <form action="{{ route('tasks.progress', $task) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-play mr-1"></i> Start Task
                    </button>
                </form>
            @elseif($task->status === 'in_progress')
                <form action="{{ route('tasks.complete', $task) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-check mr-1"></i> Mark as Completed
                    </button>
                </form>
            @elseif($task->status === 'completed')
                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium">
                    <i class="fas fa-check-circle mr-1"></i> Completed
                </span>
            @endif
        </div>
    </div>

    {{-- 💬 Comments Section --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">
            <i class="fas fa-comments mr-2 text-indigo-500"></i>
            Comments to Admin ({{ $task->comments->count() }})
        </h3>

        <p class="text-xs text-gray-400 mb-4">
            <i class="fas fa-info-circle mr-1"></i>
            Your comments are visible <strong>only to the Admin</strong>. Use this section to report progress, blockers, or ask questions.
        </p>

        {{-- Comment form --}}
        <form action="{{ route('worker.comments.store', $task) }}" method="POST" class="mb-6">
            @csrf
            <textarea name="body" rows="3" required maxlength="2000"
                      placeholder="Write your update for the admin..."
                      class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('body') }}</textarea>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

            <div class="flex justify-end mt-2">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Comment
                </button>
            </div>
        </form>

        {{-- Comments list (worker sees own + admin replies) --}}
        <div class="space-y-3">
            @forelse($task->comments as $comment)
            <div class="p-4 rounded-lg border
                {{ $comment->user_id === auth()->id() ? 'bg-indigo-50 border-indigo-200' : 'bg-yellow-50 border-yellow-200' }}">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $comment->user_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-yellow-500 text-white' }}">
                            {{ strtoupper(substr($comment->user->name,0,1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $comment->user_id === auth()->id() ? 'You' : 'Admin' }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $comment->body }}</p>
            </div>
            @empty
            <p class="text-center text-gray-400 text-sm py-6">
                No comments yet. Submit your first update above.
            </p>
            @endforelse
        </div>
    </div>
</div>
@endsection