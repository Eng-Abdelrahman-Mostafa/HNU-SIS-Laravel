<?php

namespace App\Filament\Resources\PrerequisiteResource\Pages;

use App\Filament\Resources\PrerequisiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrerequisites extends ListRecords
{
    protected static string $resource = PrerequisiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
