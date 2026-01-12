<?php

namespace App\Filament\Resources\CourseInstructorAssignmentResource\Pages;

use App\Filament\Resources\CourseInstructorAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseInstructorAssignment extends EditRecord
{
    protected static string $resource = CourseInstructorAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
