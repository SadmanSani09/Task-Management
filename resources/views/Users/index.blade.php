@extends('layouts.app')

@section('page-title', 'Workers')
@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Workers Management</h2>
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workers as $worker)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $worker->id }}</td>
                    <td class="px-6 py-4 text-sm font-medium">{{ $worker->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $worker->email }}</td>
                    <td class="px-6 py-4 text-sm">
                        <form action="{{ route('users.destroy', $worker) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection