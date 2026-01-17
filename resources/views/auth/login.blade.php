{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('content')
<div class="relative min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/HNU.jpg') }}');">
    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/55 to-blue-900/40"></div>

    <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-6xl items-center px-6 py-12">
        <div class="grid w-full grid-cols-1 gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="flex flex-col justify-center text-slate-100">
                <div class="inline-flex items-center gap-4">
                    <div class="h-16 w-16 rounded-2xl bg-white/10 p-2 backdrop-blur">
                        <img src="{{ asset('images/logo.png') }}" alt="HNU Logo" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-200/80">{{ __('auth.login.portal_label') }}</p>
                        <h1 class="text-3xl font-semibold text-white">{{ __('auth.login.university_name') }}</h1>
                    </div>
                </div>

                <p class="mt-6 max-w-xl text-lg text-slate-200">
                    {{ __('auth.login.hero_line') }}
                </p>

                <div class="mt-8 flex flex-wrap gap-4 text-sm text-slate-200/80">
                    <span class="rounded-full border border-white/20 px-4 py-1">{{ __('auth.login.badge_fast') }}</span>
                    <span class="rounded-full border border-white/20 px-4 py-1">{{ __('auth.login.badge_secure') }}</span>
                    <span class="rounded-full border border-white/20 px-4 py-1">{{ __('auth.login.badge_updates') }}</span>
                </div>
            </div>

            <div class="rounded-3xl bg-white/90 p-8 shadow-2xl backdrop-blur-xl sm:p-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">{{ __('auth.login.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('auth.login.subtitle') }}</p>
                    </div>
                    <div class="hidden h-14 w-14 rounded-2xl bg-blue-50 p-2 sm:block">
                        <img src="{{ asset('images/logo.png') }}" alt="HNU Logo" class="h-full w-full object-contain">
                    </div>
                </div>

                {{-- Make sure the action points to the correct named route --}}
                <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-slate-700">
                            {{ __('auth.login.student_id') }}
                        </label>
                        <div class="mt-2">
                            <input id="student_id" name="student_id" type="text" required
                                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   value="{{ old('student_id') }}">
                        </div>
                        @error('student_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">
                            {{ __('auth.login.password') }}
                        </label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required
                                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-200" {{ old('remember') ? 'checked' : '' }}>
                            {{ __('auth.login.remember') }}
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        {{ __('auth.login.sign_in') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
