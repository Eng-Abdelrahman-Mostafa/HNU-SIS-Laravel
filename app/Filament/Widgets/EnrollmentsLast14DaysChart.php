<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class EnrollmentsLast14DaysChart extends ChartWidget
{

    protected function getData(): array
    {
        $startDate = now()->subDays(13)->startOfDay();
        $endDate = now()->endOfDay();

        $enrollments = Enrollment::query()
            ->whereDate('enrollment_date', '>=', $startDate->toDateString())
            ->whereDate('enrollment_date', '<=', $endDate->toDateString())
            ->selectRaw('DATE(enrollment_date) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $data = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $day = $date->toDateString();
            $labels[] = $date->format('M j');
            $data[] = (int) ($enrollments[$day]->total ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Enrollments',
                    'data' => $data,
                    'borderWidth' => 2,
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): ?string
    {
        return __('filament.dashboard.enrollments_last_14_days');
    }
}
