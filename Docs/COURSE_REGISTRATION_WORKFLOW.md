# Course Registration Workflow Documentation

## Overview

This document describes the comprehensive course registration system implemented for the Students Registration System. The system uses a **pipeline pattern** for extensibility and maintainability, ensuring all constraints and business rules are properly validated before allowing course registration.

## Architecture

### Pipeline Pattern

The system uses Laravel's Pipeline pattern to process course eligibility checks through a series of "pipes". Each pipe represents a single validation rule, making the system:

- **Extensible**: New validation rules can be added by creating new pipe classes
- **Maintainable**: Each rule is isolated in its own class
- **Testable**: Individual rules can be tested independently
- **Readable**: Business logic is clearly separated

### Components

#### 1. DTOs (Data Transfer Objects)

**`CourseEligibilityContext`** (`app/DTOs/CourseEligibilityContext.php`)
- Carries all data needed for eligibility checks
- Contains student, course, semester information
- Tracks blocking reasons and eligibility status
- Stores completed courses and current enrollments

#### 2. Contracts

**`CourseEligibilityPipe`** (`app/Contracts/CourseEligibilityPipe.php`)
- Interface for all eligibility check pipes
- Ensures consistent pipe implementation

#### 3. Eligibility Pipes

All pipes are located in `app/Services/CourseEligibility/Pipes/`:

1. **`CheckRegistrationPeriodPipe`**
   - Validates registration dates
   - Ensures current time is within registration window
   - Blocks: Registration before start date or after end date

2. **`CheckCourseAvailabilityPipe`**
   - Checks if course is offered this semester
   - Validates via `CourseInstructorAssignment` table
   - Blocks: Course not available in current semester

3. **`CheckPrerequisitesPipe`**
   - Validates all prerequisites are completed
   - Checks completed courses against required prerequisites
   - Blocks: Missing any prerequisite courses

4. **`CheckCGPACourseLimitPipe`**
   - Enforces CGPA-based course limits:
     - CGPA ≤ 1: Max 4 courses
     - 1 < CGPA < 2: Max 5 courses
     - 2 ≤ CGPA < 3: Max 6 courses
     - CGPA ≥ 3: Max 7 courses
   - Blocks: Student at or over course limit

5. **`CheckDepartmentRestrictionPipe`**
   - Validates department-specific courses
   - Restricts students in Level 3 or Level 4 to their department courses only
   - Ensures senior students focus on their department/major requirements
   - Blocks: Non-department students from department courses, Level 3/4 students from non-department courses

6. **`CheckDuplicateEnrollmentPipe`**
   - Prevents duplicate enrollments
   - Prevents retaking passed courses
   - Blocks: Already enrolled or successfully completed

#### 4. Main Service

**`CourseRegistrationService`** (`app/Services/CourseRegistrationService.php`)

Main methods:

- `getAvailableCoursesForStudent()`: Returns all courses with eligibility status
- `checkCourseEligibility()`: Runs a course through the validation pipeline
- `validateCourseRegistration()`: Validates multiple courses at once
- `registerStudentForCourses()`: Registers student for eligible courses
- `dropCourse()`: Drops an enrollment (during registration period)
- `isRegistrationOpen()`: Checks if registration is currently open

#### 5. Filament Page

**`CourseRegistration`** (`app/Filament/Student/Pages/CourseRegistration.php`)

Features:
- Dashboard showing CGPA, max courses, enrolled count, remaining slots
- Registration period status and dates
- Table of current enrollments
- Add courses action with eligibility checking
- Drop course action (during registration period)
- Real-time validation and feedback

## Workflow

### 1. Fetching Available Courses

```php
$service = app(CourseRegistrationService::class);
$student = auth()->user();
$semester = $service->getCurrentSemester();

$availableCourses = $service->getAvailableCoursesForStudent($student, $semester);
```

Returns:
```php
[
    [
        'course' => Course,
        'is_eligible' => true/false,
        'blocking_reasons' => [],
        'sections' => [...]
    ],
    // ...
]
```

### 2. Validating Course Selection

```php
$courseIds = [101, 102, 103];
$validation = $service->validateCourseRegistration($student, $courseIds, $semester);
```

Returns:
```php
[
    'is_valid' => true/false,
    'eligible_courses' => [...],
    'ineligible_courses' => [
        [
            'course_id' => 101,
            'course' => Course,
            'reasons' => ['Missing prerequisites: Course A']
        ]
    ]
]
```

### 3. Registering for Courses

```php
$result = $service->registerStudentForCourses($student, $courseIds, $semester);
```

Returns:
```php
[
    'success' => true/false,
    'message' => '...',
    'enrollments' => [...] // if successful
]
```

### 4. Dropping a Course

```php
$result = $service->dropCourse($student, $enrollmentId, $semester);
```

## Business Rules

### CGPA-Based Course Limits

| CGPA Range | Maximum Courses |
|------------|----------------|
| ≤ 1.00 | 4 |
| > 1.00 and < 2.00 | 5 |
| ≥ 2.00 and < 3.00 | 6 |
| ≥ 3.00 | 7 |

### Registration Period

- Registration only allowed between `student_registeration_start_at` and `student_registeration_end_at`
- Students can add or drop courses during this period
- Outside this period, all registration actions are blocked

### Prerequisites

- Students must complete all prerequisites before registering for a course
- Prerequisites are defined in the `prerequisites` table
- Completed courses are those with grades other than F, DN, W

### Department Restrictions

- Department-specific courses only available to department students
- Students in Level 3 or Level 4 are restricted to courses within their department
  - This ensures senior students focus on their department/major requirements
- General/elective courses available to students in Level 1 and Level 2

### Duplicate Prevention

- Cannot enroll in the same course twice in the same semester
- Cannot retake courses passed with grades C or higher
- Can retake failed courses (F) or low-grade courses (D, D+)

### Course Availability

- Only courses with instructor assignments in current semester are available
- Checked via `course_instructor_assignments` table

## Extensibility

### Adding New Validation Rules

1. Create a new pipe class implementing `CourseEligibilityPipe`:

```php
<?php

namespace App\Services\CourseEligibility\Pipes;

use App\Contracts\CourseEligibilityPipe;
use App\DTOs\CourseEligibilityContext;

class CheckNewRulePipe implements CourseEligibilityPipe
{
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext
    {
        // Your validation logic here
        if (!$someCondition) {
            $context->addBlockingReason('Your blocking reason here');
        }

        return $next($context);
    }
}
```

2. Add the pipe to the pipeline in `CourseRegistrationService::checkCourseEligibility()`:

```php
$pipes = [
    CheckRegistrationPeriodPipe::class,
    // ... existing pipes
    CheckNewRulePipe::class, // Add your new pipe
];
```

### Order of Execution

Pipes are executed in the order they appear in the `$pipes` array. Consider placing:
- Quick checks (like date validation) first
- Database-heavy checks later
- Critical blocking checks early to fail fast

## Testing

### Example Test Scenarios

1. **CGPA Constraint**: Student with CGPA 1.5 tries to register for 6 courses
2. **Prerequisites**: Student tries to register for Advanced Course without Basic Course
3. **Registration Period**: Student tries to register outside allowed dates
4. **Department Restriction**: Engineering student tries to register for Medicine course
5. **Duplicate**: Student tries to register for same course twice
6. **Level Requirement**: Level 1 student tries to register for Level 4 course

## Student Panel Features

### Dashboard Metrics

- Current CGPA
- Maximum courses allowed
- Currently enrolled courses count
- Remaining course slots

### Course List Features

- Course code, name, credit hours
- Enrollment status with color-coded badges
- Enrollment date
- Retake indicator
- Drop course action (during registration period)

### Add Courses Modal

- Grid of all available courses
- Blocked courses clearly marked
- Blocking reasons displayed
- Section information for available courses
- Instructor assignments
- Real-time validation

### Registration Status

- Visual indicator (✅ Open / 🔒 Closed)
- Registration period dates
- Countdown or time remaining

## Edge Cases Handled

1. **No Active Semester**: Graceful message, no actions available
2. **No Registration Dates Set**: Registration blocked with appropriate message
3. **Course Limit Reached**: Add courses button disabled
4. **All Courses Blocked**: User can see why each course is blocked
5. **Partial Registration Success**: Clear feedback on which courses succeeded/failed
6. **Concurrent Enrollments**: Database transactions prevent race conditions

## Database Schema Requirements

### Required Tables

- `students`: student_id, cgpa, department_id, current_level_id
- `courses`: course_id, department_id, level_id, course_type
- `enrollments`: enrollment_id, student_id, course_id, semester_id, status
- `semesters`: semester_id, student_registeration_start_at, student_registeration_end_at, is_active
- `prerequisites`: course_id, prerequisite_course_id
- `course_instructor_assignments`: course_id, semester_id, instructor_id, section_number
- `student_course_grades`: enrollment_id, grade_letter, points

## Future Enhancements

Potential additions to the pipeline:

1. **Seat Capacity Check**: Limit enrollments based on section capacity
2. **Time Conflict Check**: Prevent schedule conflicts
3. **Credit Hour Limits**: Maximum credit hours per semester
4. **Hold Check**: Block registration for students with holds
5. **Payment Status**: Require fee payment before registration
6. **Advisor Approval**: Require approval for certain courses
7. **Waitlist**: Allow students to join waitlist for full courses

## API Endpoints (Future)

If you need to expose this functionality via API:

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/courses/available', [CourseRegistrationController::class, 'available']);
    Route::post('/courses/validate', [CourseRegistrationController::class, 'validate']);
    Route::post('/courses/register', [CourseRegistrationController::class, 'register']);
    Route::delete('/courses/drop/{enrollmentId}', [CourseRegistrationController::class, 'drop']);
});
```

## Conclusion

This course registration system provides a robust, maintainable, and extensible solution that handles all specified constraints and edge cases. The pipeline pattern makes it easy to add new validation rules without modifying existing code, following the Open/Closed Principle.
