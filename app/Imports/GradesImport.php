<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\StudentCourseGrade;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GradesImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public int $createdCount = 0;
    public int $updatedCount = 0;
    public int $gradesCount = 0;
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            try {
                $rowArray = $row instanceof Collection ? $row->toArray() : (array) $row;
                $this->processRow($rowArray, $index + 2);
            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $index + 2,
                    'message' => $e->getMessage(),
                ];
                Log::error('Grades import error', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => $row instanceof Collection ? $row->toArray() : (array) $row,
                ]);
            }
        }
    }

    private function processRow(array $row, int $rowNumber): void
    {
        $normalizedRow = $this->normalizeRowKeys($row);

        // Validate required fields
        if (empty($normalizedRow['student_id'])) {
            throw new \Exception('Student ID is required');
        }

        if (empty($normalizedRow['course_title'])) {
            throw new \Exception('Course Title is required');
        }

        // Extract and validate data
        $studentId = trim((string) $normalizedRow['student_id']);
        $courseTitle = trim((string) $normalizedRow['course_title']);
        $year = (int) ($normalizedRow['year'] ?? 0);
        $semesterName = trim((string) ($normalizedRow['semester'] ?? ''));

        // Verify student exists
        if (!Student::where('student_id', $studentId)->exists()) {
            throw new \Exception("Student {$studentId} not found");
        }

        // Extract and find course
        $courseCode = $this->extractCourseCode($courseTitle);
        if (!$courseCode) {
            throw new \Exception("Could not extract course code from: {$courseTitle}");
        }

        $course = Course::where('course_code', $courseCode)->first();
        if (!$course) {
            throw new \Exception("Course not found: {$courseCode}");
        }

        // Find semester
        $semesterId = $this->findSemesterId($year, $semesterName);
        if (!$semesterId) {
            throw new \Exception("Semester not found: {$semesterName} {$year}");
        }

        // Check if this is a retake
        $isRetake = Enrollment::where('student_id', $studentId)
                              ->where('course_id', $course->course_id)
                              ->where('semester_id', '!=', $semesterId)
                              ->exists();

        // Parse grade data
        $marks = $this->parseDecimal($normalizedRow['marks'] ?? null);
        $gradePercent = $this->parseDecimal($normalizedRow['grade_percent'] ?? null);
        $gradeLetter = trim((string) ($normalizedRow['grade_letter'] ?? ''));
        $points = $this->parseDecimal($normalizedRow['points'] ?? null);
        $creditHours = $this->parseInteger($normalizedRow['credit_hours'] ?? null);
        $actCreditHours = $this->parseInteger($normalizedRow['act_credit_hours'] ?? null);
        $comment = trim((string) ($normalizedRow['comment'] ?? ''));

        // Use transaction for data integrity
        DB::transaction(function () use (
            $studentId, $course, $semesterId, $isRetake, $points, $comment,
            $marks, $gradePercent, $gradeLetter, $creditHours, $actCreditHours
        ) {
            // Upsert enrollment
            $enrollment = Enrollment::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'course_id' => $course->course_id,
                    'semester_id' => $semesterId,
                ],
                [
                    'enrollment_date' => Carbon::now(),
                    'status' => 'completed',
                    'is_retake' => $isRetake,
                    'grade_points' => $points,
                    'comment' => $comment,
                ]
            );

            $wasCreated = $enrollment->wasRecentlyCreated;
            if ($wasCreated) {
                $this->createdCount++;
            } else {
                $this->updatedCount++;
            }

            // Upsert student course grade
            StudentCourseGrade::updateOrCreate(
                ['enrollment_id' => $enrollment->enrollment_id],
                [
                    'marks' => $marks,
                    'grade_percent' => $gradePercent,
                    'grade_letter' => $gradeLetter,
                    'points' => $points,
                    'credit_hours' => $creditHours,
                    'act_credit_hours' => $actCreditHours,
                ]
            );

            $this->gradesCount++;
        });
    }

    private function normalizeRowKeys(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $lowerKey = strtolower(trim((string) $key));

            if ($lowerKey === 'student_id') {
                $normalized['student_id'] = $value;
            } elseif (in_array($lowerKey, ['student_name', 'full_name', 'name'])) {
                $normalized['student_name'] = $value;
            } elseif ($lowerKey === 'year') {
                $normalized['year'] = $value;
            } elseif ($lowerKey === 'semester') {
                $normalized['semester'] = $value;
            } elseif (in_array($lowerKey, ['course_title', 'course', 'course_name'])) {
                $normalized['course_title'] = $value;
            } elseif ($lowerKey === 'marks') {
                $normalized['marks'] = $value;
            } elseif (in_array($lowerKey, ['grade_percent', 'percentage', 'percent'])) {
                $normalized['grade_percent'] = $value;
            } elseif (in_array($lowerKey, ['grade_letter', 'grade', 'letter_grade'])) {
                $normalized['grade_letter'] = $value;
            } elseif ($lowerKey === 'points') {
                $normalized['points'] = $value;
            } elseif (in_array($lowerKey, ['credit_hours', 'credits', 'hours'])) {
                $normalized['credit_hours'] = $value;
            } elseif (in_array($lowerKey, ['act_credit_hours', 'actual_credit_hours', 'earned_hours'])) {
                $normalized['act_credit_hours'] = $value;
            } elseif (in_array($lowerKey, ['comment', 'remarks', 'notes'])) {
                $normalized['comment'] = $value;
            }
        }

        return $normalized;
    }

    private function extractCourseCode(string $courseTitle): ?string
    {
        $normalized = trim($courseTitle);

        if (preg_match('/^([A-Z]{2,4})\s*(\d{3})\s*[-–—]/', $normalized, $matches)) {
            return strtoupper($matches[1]) . $matches[2];
        }

        if (preg_match('/^([A-Z]{2,4})(\d{3})[-–—]/', $normalized, $matches)) {
            return strtoupper($matches[1]) . $matches[2];
        }

        if (preg_match('/^([A-Z]{2,4})\s*(\d{3})$/', $normalized, $matches)) {
            return strtoupper($matches[1]) . $matches[2];
        }

        return null;
    }

    private function findSemesterId(int $year, string $semesterName): ?int
    {
        $normalizedName = $this->normalizeSemesterName($semesterName);

        $semester = Semester::where('year', $year)
                           ->where('semester_name', $normalizedName)
                           ->first();

        return $semester ? $semester->semester_id : null;
    }

    private function normalizeSemesterName(string $name): string
    {
        $normalized = strtoupper(trim($name));

        $mapping = [
            'FALL' => 'FALL',
            'F' => 'FALL',
            'AUTUMN' => 'FALL',
            'SPRING' => 'SPRING',
            'S' => 'SPRING',
            'SUMMER' => 'Summer',
            'SU' => 'Summer',
        ];

        return $mapping[$normalized] ?? $normalized;
    }

    private function parseDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (float) str_replace(',', '.', $value);
    }

    private function parseInteger($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (int) $value;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
