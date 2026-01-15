<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentCourseGrade;
use App\Services\CourseRegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private CourseRegistrationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CourseRegistrationService::class);
    }

    /** @test */
    public function student_with_low_cgpa_is_limited_to_4_courses()
    {
        $student = Student::factory()->create(['cgpa' => 0.8]);
        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);

        // Create 5 courses
        $courses = Course::factory()->count(5)->create();

        // Try to register for 5 courses (should be blocked at 5th)
        $result = $this->service->validateCourseRegistration(
            $student,
            $courses->pluck('course_id')->toArray(),
            $semester
        );

        $this->assertFalse($result['is_valid']);
        $this->assertEquals(4, $this->service->getMaxCoursesForStudent($student));
    }

    /** @test */
    public function student_with_high_cgpa_can_register_for_7_courses()
    {
        $student = Student::factory()->create(['cgpa' => 3.5]);

        $this->assertEquals(7, $this->service->getMaxCoursesForStudent($student));
    }

    /** @test */
    public function student_cannot_register_outside_registration_period()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->addWeek(),
            'student_registeration_end_at' => now()->addWeeks(2),
        ]);
        $course = Course::factory()->create();

        $eligibility = $this->service->checkCourseEligibility($student, $course, $semester);

        $this->assertFalse($eligibility->isEligible);
        $this->assertContains(
            'Registration period has not started yet',
            $eligibility->getBlockingReasons()[0]
        );
    }

    /** @test */
    public function student_cannot_register_without_prerequisites()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);

        $prerequisite = Course::factory()->create(['course_name' => 'Intro to Programming']);
        $advancedCourse = Course::factory()->create(['course_name' => 'Advanced Programming']);

        // Create prerequisite relationship
        $advancedCourse->prerequisites()->create([
            'prerequisite_course_id' => $prerequisite->course_id,
        ]);

        $eligibility = $this->service->checkCourseEligibility($student, $advancedCourse, $semester);

        $this->assertFalse($eligibility->isEligible);
        $this->assertStringContainsString('Missing prerequisites', $eligibility->getBlockingReasons()[0]);
    }

    /** @test */
    public function student_with_prerequisites_completed_can_register()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);

        $prerequisite = Course::factory()->create();
        $advancedCourse = Course::factory()->create();

        $advancedCourse->prerequisites()->create([
            'prerequisite_course_id' => $prerequisite->course_id,
        ]);

        // Student completed prerequisite
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->student_id,
            'course_id' => $prerequisite->course_id,
            'semester_id' => Semester::factory()->create()->semester_id,
        ]);

        StudentCourseGrade::factory()->create([
            'enrollment_id' => $enrollment->enrollment_id,
            'grade_letter' => 'B',
        ]);

        $eligibility = $this->service->checkCourseEligibility($student, $advancedCourse, $semester);

        // Should only be blocked by course availability (no instructor assignment)
        $this->assertFalse($eligibility->isEligible);
        $this->assertStringNotContainsString('Missing prerequisites', implode('', $eligibility->getBlockingReasons()));
    }

    /** @test */
    public function student_cannot_register_for_same_course_twice()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);
        $course = Course::factory()->create();

        // Already enrolled
        Enrollment::factory()->create([
            'student_id' => $student->student_id,
            'course_id' => $course->course_id,
            'semester_id' => $semester->semester_id,
            'status' => 'enrolled',
        ]);

        $eligibility = $this->service->checkCourseEligibility($student, $course, $semester);

        $this->assertFalse($eligibility->isEligible);
        $this->assertStringContainsString('already enrolled', $eligibility->getBlockingReasons()[0]);
    }

    /** @test */
    public function student_can_drop_course_during_registration_period()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);

        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->student_id,
            'semester_id' => $semester->semester_id,
            'status' => 'enrolled',
        ]);

        $result = $this->service->dropCourse($student, $enrollment->enrollment_id, $semester);

        $this->assertTrue($result['success']);
        $this->assertEquals('dropped', $enrollment->fresh()->status);
    }

    /** @test */
    public function student_cannot_drop_course_outside_registration_period()
    {
        $student = Student::factory()->create();
        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subWeeks(3),
            'student_registeration_end_at' => now()->subWeek(),
        ]);

        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->student_id,
            'semester_id' => $semester->semester_id,
            'status' => 'enrolled',
        ]);

        $result = $this->service->dropCourse($student, $enrollment->enrollment_id, $semester);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Registration period has ended', $result['message']);
    }

    /** @test */
    public function registration_is_open_only_within_period()
    {
        $semester = Semester::factory()->create([
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);

        $this->assertTrue($this->service->isRegistrationOpen($semester));

        $closedSemester = Semester::factory()->create([
            'student_registeration_start_at' => now()->addWeek(),
            'student_registeration_end_at' => now()->addWeeks(2),
        ]);

        $this->assertFalse($this->service->isRegistrationOpen($closedSemester));
    }

    /** @test */
    public function student_in_level_3_or_4_can_only_register_for_department_courses()
    {
        $csDepartment = \App\Models\Department::factory()->create(['department_id' => 'CS']);
        $mathDepartment = \App\Models\Department::factory()->create(['department_id' => 'MATH']);

        // Level 3 student
        $student = Student::factory()->create([
            'current_level_id' => 3,
            'department_id' => 'CS',
        ]);

        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);

        // CS course - should be eligible
        $csCourse = Course::factory()->create([
            'department_id' => 'CS',
            'course_type' => 'M',
        ]);

        // MATH course - should be blocked
        $mathCourse = Course::factory()->create([
            'department_id' => 'MATH',
            'course_type' => 'G',
        ]);

        // Check CS course - should be eligible (except for course availability)
        $csEligibility = $this->service->checkCourseEligibility($student, $csCourse, $semester);
        $this->assertStringNotContainsString('restricted to courses within your department', implode('', $csEligibility->getBlockingReasons()));

        // Check MATH course - should be blocked
        $mathEligibility = $this->service->checkCourseEligibility($student, $mathCourse, $semester);
        $this->assertFalse($mathEligibility->isEligible);
        $this->assertStringContainsString('restricted to courses within your department', implode('', $mathEligibility->getBlockingReasons()));
    }

    /** @test */
    public function student_in_level_1_or_2_can_register_for_non_department_courses()
    {
        $csDepartment = \App\Models\Department::factory()->create(['department_id' => 'CS']);
        $mathDepartment = \App\Models\Department::factory()->create(['department_id' => 'MATH']);

        // Level 2 student
        $student = Student::factory()->create([
            'current_level_id' => 2,
            'department_id' => 'CS',
        ]);

        $semester = Semester::factory()->create([
            'is_active' => true,
            'student_registeration_start_at' => now()->subDay(),
            'student_registeration_end_at' => now()->addDay(),
        ]);

        // MATH course - should NOT be blocked by department restriction
        $mathCourse = Course::factory()->create([
            'department_id' => 'MATH',
            'course_type' => 'G',
        ]);

        $eligibility = $this->service->checkCourseEligibility($student, $mathCourse, $semester);

        // Should only be blocked by course availability, not department restriction
        $this->assertStringNotContainsString('restricted to courses within your department', implode('', $eligibility->getBlockingReasons()));
    }
}
