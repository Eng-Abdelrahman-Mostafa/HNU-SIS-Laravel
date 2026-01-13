<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemStatsOverview extends StatsOverviewWidget
{
    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $activeSemester = Semester::query()
            ->where('is_active', true)
            ->first();

        $activeSemesterLabel = $activeSemester
            ? "{$activeSemester->semester_name} {$activeSemester->year}"
            : __('filament.dashboard.no_active_semester');

        $activeSemesterId = $activeSemester?->semester_id;

        $activeEnrollmentCount = $activeSemesterId
            ? Enrollment::query()
                ->where('semester_id', $activeSemesterId)
                ->count()
            : 0;

        $topStatus = Student::query()
            ->whereNotNull('status')
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->first();

        $topStatusLabel = __('filament.dashboard.not_available');

        if ($topStatus) {
            $statusKey = 'filament.status.' . $topStatus->status;
            $statusLabel = __($statusKey);

            if ($statusLabel === $statusKey) {
                $statusLabel = $topStatus->status;
            }

            $topStatusLabel = sprintf('%s (%d)', $statusLabel, $topStatus->total);
        }

        return [
            Stat::make(__('filament.dashboard.stats.total_students'), Student::query()->count()),
            Stat::make(__('filament.dashboard.stats.total_departments'), Department::query()->count()),
            Stat::make(__('filament.dashboard.stats.active_semester'), $activeSemesterLabel),
            Stat::make(__('filament.dashboard.stats.active_semester_enrollments'), $activeEnrollmentCount),
            Stat::make(__('filament.dashboard.stats.top_student_status'), $topStatusLabel),
        ];
    }
}
