<?php

namespace App\Services;

use App\DTOs\CourseEligibilityContext;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentCourseGrade;
use App\Services\CourseEligibility\Pipes\CheckCGPACourseLimitPipe;
use App\Services\CourseEligibility\Pipes\CheckCourseAvailabilityPipe;
use App\Services\CourseEligibility\Pipes\CheckDepartmentRestrictionPipe;
use App\Services\CourseEligibility\Pipes\CheckDuplicateEnrollmentPipe;
use App\Services\CourseEligibility\Pipes\CheckPrerequisitesPipe;
use App\Services\CourseEligibility\Pipes\CheckRegistrationPeriodPipe;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CourseRegistrationService
{
    /**
     * Get all available courses for a student with eligibility status
     */
    public function getAvailableCoursesForStudent(Student $student, Semester $semester): Collection
    {
        // Get courses available in this semester
        $availableCourseIds = DB::table('course_instructor_assignments')
            ->where('semester_id', $semester->semester_id)
            ->distinct()
            ->pluck('course_id');

        $courses = Course::with(['department', 'level', 'prerequisites.prerequisiteCourse'])
            ->whereIn('course_id', $availableCourseIds)
            ->get();

        // Get student's completed courses (passed courses)
        $completedCourses = $this->getCompletedCourses($student);

        // Get student's current enrollments (excluding dropped)
        $currentEnrollments = $student->enrollments()
            ->with('courseGrade')
            ->where('semester_id', $semester->semester_id)
            ->where('status', '!=', 'dropped')
            ->get();

        // Check eligibility for each course
        return $courses->map(function (Course $course) use ($student, $semester, $completedCourses, $currentEnrollments) {
            $eligibility = $this->checkCourseEligibility(
                $student,
                $course,
                $semester,
                $completedCourses,
                $currentEnrollments
            );

            return [
                'course' => $course,
                'is_eligible' => $eligibility->isEligible,
                'blocking_reasons' => $eligibility->getBlockingReasons(),
                'sections' => $this->getCourseSections($course, $semester),
                'should_exclude' => $eligibility->shouldExclude(),
            ];
        })->reject(function ($item) {
            // Filter out courses that should be excluded (e.g., already passed courses)
            return $item['should_exclude'];
        });
    }

    /**
     * Check if a student is eligible to register for a specific course
     */
    public function checkCourseEligibility(
        Student $student,
        Course $course,
        Semester $semester,
        ?Collection $completedCourses = null,
        ?Collection $currentEnrollments = null
    ): CourseEligibilityContext {
        $completedCourses = $completedCourses ?? $this->getCompletedCourses($student);
        $currentEnrollments = $currentEnrollments ?? $student->enrollments()->get();

        $context = new CourseEligibilityContext(
            $student,
            $course,
            $semester,
            $completedCourses,
            $currentEnrollments
        );

        // Define the pipeline of checks
        $pipes = [
            CheckRegistrationPeriodPipe::class,
            CheckCourseAvailabilityPipe::class,
            CheckDuplicateEnrollmentPipe::class,
            CheckPrerequisitesPipe::class,
            CheckDepartmentRestrictionPipe::class,
            CheckCGPACourseLimitPipe::class,
        ];

        return app(Pipeline::class)
            ->send($context)
            ->through($pipes)
            ->thenReturn();
    }

    /**
     * Validate course registration
     */
    public function validateCourseRegistration(
        Student $student,
        array $courseIds,
        Semester $semester
    ): array {
        $completedCourses = $this->getCompletedCourses($student);
        $currentEnrollments = $student->enrollments()->get();

        $validationResults = [];
        $eligibleCourses = [];
        $ineligibleCourses = [];

        foreach ($courseIds as $courseId) {
            $course = Course::find($courseId);

            if (!$course) {
                $ineligibleCourses[] = [
                    'course_id' => $courseId,
                    'reasons' => ['Course not found.'],
                ];
                continue;
            }

            $eligibility = $this->checkCourseEligibility(
                $student,
                $course,
                $semester,
                $completedCourses,
                $currentEnrollments
            );

            if ($eligibility->isEligible) {
                $eligibleCourses[] = [
                    'course_id' => $courseId,
                    'course' => $course,
                ];
            } else {
                $ineligibleCourses[] = [
                    'course_id' => $courseId,
                    'course' => $course,
                    'reasons' => $eligibility->getBlockingReasons(),
                ];
            }
        }

        return [
            'is_valid' => empty($ineligibleCourses),
            'eligible_courses' => $eligibleCourses,
            'ineligible_courses' => $ineligibleCourses,
        ];
    }

    /**
     * Register student for courses
     */
    public function registerStudentForCourses(
        Student $student,
        array $courseIds,
        Semester $semester
    ): array {
        // Calculate minimum required courses
        $maxCourses = $this->getMaxCoursesForStudent($student);
        $currentEnrollmentCount = $student->enrollments()
            ->where('semester_id', $semester->semester_id)
            ->where('status', '!=', 'dropped')
            ->count();

        $remainingSlots = $maxCourses - $currentEnrollmentCount;
        $availableCourses = $this->getAvailableCoursesForStudent($student, $semester);
        $eligibleCoursesCount = $availableCourses->filter(fn($item) => $item['is_eligible'])->count();
        $requiredMinimum = min($remainingSlots, $eligibleCoursesCount);

        // Check if student selected minimum required courses
        if (count($courseIds) < $requiredMinimum) {
            return [
                'success' => false,
                'message' => __('filament.course_registration.minimum_courses_required_description', [
                    'required' => $requiredMinimum,
                    'selected' => count($courseIds),
                ]),
            ];
        }

        // Validate eligibility for each course
        $validation = $this->validateCourseRegistration($student, $courseIds, $semester);

        if (!$validation['is_valid']) {
            return [
                'success' => false,
                'message' => 'Some courses are not eligible for registration.',
                'validation' => $validation,
            ];
        }

        DB::beginTransaction();

        try {
            $enrollments = [];

            foreach ($validation['eligible_courses'] as $eligibleCourse) {
                $enrollment = Enrollment::create([
                    'student_id' => $student->student_id,
                    'course_id' => $eligibleCourse['course_id'],
                    'semester_id' => $semester->semester_id,
                    'enrollment_date' => now(),
                    'status' => 'enrolled',
                    'is_retake' => $this->isRetake($student, $eligibleCourse['course_id']),
                ]);

                $enrollments[] = $enrollment;
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Successfully registered for ' . count($enrollments) . ' course(s).',
                'enrollments' => $enrollments,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Drop a course enrollment
     */
    public function dropCourse(Student $student, int $enrollmentId, Semester $semester): array
    {
        // Check if registration is still open
        if (!$this->isRegistrationOpen($semester)) {
            return [
                'success' => false,
                'message' => 'Registration period has ended. You cannot drop courses at this time.',
            ];
        }

        $enrollment = Enrollment::where('enrollment_id', $enrollmentId)
            ->where('student_id', $student->student_id)
            ->where('semester_id', $semester->semester_id)
            ->first();

        if (!$enrollment) {
            return [
                'success' => false,
                'message' => 'Enrollment not found.',
            ];
        }

        if ($enrollment->status === 'dropped') {
            return [
                'success' => false,
                'message' => 'This enrollment has already been dropped.',
            ];
        }

        $enrollment->update([
            'status' => 'dropped',
        ]);

        return [
            'success' => true,
            'message' => 'Course dropped successfully.',
            'enrollment' => $enrollment,
        ];
    }

    /**
     * Get student's completed courses (passed with acceptable grades)
     */
    private function getCompletedCourses(Student $student): Collection
    {
        return StudentCourseGrade::whereHas('enrollment', function ($query) use ($student) {
            $query->where('student_id', $student->student_id);
        })
            ->whereNotIn('grade_letter', ['F', 'DN', 'W'])
            ->with('enrollment.course')
            ->get()
            ->map(function ($grade) {
                $course = $grade->enrollment->course;
                $course->grade_letter = $grade->grade_letter;
                return $course;
            });
    }

    /**
     * Check if course is a retake for the student
     */
    private function isRetake(Student $student, int $courseId): bool
    {
        return Enrollment::where('student_id', $student->student_id)
            ->where('course_id', $courseId)
            ->exists();
    }

    /**
     * Check if registration is currently open
     */
    public function isRegistrationOpen(Semester $semester): bool
    {
        $now = now();
        return $semester->student_registeration_start_at &&
            $semester->student_registeration_end_at &&
            $now->between(
                $semester->student_registeration_start_at,
                $semester->student_registeration_end_at
            );
    }

    /**
     * Get course sections for a semester
     */
    private function getCourseSections(Course $course, Semester $semester): Collection
    {
        return $course->courseInstructorAssignments()
            ->where('semester_id', $semester->semester_id)
            ->with('instructor')
            ->get()
            ->map(function ($assignment) {
                $instructorName = $assignment->instructor->full_name_arabic
                    ?? ($assignment->instructor->first_name . ' ' . $assignment->instructor->last_name)
                    ?? 'TBA';

                return [
                    'assignment_id' => $assignment->assignment_id,
                    'section_number' => $assignment->section_number,
                    'instructor_name' => $instructorName,
                    'student_count' => $assignment->student_count ?? 0,
                ];
            });
    }

    /**
     * Get maximum courses allowed based on CGPA
     */
    public function getMaxCoursesForStudent(Student $student): int
    {
        $cgpa = $student->cgpa ?? 0;

        if ($cgpa <= 1) {
            return 4;
        } elseif ($cgpa > 1 && $cgpa < 2) {
            return 5;
        } elseif ($cgpa >= 2 && $cgpa < 3) {
            return 6;
        } else {
            return 7;
        }
    }

    /**
     * Get current semester
     */
    public function getCurrentSemester(): ?Semester
    {
        return Semester::where('is_active', true)
            ->first();
    }
}
