@extends('layouts.admin')

@section('page-title', 'Edit Worker')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-2xl shadow-sm p-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Worker: {{ $worker->name }}</h3>

    <form action="{{ route('admin.workers.update', $worker) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $worker->name) }}"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $worker->email) }}"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    New Password <span class="text-xs text-gray-400">(leave blank to keep current)</span>
                </label>
                <input type="password" name="password"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                       class="mt-1 w-full border-gray-300 rounded-lg text-sm">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.workers.index') }}" class="px-5 py-2 text-gray-600 text-sm">Cancel</a>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Update Worker
                </button>
            </div>
        </div>
    </form>
</div>
@endsection