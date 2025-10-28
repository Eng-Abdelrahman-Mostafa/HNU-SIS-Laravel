<?php
// app/Http/Controllers/Admin/ImportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImportService;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\Department;
use App\Models\AcademicLevel;
use App\Models\Course;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- Add Storage facade

class ImportController extends Controller
{
    protected $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Show the import forms.
     */
    public function index()
    {
        // ... (index method remains the same) ...
        $semesters = Semester::orderBy('year', 'desc')->orderBy('start_date', 'desc')->get();
        $departments = Department::orderBy('department_name')->get();
        $levels = AcademicLevel::orderBy('level_number')->get();
        $courses = Course::orderBy('course_code')->get();

        return view('admin.import', [
            'semesters' => $semesters,
            'departments' => $departments,
            'levels' => $levels,
            'courses' => $courses,
        ]);
    }

    /**
     * Handle the 6 Student and Grade file uploads (XLSX).
     */
    public function uploadStudentData(Request $request)
    {
        $filesToValidate = [
            'students_ds' => 'required|file|mimes:xlsx,xls',
            'students_mse' => 'required|file|mimes:xlsx,xls',
            'students_rse' => 'required|file|mimes:xlsx,xls',
            'grades_ds' => 'required|file|mimes:xlsx,xls',
            'grades_mse' => 'required|file|mimes:xlsx,xls',
            'grades_rse' => 'required|file|mimes:xlsx,xls',
        ];

        $request->validate($filesToValidate);

        $fileMappings = [
            'students_ds' => 'Students CGPA_DS.xlsx',
            'students_mse' => 'Students CGPA_MSE.xlsx',
            'students_rse' => 'Students CGPA_RSE.xlsx',
            'grades_ds' => 'Subjects Grades_DS.xlsx',
            'grades_mse' => 'Subjects Grades_MSE.xlsx',
            'grades_rse' => 'Subjects Grades_RSE.xlsx',
        ];

        // --- NEW DEBUGGING STEPS ---
        $targetDirectory = 'import'; // Relative to storage/app
        $absoluteTargetPath = storage_path('app/' . $targetDirectory);

        // 1. Check if directory exists
        if (!Storage::disk('local')->exists($targetDirectory)) {
            Log::warning("Target directory '{$absoluteTargetPath}' does not exist. Attempting to create.");
            // Attempt to create it (might fail due to permissions on storage/app)
            try {
                Storage::disk('local')->makeDirectory($targetDirectory);
                if (!Storage::disk('local')->exists($targetDirectory)) {
                     Log::error("Failed to create target directory '{$absoluteTargetPath}'. Check permissions on storage/app.");
                     return back()->with('error', "Import directory is missing and could not be created. Check server permissions.");
                }
                 Log::info("Target directory '{$absoluteTargetPath}' created successfully.");
            } catch (\Exception $e) {
                 Log::error("Exception creating target directory '{$absoluteTargetPath}': " . $e->getMessage());
                 return back()->with('error', "Error creating import directory. Check server permissions and logs.");
            }
        } else {
             Log::info("Target directory '{$absoluteTargetPath}' exists.");
        }

        // 2. Check if directory is writable (basic check, might not catch all OS-level issues)
        if (!is_writable($absoluteTargetPath)) {
            Log::error("Target directory '{$absoluteTargetPath}' is NOT WRITABLE by the web server user.");
            // Try to set permissions (this might fail depending on user/setup)
            @chmod($absoluteTargetPath, 0775); // Use @ to suppress errors if it fails
            if (!is_writable($absoluteTargetPath)) {
                return back()->with('error', "The import directory ('{$absoluteTargetPath}') is not writable by the web server. Please check permissions.");
            } else {
                 Log::info("Permissions potentially fixed for '{$absoluteTargetPath}'. Retrying write.");
            }
        } else {
             Log::info("Target directory '{$absoluteTargetPath}' is writable.");
        }
        // --- END DEBUGGING STEPS ---


        try {
            foreach ($fileMappings as $inputName => $targetName) {
                if (!$request->hasFile($inputName) || !$request->file($inputName)->isValid()) {
                    Log::error("Upload failed or missing for input: {$inputName}");
                    return back()->with('error', "Upload failed or missing for file: {$inputName}. Please try again.");
                }

                // Use the $targetDirectory variable
                $storedPath = $request->file($inputName)->storeAs($targetDirectory, $targetName);

                if ($storedPath === false) {
                    Log::critical("!!! FAILED TO STORE: Input '{$inputName}' to '{$targetName}' in directory '{$targetDirectory}'. Double-check permissions!");
                    $errorMessage = "Failed to save the file '{$request->file($inputName)->getClientOriginalName()}'.";
                    return back()->with('error', $errorMessage . " Please check server logs or permissions.");
                } else {
                    Log::info("Successfully stored: Input '{$inputName}' to '{$targetName}' at path '{$storedPath}'");
                }
            }

        } catch (\Exception $e) {
             Log::error('Exception during file storage: ' . $e->getMessage());
             Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'An unexpected error occurred while saving files: ' . $e->getMessage());
        }

        Log::info('File storage step completed. Proceeding to import service.');
        $logs = $this->importService->runStudentDataImport($request->boolean('fresh_students'));

        // ... (rest of the method remains the same) ...
        $hasErrors = collect($logs)->contains(fn($log) => str_starts_with($log, 'ERROR:'));
        if ($hasErrors) {
             $onlyFileNotFound = collect($logs)->every(fn($log) => str_starts_with($log, 'ERROR: File not found'));
             if($onlyFileNotFound){
                 Log::error('ImportService reported File Not Found errors after storage step succeeded. Check filesystem visibility/permissions for the PHP process.');
                 return back()->with('error-students', 'Import failed: Files were saved but could not be read by the import process. Check storage permissions and logs.')->with('logs-students', $logs);
             }
             return back()->with('error-students', 'Student data import completed with errors.')->with('logs-students', $logs);
        }

        return back()->with('success-students', 'Student & Grade data imported successfully!')->with('logs-students', $logs);
    }

    /**
     * Seed the static data (Departments, Levels)
     */
    public function seedStaticData(Request $request)
    {
        // ... (remains the same) ...
        $logs = $this->importService->importStaticData();
        $hasErrors = collect($logs)->contains(fn($log) => str_starts_with($log, 'ERROR:'));

        if ($hasErrors) {
             return back()->with('error-curriculum', 'Seeding static data failed.')->with('logs-curriculum', $logs);
        }

        return back()->with('success-curriculum', 'Departments and Levels seeded successfully!')->with('logs-curriculum', $logs);
    }

    /**
     * Export student registrations for a given semester as a CSV.
     */
    public function exportRegistrations(Request $request): StreamedResponse
    {
        // ... (remains the same) ...
        $request->validate(['semester_id' => 'required|integer|exists:semesters,semester_id']);
        $semesterId = $request->input('semester_id');
        $semester = Semester::findOrFail($semesterId);
        $fileName = 'registrations_' . $semester->semester_code . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($semesterId) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            $columns = [
                'Student ID', 'Student Name (Arabic)', 'Student Email', 'Department',
                'Course Code', 'Course Name', 'Course Credit Hours', 'Semester',
                'Enrollment Status', 'Enrollment Date',
            ];
            fputcsv($file, $columns);

            Enrollment::with(['student.department', 'course', 'semester'])
                ->where('semester_id', $semesterId)
                ->where('status', 'Registered')
                ->orderBy('student_id')
                ->chunk(500, function ($enrollments) use ($file) {
                    foreach ($enrollments as $enrollment) {
                        fputcsv($file, [
                            $enrollment->student->student_id ?? 'N/A',
                            $enrollment->student->full_name_arabic ?? 'N/A',
                            $enrollment->student->email ?? 'N/A',
                            $enrollment->student->department->department_name ?? 'N/A',
                            $enrollment->course->course_code ?? 'N/A',
                            $enrollment->course->course_name ?? 'N/A',
                            $enrollment->course->credit_hours ?? 'N/A',
                            $enrollment->semester->semester_name ?? 'N/A',
                            $enrollment->status,
                            $enrollment->enrollment_date->format('Y-m-d'),
                        ]);
                    }
                });
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
