@extends('layouts.admin')

@section('page-title', 'Create Category')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm p-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Create Category</h3>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Category Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2 text-gray-600 text-sm">Cancel</a>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm">Create</button>
        </div>
    </form>
</div>
@endsection