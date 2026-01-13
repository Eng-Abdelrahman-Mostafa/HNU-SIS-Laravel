<?php

namespace App\Filament\Widgets;

use App\Models\Semester;
use Filament\Widgets\Widget;

class RegistrationWindowInfo extends Widget
{
    protected string $view = 'filament.widgets.registration-window-info';

    protected function getViewData(): array
    {
        $activeSemester = Semester::query()
            ->where('is_active', true)
            ->first();

        $semesterLabel = $activeSemester
            ? "{$activeSemester->semester_name} {$activeSemester->year}"
            : __('filament.dashboard.no_active_semester');

        $startAt = $activeSemester?->student_registeration_start_at;
        $endAt = $activeSemester?->student_registeration_end_at;

        $isOpen = $startAt && $endAt && now()->between($startAt, $endAt);

        return [
            'semesterLabel' => $semesterLabel,
            'startAt' => $startAt,
            'endAt' => $endAt,
            'isOpen' => $isOpen,
        ];
    }
}
