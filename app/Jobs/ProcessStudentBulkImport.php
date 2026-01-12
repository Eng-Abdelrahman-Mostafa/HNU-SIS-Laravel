<?php

namespace App\Jobs;

use App\Imports\StudentsImport;
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

class ProcessStudentBulkImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected string $departmentId;
    protected ?int $currentLevelId;
    protected ?int $userId;

    public function __construct(string $filePath, string $departmentId, ?int $currentLevelId = null, ?int $userId = null)
    {
        $this->filePath = $filePath;
        $this->departmentId = $departmentId;
        $this->currentLevelId = $currentLevelId;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        try {
            Log::info('Starting student bulk import', [
                'file' => $this->filePath,
                'department_id' => $this->departmentId,
            ]);

            // Create import instance
            $import = new StudentsImport($this->departmentId, $this->currentLevelId);

            // Load and process the Excel file
            Excel::import($import, $this->filePath, disk: 'local');

            // Get statistics
            $totalAffected = $import->createdCount + $import->updatedCount;

            // Log the results
            Log::info('Student bulk import completed', [
                'created' => $import->createdCount,
                'updated' => $import->updatedCount,
                'errors' => count($import->errors),
            ]);

            // Send notification to user
            $this->sendNotification($import->createdCount, $import->updatedCount, $import->errors);

            // Clean up the uploaded file
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            Log::error('Student bulk import failed', [
                'error' => $e->getMessage(),
                'file' => $this->filePath,
            ]);

            // Send error notification
            $this->sendErrorNotification($e->getMessage());

            // Clean up the uploaded file even on error
            Storage::delete($this->filePath);
        }
    }

    protected function sendNotification(int $created, int $updated, array $errors): void
    {
        $title = __('filament.bulk_import.completed');
        $message = __('filament.bulk_import.completed_message', ['created' => $created, 'updated' => $updated]);

        if (!empty($errors)) {
            $message .= "\n" . __('filament.bulk_import.errors_count', ['count' => count($errors)]);
        }

        try {
            // If we have a specific user ID, send to that user
            if ($this->userId) {
                $user = User::find($this->userId);
                if ($user) {
                    Notification::make()
                        ->title($title)
                        ->body($message)
                        ->success()
                        ->sendToDatabase($user);
                }
            } else {
                // Send to all admin users
                $adminUsers = User::query()
                    ->where('id', '>', 0) // Get all users as fallback
                    ->limit(50)
                    ->get();

                foreach ($adminUsers as $user) {
                    Notification::make()
                        ->title($title)
                        ->body($message)
                        ->success()
                        ->sendToDatabase($user);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send bulk import notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendErrorNotification(string $error): void
    {
        try {
            // If we have a specific user ID, send to that user
            if ($this->userId) {
                $user = User::find($this->userId);
                if ($user) {
                    Notification::make()
                        ->title(__('filament.bulk_import.failed'))
                        ->body($error)
                        ->danger()
                        ->sendToDatabase($user);
                }
            } else {
                // Send to all admin users
                $adminUsers = User::query()
                    ->where('id', '>', 0)
                    ->limit(50)
                    ->get();

                foreach ($adminUsers as $user) {
                    Notification::make()
                        ->title(__('filament.bulk_import.failed'))
                        ->body($error)
                        ->danger()
                        ->sendToDatabase($user);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send bulk import error notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
