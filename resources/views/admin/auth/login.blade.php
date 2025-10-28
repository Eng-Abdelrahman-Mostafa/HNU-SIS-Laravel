@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen py-12 -mt-16">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-center text-gray-900">
            Admin Login
        </h2>

        <form class="space-y-6" action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('email') }}">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

