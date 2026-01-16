@php
    $livewire ??= null;
    $isRtl = app()->getLocale() === 'ar';
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --ink: #0f172a;
            --emerald: #047857;
            --emerald-hover: #065f46;
        }

        body {
            font-family: 'Cairo', 'Tajawal', 'Georgia', serif;
            color: var(--ink);
        }

        .fi-simple-page-content {
            width: 100%;
        }

        .fi-fo-field-label {
            font-weight: 600;
            color: #334155;
        }

        .fi-input-wrp {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            background: #ffffff;
            padding: 0.75rem 1rem;
            box-shadow: 0 1px 1px rgba(15, 23, 42, 0.04);
        }

        .fi-input {
            background: transparent;
            border: none;
            padding: 0;
            color: #0f172a;
            font-size: 0.95rem;
        }

        .fi-input:focus {
            outline: none;
        }

        .fi-input-wrp:focus-within {
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .fi-checkbox-input {
            width: 1rem;
            height: 1rem;
            border-radius: 0.25rem;
            accent-color: #10b981;
        }

        .fi-btn {
            background: var(--emerald);
            color: #ffffff;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            width: 100%;
            justify-content: center;
        }

        .fi-btn:hover {
            background: var(--emerald-hover);
        }

        [dir="rtl"] .fi-fo-field-label,
        [dir="rtl"] .fi-input {
            text-align: right;
        }

        [dir="rtl"] .fi-fo-field-wrp {
            direction: rtl;
        }

        [dir="rtl"] .fi-fo-field-wrp .fi-fo-field-label-ctn {
            justify-content: flex-end;
        }
    </style>
    <div class="min-h-screen bg-slate-950" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        {{ $slot }}
    </div>
</x-filament-panels::layout.base>
