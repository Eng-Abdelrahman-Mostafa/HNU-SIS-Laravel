<?php

namespace App\Filament\Resources\ProgramRequirementResource\Pages;

use App\Filament\Resources\ProgramRequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProgramRequirement extends CreateRecord
{
    protected static string $resource = ProgramRequirementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
