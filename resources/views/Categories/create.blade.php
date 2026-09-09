@extends('layouts.app')

@section('page-title', 'Create Category')
@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-sm">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Category</h2>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Category Name</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('categories.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Create</button>
        </div>
    </form>
</div>
@endsection