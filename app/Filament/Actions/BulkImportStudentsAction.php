<?php

namespace App\Filament\Actions;

use App\Jobs\ProcessStudentBulkImport;
use App\Models\Department;
use App\Models\AcademicLevel;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BulkImportStudentsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament.bulk_import.title'))
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                Select::make('department_id')
                    ->label(__('filament.bulk_import.department_label'))
                    ->options(fn() => Department::pluck('department_name', 'department_id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText(__('filament.bulk_import.department_help')),

                Select::make('current_level_id')
                    ->label(__('filament.bulk_import.level_label'))
                    ->options(fn() => AcademicLevel::pluck('level_name', 'level_id'))
                    ->searchable()
                    ->preload()
                    ->helperText(__('filament.bulk_import.level_help')),

                FileUpload::make('file')
                    ->label(__('filament.bulk_import.file_label'))
                    ->disk('local')
                    ->directory('imports/students')
                    ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->required()
                    ->helperText(__('filament.bulk_import.file_help'))
                    ->maxSize(10 * 1024), // 10MB max
            ])
            ->action(function (array $data) {
                $this->handleImport($data);
            })
            ->modalHeading(__('filament.bulk_import.title'))
            ->modalDescription(__('filament.bulk_import.description'))
            ->modalSubmitActionLabel(__('filament.bulk_import.submit_button'))
            ->modalCancelActionLabel(__('filament.bulk_import.cancel_button'));
    }

    protected function handleImport(array $data): void
    {
        try {
            // Filament's FileUpload already includes the directory path
            $filePath = $data['file'];

            if (!$filePath || !Storage::exists($filePath)) {
                Notification::make()
                    ->title(__('Error'))
                    ->body(__('filament.bulk_import.file_not_found'))
                    ->danger()
                    ->send();
                return;
            }

            // Dispatch the job to process the import
            ProcessStudentBulkImport::dispatch(
                $filePath,
                $data['department_id'],
                $data['current_level_id'] ?? null,
                auth()->id()
            );

            // Send immediate notification
            Notification::make()
                ->title(__('filament.bulk_import.started'))
                ->body(__('filament.bulk_import.started_message'))
                ->info()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Error'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
