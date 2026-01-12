<?php

namespace App\Filament\Resources\PrerequisiteResource\Pages;

use App\Filament\Resources\PrerequisiteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrerequisite extends EditRecord
{
    protected static string $resource = PrerequisiteResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
