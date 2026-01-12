<?php

namespace App\Filament\Actions;

use App\Jobs\ProcessGradesBulkImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BulkImportGradesAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament.grades_import.title'))
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label(__('filament.grades_import.file_label'))
                    ->disk('local')
                    ->directory('imports/grades')
                    ->acceptedFileTypes([
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ])
                    ->required()
                    ->helperText(__('filament.grades_import.file_help'))
                    ->maxSize(10 * 1024), // 10MB
            ])
            ->action(function (array $data) {
                $this->handleImport($data);
            })
            ->modalHeading(__('filament.grades_import.title'))
            ->modalDescription(__('filament.grades_import.description'))
            ->modalSubmitActionLabel(__('filament.grades_import.submit_button'))
            ->modalCancelActionLabel(__('filament.grades_import.cancel_button'));
    }

    protected function handleImport(array $data): void
    {
        try {
            $filePath = $data['file'];

            if (!$filePath || !Storage::exists($filePath)) {
                Notification::make()
                    ->title(__('Error'))
                    ->body(__('filament.grades_import.file_not_found'))
                    ->danger()
                    ->send();
                return;
            }

            ProcessGradesBulkImport::dispatch(
                $filePath,
                auth()->id()
            );

            Notification::make()
                ->title(__('filament.grades_import.started'))
                ->body(__('filament.grades_import.started_message'))
                ->info()
                ->send();
        } catch (\Exception $e) {
            Log::error('Grades bulk import action error', [
                'error' => $e->getMessage(),
            ]);

            Notification::make()
                ->title(__('Error'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
