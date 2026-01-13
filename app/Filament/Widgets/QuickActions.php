<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CourseResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\EnrollmentResource;
use App\Filament\Resources\SemesterResource;
use App\Filament\Resources\StudentResource;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected string $view = 'filament.widgets.quick-actions';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => __('filament.dashboard.actions.students'),
                    'description' => __('filament.dashboard.actions.students_description'),
                    'url' => StudentResource::getUrl('index'),
                    'icon' => Heroicon::Users,
                    'tone' => 'sky',
                ],
                [
                    'label' => __('filament.dashboard.actions.enrollments'),
                    'description' => __('filament.dashboard.actions.enrollments_description'),
                    'url' => EnrollmentResource::getUrl('index'),
                    'icon' => Heroicon::ClipboardDocumentList,
                    'tone' => 'emerald',
                ],
                [
                    'label' => __('filament.dashboard.actions.courses'),
                    'description' => __('filament.dashboard.actions.courses_description'),
                    'url' => CourseResource::getUrl('index'),
                    'icon' => Heroicon::BookOpen,
                    'tone' => 'amber',
                ],
                [
                    'label' => __('filament.dashboard.actions.semesters'),
                    'description' => __('filament.dashboard.actions.semesters_description'),
                    'url' => SemesterResource::getUrl('index'),
                    'icon' => Heroicon::CalendarDays,
                    'tone' => 'violet',
                ],
                [
                    'label' => __('filament.dashboard.actions.departments'),
                    'description' => __('filament.dashboard.actions.departments_description'),
                    'url' => DepartmentResource::getUrl('index'),
                    'icon' => Heroicon::BuildingOffice2,
                    'tone' => 'rose',
                ],
            ],
        ];
    }
}
