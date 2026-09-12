<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminWorkerController extends Controller
{
    /* ---------- LIST WORKERS ---------- */
    public function index()
    {
        $workers = User::where('role', 'worker')
            ->withCount('tasks')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.workers.index', compact('workers'));
    }

    /* ---------- CREATE FORM ---------- */
    public function create()
    {
        return view('admin.workers.create');
    }

    /* ---------- STORE WORKER ---------- */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'worker',
        ]);

        return redirect()
            ->route('admin.workers.index')
            ->with('success', "Worker \"{$validated['name']}\" created successfully.");
    }

    /* ---------- EDIT FORM ---------- */
    public function edit(User $worker)
    {
        abort_if($worker->role !== 'worker', 404);
        return view('admin.workers.edit', compact('worker'));
    }

    /* ---------- UPDATE ---------- */
    public function update(Request $request, User $worker)
    {
        abort_if($worker->role !== 'worker', 404);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $worker->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $worker->update($data);

        return redirect()
            ->route('admin.workers.index')
            ->with('success', "Worker \"{$worker->name}\" updated.");
    }

    /* ---------- DELETE ---------- */
    public function destroy(User $worker)
    {
        abort_if($worker->role !== 'worker', 404);

        $name = $worker->name;

        // Detach all tasks before deleting
        $worker->tasks()->detach();
        $worker->delete();

        return redirect()
            ->route('admin.workers.index')
            ->with('success', "Worker \"{$name}\" deleted from database.");
    }
}