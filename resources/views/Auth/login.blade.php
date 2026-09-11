@extends('layouts.app')

@section('title', 'Login')
@section('page-title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-sm mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
        <i class="fas fa-sign-in-alt text-indigo-600 mr-2"></i>Sign In
    </h2>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                <span class="ml-2">Remember me</span>
            </label>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
            Login
        </button>

        <p class="text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register</a>
        </p>
    </form>
</div>
@endsection