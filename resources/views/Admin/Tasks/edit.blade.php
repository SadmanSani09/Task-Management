@extends('layouts.admin')

@section('page-title', 'Edit Task')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm p-8">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Edit Task</h3>
            <p class="text-sm text-gray-500">
                Task ID: #{{ $task->id }} — Created {{ $task->created_at->diffForHumans() }}
            </p>
        </div>
        <a href="{{ route('admin.tasks.index') }}"
           class="text-sm text-indigo-600 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to list
        </a>
    </div>

    <form action="{{ route('admin.tasks.update', $task) }}" method="POST" id="update-task-form">
        @csrf
        @method('PUT')

        <div class="space-y-5">

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3"
                          class="mt-1 w-full border-gray-300 rounded-lg text-sm">{{ old('description', $task->description) }}</textarea>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" class="mt-1 w-full border-gray-300 rounded-lg text-sm">
                        @foreach(['low','medium','high','urgent'] as $p)
                            <option value="{{ $p }}" @selected(old('priority', $task->priority) == $p)>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $task->category_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deadline</label>
                    <input type="date" name="deadline"
                           value="{{ old('deadline', $task->deadline->format('Y-m-d')) }}"
                           class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 w-full border-gray-300 rounded-lg text-sm">
                        @foreach(['pending','in_progress','completed','overdue'] as $s)
                            <option value="{{ $s }}" @selected(old('status', $task->status) == $s)>
                                {{ str_replace('_', ' ', ucfirst($s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t pt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Assigned Workers
                    <span class="text-xs text-gray-400">(check to assign, uncheck to remove)</span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
                    @php $assignedIds = $task->assignedUsers->pluck('id')->toArray(); @endphp
                    @foreach($workers as $w)
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="worker_ids[]" value="{{ $w->id }}"
                               class="rounded text-indigo-600 focus:ring-indigo-500"
                               @checked(in_array($w->id, old('worker_ids', $assignedIds)))>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $w->name }}</p>
                            <p class="text-xs text-gray-400">{{ $w->email }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.tasks.index') }}"
                   class="px-5 py-2 text-gray-600 hover:text-gray-900 text-sm">Cancel</a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Update Task
                </button>
            </div>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t">
        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST"
              onsubmit="return confirm('Delete this task permanently? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-sm text-red-600 hover:text-red-800 transition">
                <i class="fas fa-trash mr-1"></i> Delete Task
            </button>
        </form>
    </div>

</div>
@endsection