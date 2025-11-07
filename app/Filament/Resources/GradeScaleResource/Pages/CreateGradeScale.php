<?php

namespace App\Filament\Resources\GradeScaleResource\Pages;

use App\Filament\Resources\GradeScaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGradeScale extends CreateRecord
{
    protected static string $resource = GradeScaleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
