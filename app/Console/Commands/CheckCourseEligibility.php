<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Student;
use App\Services\CourseRegistrationService;
use Illuminate\Console\Command;

class CheckCourseEligibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:check-eligibility {student_id} {course_code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check a student\'s eligibility for course registration';

    /**
     * Execute the console command.
     */
    public function handle(CourseRegistrationService $service): int
    {
        $studentId = $this->argument('student_id');
        $courseCode = $this->argument('course_code');

        // Find student
        $student = Student::find($studentId);

        if (!$student) {
            $this->error("Student {$studentId} not found.");
            return Command::FAILURE;
        }

        // Get current semester
        $semester = $service->getCurrentSemester();

        if (!$semester) {
            $this->error('No active semester found.');
            return Command::FAILURE;
        }

        // Display student info
        $this->info("Student Information:");
        $this->info("  ID: {$student->student_id}");
        $this->info("  Name: {$student->full_name_arabic}");
        $this->info("  Department: {$student->department->department_name}");
        $this->info("  CGPA: " . ($student->cgpa ?? 'N/A'));
        $this->info("  Max Courses Allowed: " . $service->getMaxCoursesForStudent($student));
        $this->newLine();

        // Display semester info
        $this->info("Current Semester:");
        $this->info("  {$semester->semester_name} {$semester->year}");
        $this->info("  Registration: " . ($service->isRegistrationOpen($semester) ? '✅ OPEN' : '🔒 CLOSED'));
        $this->newLine();

        if ($courseCode) {
            // Check specific course
            $course = Course::where('course_code', $courseCode)->first();

            if (!$course) {
                $this->error("Course {$courseCode} not found.");
                return Command::FAILURE;
            }

            $this->info("Checking eligibility for: {$course->course_code} - {$course->course_name}");
            $this->newLine();

            $eligibility = $service->checkCourseEligibility($student, $course, $semester);

            if ($eligibility->isEligible) {
                $this->info('✅ Student IS ELIGIBLE for this course');
            } else {
                $this->error('❌ Student IS NOT ELIGIBLE for this course');
                $this->newLine();
                $this->warn('Blocking Reasons:');
                foreach ($eligibility->getBlockingReasons() as $index => $reason) {
                    $this->warn('  ' . ($index + 1) . '. ' . $reason);
                }
            }
        } else {
            // Show all available courses
            $this->info('Fetching available courses...');
            $this->newLine();

            $availableCourses = $service->getAvailableCoursesForStudent($student, $semester);

            // Separate eligible and ineligible courses
            $eligibleCourses = $availableCourses->where('is_eligible', true);
            $ineligibleCourses = $availableCourses->where('is_eligible', false);

            // Display eligible courses
            if ($eligibleCourses->isNotEmpty()) {
                $this->info("✅ Eligible Courses ({$eligibleCourses->count()}):");
                $this->newLine();

                $tableData = $eligibleCourses->map(function ($item) {
                    $course = $item['course'];
                    $sections = collect($item['sections'])->pluck('section_number')->join(', ');

                    return [
                        $course->course_code,
                        $course->course_name,
                        $course->credit_hours,
                        $sections ?: 'N/A',
                    ];
                })->toArray();

                $this->table(
                    ['Code', 'Course Name', 'Credit Hours', 'Sections'],
                    $tableData
                );
            } else {
                $this->warn('No eligible courses found.');
            }

            $this->newLine();

            // Display ineligible courses
            if ($ineligibleCourses->isNotEmpty()) {
                $this->warn("❌ Ineligible Courses ({$ineligibleCourses->count()}):");
                $this->newLine();

                foreach ($ineligibleCourses as $item) {
                    $course = $item['course'];
                    $reasons = $item['blocking_reasons'];

                    $this->warn("  {$course->course_code} - {$course->course_name}");
                    foreach ($reasons as $reason) {
                        $this->line("    • {$reason}");
                    }
                    $this->newLine();
                }
            }

            // Display current enrollments
            $enrollments = $student->enrollments()
                ->where('semester_id', $semester->semester_id)
                ->where('status', '!=', 'dropped')
                ->with('course')
                ->get();

            if ($enrollments->isNotEmpty()) {
                $this->newLine();
                $this->info("📚 Current Enrollments ({$enrollments->count()}):");
                $this->newLine();

                $enrollmentData = $enrollments->map(function ($enrollment) {
                    return [
                        $enrollment->course->course_code,
                        $enrollment->course->course_name,
                        $enrollment->course->credit_hours,
                        $enrollment->status,
                    ];
                })->toArray();

                $this->table(
                    ['Code', 'Course Name', 'Credit Hours', 'Status'],
                    $enrollmentData
                );
            }
        }

        return Command::SUCCESS;
    }
}
