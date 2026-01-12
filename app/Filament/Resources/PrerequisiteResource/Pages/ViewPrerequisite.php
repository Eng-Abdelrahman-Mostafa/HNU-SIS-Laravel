<?php

namespace App\Filament\Resources\PrerequisiteResource\Pages;

use App\Filament\Resources\PrerequisiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPrerequisite extends ViewRecord
{
    protected static string $resource = PrerequisiteResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
