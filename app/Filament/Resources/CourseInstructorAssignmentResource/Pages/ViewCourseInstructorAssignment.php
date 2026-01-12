<?php

namespace App\Filament\Resources\CourseInstructorAssignmentResource\Pages;

use App\Filament\Resources\CourseInstructorAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCourseInstructorAssignment extends ViewRecord
{
    protected static string $resource = CourseInstructorAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
