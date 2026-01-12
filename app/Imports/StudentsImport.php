<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public int $createdCount = 0;
    public int $updatedCount = 0;
    public array $errors = [];
    private string $departmentId;
    private ?int $currentLevelId;
    private string $appName;

    public function __construct(string $departmentId, ?int $currentLevelId = null)
    {
        $this->departmentId = $departmentId;
        $this->currentLevelId = $currentLevelId;
        // Use APP_NAME from config, fallback to 'app'
        $this->appName = strtolower(str_replace(' ', '', config('app.name') ?: 'app'));
    }

    public function collection(Collection $rows): void
    {
        // Process rows in batches to avoid N+1 queries
        foreach ($rows as $index => $row) {
            try {
                // Convert Collection row to array
                $rowArray = $row instanceof Collection ? $row->toArray() : (array) $row;
                $this->processRow($rowArray, $index + 2); // +2 because row 1 is header, Excel is 1-indexed
            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $index + 2,
                    'message' => $e->getMessage(),
                ];
                Log::error('Student import error', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => $row instanceof Collection ? $row->toArray() : (array) $row,
                ]);
            }
        }
    }

    private function processRow(array $row, int $rowNumber): void
    {
        // Normalize column names - handle variations
        $normalizedRow = $this->normalizeRowKeys($row);

        // Skip empty rows
        if (empty($normalizedRow['student_id']) || empty($normalizedRow['full_name_arabic'])) {
            throw new \Exception('Student ID and Full Name are required');
        }

        $studentId = trim((string) $normalizedRow['student_id']);
        $fullNameArabic = trim((string) $normalizedRow['full_name_arabic']);

        // Parse numeric fields
        $cgpa = $this->parseDecimal($normalizedRow['cgpa'] ?? 0);
        $totalPoints = $this->parseDecimal($normalizedRow['total_points'] ?? 0);
        $earnedCreditHours = $this->parseInteger($normalizedRow['earned_credit_hours'] ?? 0);
        $studiedCreditHours = $this->parseInteger($normalizedRow['studied_credit_hours'] ?? 0);
        $actualCreditHours = $this->parseInteger($normalizedRow['actual_credit_hours'] ?? 0);

        // Check if student exists
        $student = Student::where('student_id', $studentId)->first();

        if ($student) {
            // Update existing student
            $student->update([
                'full_name_arabic' => $fullNameArabic,
                'cgpa' => $cgpa,
                'total_points' => $totalPoints,
                'earned_credit_hours' => $earnedCreditHours,
                'studied_credit_hours' => $studiedCreditHours,
                'actual_credit_hours' => $actualCreditHours,
            ]);
            $this->updatedCount++;
        } else {
            // Create new student
            $email = "{$studentId}@{$this->appName}.com";

            // Ensure email is unique
            while (Student::where('email', $email)->exists()) {
                $email = "{$studentId}." . uniqid() . "@{$this->appName}.com";
            }

            Student::create([
                'student_id' => $studentId,
                'full_name_arabic' => $fullNameArabic,
                'email' => $email,
                'password_hash' => Hash::make($studentId),
                'department_id' => $this->departmentId,
                'current_level_id' => $this->currentLevelId,
                'cgpa' => $cgpa,
                'total_points' => $totalPoints,
                'earned_credit_hours' => $earnedCreditHours,
                'studied_credit_hours' => $studiedCreditHours,
                'actual_credit_hours' => $actualCreditHours,
                'status' => 'active',
            ]);
            $this->createdCount++;
        }
    }

    private function normalizeRowKeys(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $lowerKey = strtolower(trim((string) $key));

            // Normalize student_id
            if ($lowerKey === 'student_id') {
                $normalized['student_id'] = $value;
            }
            // Normalize full name (various possibilities)
            elseif (in_array($lowerKey, ['full_name_arabic', 'student_name', 'full_name', 'name', 'student_full_name'])) {
                $normalized['full_name_arabic'] = $value;
            }
            // Normalize CGPA
            elseif (in_array($lowerKey, ['cgpa', 'gpa', 'cumulative_gpa'])) {
                $normalized['cgpa'] = $value;
            }
            // Normalize total points
            elseif (in_array($lowerKey, ['total_points', 'points', 'grade_points'])) {
                $normalized['total_points'] = $value;
            }
            // Normalize earned credit hours
            elseif (in_array($lowerKey, ['earned_credit_hours', 'earned_ch', 'earned_hours', 'completed_hours'])) {
                $normalized['earned_credit_hours'] = $value;
            }
            // Normalize studied credit hours
            elseif (in_array($lowerKey, ['studied_credit_hours', 'studied_ch', 'studied_hours', 'attempted_hours'])) {
                $normalized['studied_credit_hours'] = $value;
            }
            // Normalize actual credit hours
            elseif (in_array($lowerKey, ['actual_credit_hours', 'actual_ch', 'actual_hours'])) {
                $normalized['actual_credit_hours'] = $value;
            }
        }

        return $normalized;
    }

    private function parseDecimal($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }
        return (float) str_replace(',', '.', $value);
    }

    private function parseInteger($value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }
        return (int) $value;
    }

    public function chunkSize(): int
    {
        return 200; // Process 200 rows at a time for better memory management
    }

    public function headingRow(): int
    {
        return 1;
    }
}
