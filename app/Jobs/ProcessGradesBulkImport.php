<?php

namespace App\Jobs;

use App\Imports\GradesImport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ProcessGradesBulkImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected ?int $userId;

    public function __construct(string $filePath, ?int $userId = null)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        try {
            Log::info('Starting grades bulk import', [
                'file' => $this->filePath,
            ]);

            $import = new GradesImport();

            Excel::import($import, $this->filePath, disk: 'local');

            Log::info('Grades bulk import completed', [
                'enrollments_created' => $import->createdCount,
                'enrollments_updated' => $import->updatedCount,
                'grades_processed' => $import->gradesCount,
                'errors' => count($import->errors),
            ]);

            $this->sendNotification(
                $import->createdCount,
                $import->updatedCount,
                $import->gradesCount,
                $import->errors
            );

            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            Log::error('Grades bulk import failed', [
                'error' => $e->getMessage(),
                'file' => $this->filePath,
            ]);

            $this->sendErrorNotification($e->getMessage());
            Storage::delete($this->filePath);
        }
    }

    protected function sendNotification(
        int $created,
        int $updated,
        int $gradesCount,
        array $errors
    ): void {
        $title = __('filament.grades_import.completed');
        $message = __('filament.grades_import.completed_message', [
            'created' => $created,
            'updated' => $updated,
            'grades' => $gradesCount,
        ]);

        if (!empty($errors)) {
            $message .= "\n" . __('filament.grades_import.errors_count', [
                'count' => count($errors)
            ]);
        }

        try {
            if ($this->userId) {
                $user = User::find($this->userId);
                if ($user) {
                    Notification::make()
                        ->title($title)
                        ->body($message)
                        ->success()
                        ->sendToDatabase($user);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send grades import notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendErrorNotification(string $error): void
    {
        try {
            if ($this->userId) {
                $user = User::find($this->userId);
                if ($user) {
                    Notification::make()
                        ->title(__('filament.grades_import.failed'))
                        ->body($error)
                        ->danger()
                        ->sendToDatabase($user);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send grades import error notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
