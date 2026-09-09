@extends('layouts.app')

@section('page-title', 'Edit Task')
@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Task</h2>
    
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Task Title</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" 
                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" 
                          class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500">
                        @foreach(['low','medium','high','urgent'] as $p)
                            <option value="{{ $p }}" {{ old('priority', $task->priority) == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', $task->deadline->format('Y-m-d')) }}" 
                           class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500">
                    @foreach(['pending','in_progress','completed','overdue'] as $s)
                        <option value="{{ $s }}" {{ old('status', $task->status) == $s ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Assign to Workers</label>
                <select name="user_ids[]" multiple class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 h-32">
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}" 
                            {{ $task->assignedUsers->contains($worker->id) ? 'selected' : '' }}>
                            {{ $worker->name }} ({{ $worker->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Hold Ctrl (Cmd) to select multiple.</p>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    <i class="fas fa-save mr-1"></i> Update Task
                </button>
            </div>
        </div>
    </form>
</div>
@endsection