<?php

namespace App\Filament\Resources\ProgramRequirementResource\Pages;

use App\Filament\Resources\ProgramRequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramRequirement extends ViewRecord
{
    protected static string $resource = ProgramRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
