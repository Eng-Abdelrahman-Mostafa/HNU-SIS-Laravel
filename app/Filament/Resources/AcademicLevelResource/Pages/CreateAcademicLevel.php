<?php

namespace App\Filament\Resources\AcademicLevelResource\Pages;

use App\Filament\Resources\AcademicLevelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAcademicLevel extends CreateRecord
{
    protected static string $resource = AcademicLevelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
