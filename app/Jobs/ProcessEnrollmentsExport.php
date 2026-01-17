<?php

namespace App\Jobs;

use App\Exports\EnrollmentsExport;
use App\Models\User;
use App\Models\Semester;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Log;

class ProcessEnrollmentsExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $semesterId;
    protected ?int $userId;

    public function __construct(int $semesterId, ?int $userId = null)
    {
        $this->semesterId = $semesterId;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        try {
            Log::info('Starting enrollments export', [
                'semester_id' => $this->semesterId,
                'user_id' => $this->userId,
            ]);

            $semester = Semester::find($this->semesterId);
            if (!$semester) {
                throw new \Exception('Semester not found');
            }

            // Generate filename
            $filename = 'enrollments_' . $semester->semester_code . '_' . now()->format('Ymd_His') . '.xlsx';
            $filePath = 'exports/' . $filename;

            // Create the export
            Excel::store(
                new EnrollmentsExport($this->semesterId),
                $filePath,
                'public'
            );

            Log::info('Enrollments export completed', [
                'semester_id' => $this->semesterId,
                'file_path' => $filePath,
            ]);

            // Send success notification
            $this->sendNotification($filename, $filePath);

        } catch (\Exception $e) {
            Log::error('Enrollments export failed', [
                'error' => $e->getMessage(),
                'semester_id' => $this->semesterId,
            ]);

            // Send error notification
            $this->sendErrorNotification($e->getMessage());
        }
    }

    protected function sendNotification(string $filename, string $filePath): void
    {
        $title = __('filament.enrollments_export.completed');
        $message = __('filament.enrollments_export.completed_message', ['filename' => $filename]);

        try {
            if ($this->userId) {
                $user = User::find($this->userId);
                if ($user) {
                    Notification::make()
                        ->title($title)
                        ->body($message)
                        ->success()
                        ->actions([
                            Action::make('download')
                                ->label(__('filament.enrollments_export.download'))
                                ->url(Storage::disk('public')->url($filePath))
                                ->openUrlInNewTab()
                        ])
                        ->sendToDatabase($user);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send enrollments export notification', [
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
                        ->title(__('filament.enrollments_export.failed'))
                        ->body($error)
                        ->danger()
                        ->sendToDatabase($user);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send enrollments export error notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
