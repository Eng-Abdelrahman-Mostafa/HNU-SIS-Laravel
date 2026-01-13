<x-filament-widgets::widget>
    <x-filament::section heading="{{ __('filament.dashboard.registration_window') }}">
        <div class="space-y-2 text-sm">
            <div>
                <span class="font-medium">{{ __('filament.dashboard.semester') }}:</span>
                <span>{{ $semesterLabel }}</span>
            </div>
            <div>
                <span class="font-medium">{{ __('filament.dashboard.starts') }}:</span>
                <span>{{ $startAt?->format('M j, Y g:i A') ?? __('filament.dashboard.not_set') }}</span>
            </div>
            <div>
                <span class="font-medium">{{ __('filament.dashboard.ends') }}:</span>
                <span>{{ $endAt?->format('M j, Y g:i A') ?? __('filament.dashboard.not_set') }}</span>
            </div>
            <div>
                <span class="font-medium">{{ __('filament.dashboard.open_now') }}:</span>
                <span>{{ $isOpen ? __('filament.dashboard.yes') : __('filament.dashboard.no') }}</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
