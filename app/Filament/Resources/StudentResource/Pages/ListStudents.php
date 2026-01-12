<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Filament\Actions\BulkImportStudentsAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BulkImportStudentsAction::make('bulkImportStudents'),
            Actions\CreateAction::make(),
        ];
    }
}
