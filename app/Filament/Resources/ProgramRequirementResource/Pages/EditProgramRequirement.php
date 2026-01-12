<?php

namespace App\Filament\Resources\ProgramRequirementResource\Pages;

use App\Filament\Resources\ProgramRequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgramRequirement extends EditRecord
{
    protected static string $resource = ProgramRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
