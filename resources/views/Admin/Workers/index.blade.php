@extends('layouts.admin')

@section('page-title', 'Workers Management')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">
            <i class="fas fa-users mr-2 text-indigo-500"></i>
            All Workers ({{ $workers->total() }})
        </h3>
        <a href="{{ route('admin.workers.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg">
            <i class="fas fa-user-plus mr-1"></i> Add Worker
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Assigned Tasks</th>
                    <th class="px-4 py-3 text-left">Registered</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workers as $w)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">#{{ $w->id }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($w->name,0,1)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $w->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $w->email }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-indigo-50 text-indigo-700 text-xs px-2 py-1 rounded-full font-semibold">
                            {{ $w->tasks_count }} task(s)
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $w->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.workers.edit', $w) }}"
                           class="text-indigo-600 hover:text-indigo-800 mr-3"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.workers.destroy', $w) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this worker? Their task assignments will be removed.')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400">
                    No workers registered. <a href="{{ route('admin.workers.create') }}" class="text-indigo-600 underline">Add one now →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t">{{ $workers->links() }}</div>
</div>
@endsection