<?php

namespace App\Filament\Resources\AcademicLevelResource\Pages;

use App\Filament\Resources\AcademicLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAcademicLevel extends ViewRecord
{
    protected static string $resource = AcademicLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
