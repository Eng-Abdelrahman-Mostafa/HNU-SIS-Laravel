<x-filament-widgets::widget>
    <x-filament::section
        heading="{{ __('filament.dashboard.quick_actions') }}"
        description="{{ __('filament.dashboard.quick_actions_description') }}"
    >
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($actions as $action)
                @php
                    $tone = $action['tone'] ?? 'primary';
                    $tones = [
                        'sky' => [
                            'bg' => 'bg-sky-50 dark:bg-sky-900/30',
                            'text' => 'text-sky-700 dark:text-sky-200',
                            'ring' => 'ring-sky-200/60 dark:ring-sky-900/40',
                        ],
                        'emerald' => [
                            'bg' => 'bg-emerald-50 dark:bg-emerald-900/30',
                            'text' => 'text-emerald-700 dark:text-emerald-200',
                            'ring' => 'ring-emerald-200/60 dark:ring-emerald-900/40',
                        ],
                        'amber' => [
                            'bg' => 'bg-amber-50 dark:bg-amber-900/30',
                            'text' => 'text-amber-700 dark:text-amber-200',
                            'ring' => 'ring-amber-200/60 dark:ring-amber-900/40',
                        ],
                        'violet' => [
                            'bg' => 'bg-violet-50 dark:bg-violet-900/30',
                            'text' => 'text-violet-700 dark:text-violet-200',
                            'ring' => 'ring-violet-200/60 dark:ring-violet-900/40',
                        ],
                        'rose' => [
                            'bg' => 'bg-rose-50 dark:bg-rose-900/30',
                            'text' => 'text-rose-700 dark:text-rose-200',
                            'ring' => 'ring-rose-200/60 dark:ring-rose-900/40',
                        ],
                        'primary' => [
                            'bg' => 'bg-primary-50 dark:bg-primary-900/30',
                            'text' => 'text-primary-700 dark:text-primary-200',
                            'ring' => 'ring-primary-200/60 dark:ring-primary-900/40',
                        ],
                    ];

                    $toneClasses = $tones[$tone] ?? $tones['primary'];
                @endphp
                <a
                    href="{{ $action['url'] }}"
                    class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl ring-1 {{ $toneClasses['bg'] }} {{ $toneClasses['text'] }} {{ $toneClasses['ring'] }}">
                        <x-filament::icon :icon="$action['icon']" class="h-6 w-6" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $action['label'] }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $action['description'] }}
                        </div>
                    </div>
                    <div class="text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-gray-400 dark:text-gray-600 dark:group-hover:text-gray-500">
                        <x-filament::icon :icon="\Filament\Support\Icons\Heroicon::ArrowRight" class="h-4 w-4" />
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
