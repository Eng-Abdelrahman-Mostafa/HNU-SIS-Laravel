<?php

namespace App\Services\CourseEligibility\Pipes;

use App\Contracts\CourseEligibilityPipe;
use App\DTOs\CourseEligibilityContext;

class CheckDuplicateEnrollmentPipe implements CourseEligibilityPipe
{
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext
    {
        // Check if student has already completed this course successfully
        // If they passed, completely exclude this course from being shown
        $hasCompleted = $context->completedCourses
            ->where('course_id', $context->course->course_id)
            ->isNotEmpty();

        if ($hasCompleted) {
            $lastGrade = $context->completedCourses
                ->where('course_id', $context->course->course_id)
                ->first();

            // If student passed the course, mark it to be completely excluded
            if ($lastGrade && in_array($lastGrade->grade_letter, ['A+', 'A', 'B+', 'B', 'C+', 'C'])) {
                $context->setShouldExclude(true);
                return $next($context);
            }
        }

        // Check if student is already enrolled in this course in the current semester
        $isAlreadyEnrolled = $context->currentEnrollments
            ->where('course_id', $context->course->course_id)
            ->where('semester_id', $context->semester->semester_id)
            ->where('status', '!=', 'dropped')
            ->isNotEmpty();

        if ($isAlreadyEnrolled) {
            $context->addBlockingReason(
                __('filament.course_registration.already_enrolled')
            );
        }

        return $next($context);
    }
}
