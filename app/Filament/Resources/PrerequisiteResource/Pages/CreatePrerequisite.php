<?php

namespace App\Filament\Resources\PrerequisiteResource\Pages;

use App\Filament\Resources\PrerequisiteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePrerequisite extends CreateRecord
{
    protected static string $resource = PrerequisiteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
