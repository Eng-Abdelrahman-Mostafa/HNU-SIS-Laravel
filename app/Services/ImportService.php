<?php
// app/Services/ImportService.php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
// REMOVED: use Maatwebsite\Excel\HeadingRowImport; // Not used
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection; // <-- ADDED Collection type hint base
use App\Models\Department;
use App\Models\AcademicLevel;
use App\Models\Course;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Enrollment;
// REMOVED: use App\Models\ProgramRequirement; // Not used
// REMOVED: use App\Models\Prerequisite; // Not used
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ImportService
{
    public array $logs = [];
    /** @var Collection */ // <-- ADDED Type Hint
    protected $departmentCache;
    /** @var Collection */ // <-- ADDED Type Hint
    protected $levelCache;
    /** @var Collection */ // <-- ADDED Type Hint
    protected $courseCache;
    /** @var array */ // Simple array is fine here
    protected $semesterCache = [];

    public function __construct()
    {
        // Initialize caches as empty Collections to satisfy type hints
        $this->departmentCache = collect();
        $this->levelCache = collect();
        $this->courseCache = collect();
    }


    /**
     * Seeds the database with static Departments and Levels
     */
    public function importStaticData(): array
    {
        $this->logs = [];
        $this->logs[] = 'INFO: Seeding Departments and Levels...';
        try {
            // Use updateOrCreate to prevent duplicates and allow re-running
            Department::updateOrCreate(['department_code' => 'DS'], ['department_name' => 'Data Science', 'department_prefix' => '931']);
            Department::updateOrCreate(['department_code' => 'MSE'], ['department_name' => 'Multimedia & Software Engineering', 'department_prefix' => '933']);
            Department::updateOrCreate(['department_code' => 'RSE'], ['department_name' => 'Robotics & Software Engineering', 'department_prefix' => '932']);

            AcademicLevel::updateOrCreate(['level_number' => 1], ['level_name' => 'Level 1', 'min_credit_hours' => 0, 'max_credit_hours' => 36]);
            AcademicLevel::updateOrCreate(['level_number' => 2], ['level_name' => 'Level 2', 'min_credit_hours' => 37, 'max_credit_hours' => 72]);
            AcademicLevel::updateOrCreate(['level_number' => 3], ['level_name' => 'Level 3', 'min_credit_hours' => 73, 'max_credit_hours' => 108]);
            AcademicLevel::updateOrCreate(['level_number' => 4], ['level_name' => 'Level 4', 'min_credit_hours' => 109, 'max_credit_hours' => 144]);

            $this->logs[] = 'SUCCESS: Departments and Levels seeded/updated.';
        } catch (\Exception $e) {
            Log::error('StaticSeed Failed: ' . $e->getMessage());
            $this->logs[] = 'ERROR: ' . $e->getMessage();
        }
        return $this->logs;
    }

    /**
     * Imports just the 6 Student and Grade XLSX files.
     */
    public function runStudentDataImport(bool $fresh = false): array
    {
        $this->logs = [];
        try {
            // Pre-fill caches from data that should exist
            $this->departmentCache = Department::all()->pluck('department_id', 'department_code');
            $this->levelCache = AcademicLevel::all(); // Collection of AcademicLevel objects
            $this->courseCache = Course::all()->keyBy('course_code'); // Collection code => Course object

            // Check caches (isEmpty() works on Collections)
            if ($this->departmentCache->isEmpty() || $this->courseCache->isEmpty() || $this->levelCache->isEmpty()) {
                 $this->logs[] = 'ERROR: No Departments, Levels, or Courses found. Please add curriculum data manually first.';
                 return $this->logs;
            }

            if ($fresh) {
                $this->logs[] = 'INFO: Clearing Student & Enrollment data...';
                DB::statement("SET session_replication_role = 'replica';");
                Enrollment::truncate();
                Student::truncate();
                Semester::truncate();
                DB::statement("SET session_replication_role = 'origin';");
                $this->logs[] = 'SUCCESS: Student & Enrollment tables cleared.';
            }

            $this->logs[] = 'INFO: Starting Student & Grade data import...';
            $this->importStudents();
            $this->importEnrollments();
            $this->logs[] = 'SUCCESS: Student & Grade data import completed.';

        } catch (\Exception $e) {
            DB::statement("SET session_replication_role = 'origin';");
            Log::error('StudentDataImport Failed: ' . $e->getMessage());
            $this->logs[] = 'ERROR: ' . $e->getMessage();
        }
        return $this->logs;
    }

    // --- Student/Enrollment Helper Functions ---

    protected function importStudents()
    {
        $this->logs[] = 'INFO: Importing Students...';
        $files = [
            'DS' => 'Students CGPA_DS.xlsx',
            'MSE' => 'Students CGPA_MSE.xlsx',
            'RSE' => 'Students CGPA_RSE.xlsx',
        ];

        foreach ($files as $deptCode => $fileName) {
            // Use get() method on Collection
            $department_id = $this->departmentCache->get($deptCode);
            if (!$department_id) {
                 $this->logs[] = "WARN: Skipping students for $deptCode (Department not found).";
                 continue;
            }

            $path = storage_path('app/import/' . $fileName);
            if (!File::exists($path)) {
                $this->logs[] = "ERROR: File not found: $path";
                continue;
            }

            $this->processExcel($path, function ($row) use ($department_id) {
                 if (empty($row['student_id'])) return;

                $level_id = $this->getAcademicLevelId((int)($row['earned_ch'] ?? 0));

                Student::updateOrCreate(
                    ['student_id' => $row['student_id']],
                    [
                        'full_name_arabic' => $row['student_name'] ?? 'Unknown',
                        'email' => $row['student_id'] . '@university.edu',
                        'password_hash' => Hash::make($row['student_id']),
                        'department_id' => $department_id,
                        'current_level_id' => $level_id,
                        'cgpa' => (float)($row['cgpa'] ?? 0),
                        'total_points' => (float)($row['total_points'] ?? 0),
                        'earned_credit_hours' => (int)($row['earned_ch'] ?? 0),
                        'studied_credit_hours' => (int)($row['studied_ch'] ?? 0),
                        'actual_credit_hours' => (int)($row['actual_ch'] ?? 0),
                        'status' => 'Active',
                    ]
                );
            });
            $this->logs[] = "INFO: Imported students for $deptCode.";
        }
    }

    protected function importEnrollments()
    {
        $this->logs[] = 'INFO: Importing Enrollments (Academic History)...';
        $files = [
            'DS' => 'Subjects Grades_DS.xlsx',
            'MSE' => 'Subjects Grades_MSE.xlsx',
            'RSE' => 'Subjects Grades_RSE.xlsx',
        ];

        foreach ($files as $deptCode => $fileName) {
            $path = storage_path('app/import/' . $fileName);
             if (!File::exists($path)) {
                $this->logs[] = "ERROR: File not found: $path";
                continue;
            }

            $this->processExcel($path, function ($row) {
                if (empty($row['student_id'])) return;
                if (empty($row['course_title'])) {
                     $this->logs[] = "WARN: Skipping row for student {$row['student_id']} - missing course title.";
                     return;
                }

                $courseCode = $this->parseCourseCode($row['course_title']);
                if (empty($courseCode)) {
                    $this->logs[] = "WARN: Could not parse course code from: " . $row['course_title'];
                    return;
                }

                // Call getCourseFromCache without name/credits
                $course = $this->getCourseFromCache($courseCode);
                if (!$course) {
                    $this->logs[] = "WARN: Could not find course for code: $courseCode. Enrollment skipped for student {$row['student_id']}.";
                    return;
                }

                if (empty($row['year']) || empty($row['semester'])) {
                    $this->logs[] = "WARN: Skipping enrollment for student {$row['student_id']}, course {$courseCode} - missing YEAR or SEMESTER.";
                    return;
                }
                $semester_id = $this->getSemesterId($row['year'], $row['semester']);

                $is_retake = Enrollment::where('student_id', $row['student_id'])
                                    ->where('course_id', $course->course_id)
                                    ->exists();

                $points = (float)($row['points'] ?? 0);
                $grade_letter = $row['grade_letter'] ?? 'F';

                $status = ($points > 0 || in_array(strtoupper($grade_letter), ['P', 'PASS'])) ? 'Completed' : 'Failed';

                Enrollment::create([
                    'student_id' => $row['student_id'],
                    'course_id' => $course->course_id,
                    'semester_id' => $semester_id,
                    'enrollment_date' => Carbon::now(),
                    'status' => $status,
                    'is_retake' => $is_retake,
                    'grade_points' => $points,
                ]);
            });
            $this->logs[] = "INFO: Imported enrollments for $deptCode.";
        }
    }

    /**
     * Process rows from an Excel file using Maatwebsite/Excel.
     */
    protected function processExcel($path, callable $callback)
    {
        try {
            Excel::filter('chunk')->load($path)->chunk(200, function ($results) use ($callback) {
                 foreach ($results as $row) {
                    $rowData = $row->toArray();
                    $cleanedRowData = [];
                    foreach ($rowData as $key => $value) {
                        // More robust snake_case conversion
                        $cleanedKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', str_replace([' ', '-'], '_', $key)));
                        $cleanedKey = preg_replace('/_+/', '_', $cleanedKey); // Replace multiple underscores
                        $cleanedRowData[$cleanedKey] = $value;
                    }

                    if (count(array_filter($cleanedRowData)) == 0) {
                        continue;
                    }

                    $callback($cleanedRowData);
                }
            }, false);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             foreach ($failures as $failure) {
                 $this->logs[] = "ERROR: Excel Validation Failed Row {$failure->row()}: {$failure->errors()[0]} (Attribute: {$failure->attribute()})";
             }
             Log::error("Excel Validation Errors in {$path}: " . json_encode($failures));
        } catch (\Exception $e) {
            $this->logs[] = "ERROR: Failed to process Excel file {$path}: " . $e->getMessage();
            Log::error("Excel Import Error in {$path}: " . $e->getMessage() . " at Line: " . $e->getLine());
        }
    }


    protected function getAcademicLevelId(int $earnedHours): int
    {
        if ($this->levelCache->isEmpty()) {
            $this->levelCache = AcademicLevel::all();
            if ($this->levelCache->isEmpty()) {
                $this->logs[] = "ERROR: Academic Levels not found in database.";
                return 1;
            }
        }
        // Use first() on the Collection
        $level = $this->levelCache->first(function ($level) use ($earnedHours) {
            // Ensure $level is an object
             return is_object($level) && property_exists($level, 'min_credit_hours') && property_exists($level, 'max_credit_hours') &&
                    $earnedHours >= $level->min_credit_hours && $earnedHours <= $level->max_credit_hours;

        });

        // Use last() on the Collection
        return $level->level_id ?? $this->levelCache->sortByDesc('level_number')->first()->level_id;
    }

    protected function parseCourseCode(string $courseTitle): ?string
    {
        if (preg_match('/^([A-Z]{3}\s\d{3})/', trim($courseTitle), $matches)) {
            return $matches[1];
        }
        return null;
    }

    // REMOVED unused $name, $credits parameters
    protected function getCourseFromCache(string $code): ?Course
    {
        if (empty($code)) return null;

        if ($this->courseCache->isEmpty()) {
            $this->courseCache = Course::all()->keyBy('course_code');
             if ($this->courseCache->isEmpty()) {
                $this->logs[] = "ERROR: Courses not found in database. Cannot process enrollments.";
                return null;
            }
        }

        // Use get() method on Collection
        $course = $this->courseCache->get($code);

        if ($course) {
            return $course;
        }

        // Do NOT create stub courses here.
        $this->logs[] = "WARN: Course code '$code' from Excel not found in the database.";
        return null;
    }

    protected function getSemesterId(string $year, string $term): int
    {
        if (!is_numeric($year) || (int)$year < 1900 || (int)$year > date('Y') + 5) {
            $this->logs[] = "ERROR: Invalid year '{$year}' found in Excel.";
             throw new \Exception("Invalid year '$year' found in Excel.");
        }
        $term = strtoupper(trim($term));
         if (!in_array($term, ['FALL', 'SPRING', 'SUMMER'])) {
             $this->logs[] = "ERROR: Invalid term '{$term}' found in Excel.";
             throw new \Exception("Invalid term '$term' found in Excel.");
        }

        $code = "$year-$term";
        if (isset($this->semesterCache[$code])) return $this->semesterCache[$code];

        $name = ucfirst(strtolower($term)) . " $year";
        $semester = Semester::firstOrCreate(
            ['semester_code' => $code],
            [
                'semester_name' => $name,
                'year' => (int)$year,
                'start_date' => Carbon::create((int)$year, ($term === 'FALL' ? 9 : ($term === 'SPRING' ? 2 : 6)), 1)->startOfMonth(),
                'end_date' => Carbon::create((int)$year, ($term === 'FALL' ? 12 : ($term === 'SPRING' ? 5 : 8)), 1)->endOfMonth(),
            ]
        );
        $this->semesterCache[$code] = $semester->semester_id;
        return $semester->semester_id;
    }
}
