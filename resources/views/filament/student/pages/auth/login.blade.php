@php
    $isRtl = app()->getLocale() === 'ar';
    $alignment = $isRtl ? 'text-right' : 'text-left';
@endphp

<div class="relative min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/HNU.jpg') }}');">
    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/55 to-emerald-900/40"></div>

    <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-6xl items-center px-6 py-12">
        <div class="grid w-full grid-cols-1 gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="flex flex-col justify-center text-slate-100 {{ $alignment }} {{ $isRtl ? 'items-end' : '' }}">
                <div class="inline-flex items-center gap-4 {{ $isRtl ? 'flex-row-reverse items-end' : '' }}">
                    <div class="h-16 w-16 rounded-2xl bg-white/10 p-2 backdrop-blur">
                        <img src="{{ asset('images/logo.png') }}" alt="HNU Logo" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-200/80">
                            {{ __('auth.login.portal_label') }}
                        </p>
                        <h1 class="text-3xl font-semibold text-white">
                            {{ __('auth.login.university_name') }}
                        </h1>
                    </div>
                </div>

                <p class="mt-6 max-w-xl text-lg text-slate-200">
                    {{ __('auth.login.hero_line') }}
                </p>

                <div class="mt-8 flex flex-wrap gap-4 text-sm text-slate-200/80 {{ $isRtl ? 'justify-end' : '' }}">
                    <span class="rounded-full border border-white/20 px-4 py-1">{{ __('auth.login.badge_fast') }}</span>
                    <span class="rounded-full border border-white/20 px-4 py-1">{{ __('auth.login.badge_secure') }}</span>
                    <span class="rounded-full border border-white/20 px-4 py-1">{{ __('auth.login.badge_updates') }}</span>
                </div>
            </div>

            <div class="rounded-3xl bg-white/90 p-8 shadow-2xl backdrop-blur-xl sm:p-10 {{ $alignment }}">
                <div class="flex items-center justify-between {{ $isRtl ? 'flex-row-reverse' : '' }}">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">{{ __('auth.login.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('auth.login.subtitle') }}</p>
                    </div>
                    <div class="hidden h-14 w-14 rounded-2xl bg-emerald-50 p-2 sm:block">
                        <img src="{{ asset('images/logo.png') }}" alt="HNU Logo" class="h-full w-full object-contain">
                    </div>
                </div>

                <div class="mt-8">
                    {{ $this->content }}
                </div>
            </div>
        </div>
    </div>
</div>
