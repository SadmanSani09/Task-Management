@extends('layouts.admin')

@section('page-title', 'Create & Assign Task')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm p-8">

    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-800">Create New Task</h3>
        <p class="text-sm text-gray-500 mt-1">Fill the form and <strong>manually assign</strong> the task to one or more workers.</p>
    </div>

    <form action="{{ route('admin.tasks.store') }}" method="POST">
        @csrf

        <div class="space-y-5">

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Task Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="mt-1 w-full border-gray-300 rounded-lg focus:ring-indigo-500 text-sm" required>
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3"
                          class="mt-1 w-full border-gray-300 rounded-lg focus:ring-indigo-500 text-sm">{{ old('description') }}</textarea>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" class="mt-1 w-full border-gray-300 rounded-lg text-sm">
                        @foreach(['low','medium','high','urgent'] as $p)
                            <option value="{{ $p }}" @selected(old('priority','medium')==$p)>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                        <option value="">-- Select --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deadline <span class="text-red-500">*</span></label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}"
                           class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="mt-1 w-full border-gray-300 rounded-lg text-sm">
                        @foreach(['pending','in_progress','completed','overdue'] as $s)
                            <option value="{{ $s }}" @selected(old('status','pending')==$s)>{{ str_replace('_',' ',ucfirst($s)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 🔥 Manual Worker Assignment --}}
            <div class="border-t pt-5">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Assign to Workers <span class="text-red-500">*</span>
                    </label>
                    <button type="button" onclick="selectAllWorkers()"
                            class="text-xs text-indigo-600 hover:underline">Select all</button>
                </div>

                @if($workers->isEmpty())
                    <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        No workers available. Please <a href="{{ route('admin.workers.create') }}" class="underline">create a worker</a> first.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @foreach($workers as $w)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="worker_ids[]" value="{{ $w->id }}"
                                   class="worker-checkbox rounded text-indigo-600 focus:ring-indigo-500"
                                   @checked(in_array($w->id, old('worker_ids', [])))>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $w->name }}</p>
                                <p class="text-xs text-gray-400">{{ $w->email }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('worker_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.tasks.index') }}"
                   class="px-5 py-2 text-gray-600 hover:text-gray-900 text-sm">Cancel</a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Create & Assign
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function selectAllWorkers() {
    const boxes = document.querySelectorAll('.worker-checkbox');
    const allChecked = Array.from(boxes).every(b => b.checked);
    boxes.forEach(b => b.checked = !allChecked);
}
</script>
@endsection