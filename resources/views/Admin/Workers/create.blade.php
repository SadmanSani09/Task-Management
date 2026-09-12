@extends('layouts.admin')

@section('page-title', 'Add Worker')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-2xl shadow-sm p-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Add New Worker</h3>

    <form action="{{ route('admin.workers.store') }}" method="POST">
        @csrf
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.workers.index') }}" class="px-5 py-2 text-gray-600 hover:text-gray-900 text-sm">Cancel</a>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Create Worker
                </button>
            </div>
        </div>
    </form>
</div>
@endsection