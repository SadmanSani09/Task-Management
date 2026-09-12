@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="font-semibold text-gray-800"><i class="fas fa-tags mr-2 text-indigo-500"></i>Categories</h3>
        <a href="{{ route('admin.categories.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-1"></i> Add Category
        </a>
    </div>

    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Slug</th>
                <th class="px-4 py-3 text-left">Tasks</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($categories as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $c->slug }}</td>
                <td class="px-4 py-3">
                    <span class="bg-indigo-50 text-indigo-700 text-xs px-2 py-1 rounded-full">{{ $c->tasks_count }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.categories.edit', $c) }}"
                       class="text-indigo-600 hover:text-indigo-800 mr-3"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline"
                          onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection