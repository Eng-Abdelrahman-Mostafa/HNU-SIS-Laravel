{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen py-12 -mt-16">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-center text-gray-900">
            Student Login
        </h2>

        {{-- Make sure the action points to the correct named route --}}
        <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">
                    Student ID
                </label>
                <div class="mt-1">
                    <input id="student_id" name="student_id" type="text" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('student_id') }}">
                </div>
                @error('student_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
