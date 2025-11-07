<?php

namespace App\Filament\Resources\AcademicLevelResource\Pages;

use App\Filament\Resources\AcademicLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademicLevels extends ListRecords
{
    protected static string $resource = AcademicLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
