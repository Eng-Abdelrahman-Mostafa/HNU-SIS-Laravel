<?php

namespace App\Filament\Resources\CourseInstructorAssignmentResource\Pages;

use App\Filament\Resources\CourseInstructorAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseInstructorAssignments extends ListRecords
{
    protected static string $resource = CourseInstructorAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
