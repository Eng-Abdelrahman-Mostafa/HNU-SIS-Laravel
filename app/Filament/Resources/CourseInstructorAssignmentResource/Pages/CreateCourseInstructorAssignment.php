<?php

namespace App\Filament\Resources\CourseInstructorAssignmentResource\Pages;

use App\Filament\Resources\CourseInstructorAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseInstructorAssignment extends CreateRecord
{
    protected static string $resource = CourseInstructorAssignmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
