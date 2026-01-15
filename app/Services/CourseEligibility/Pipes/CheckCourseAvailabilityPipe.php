<?php

namespace App\Services\CourseEligibility\Pipes;

use App\Contracts\CourseEligibilityPipe;
use App\DTOs\CourseEligibilityContext;
use App\Models\CourseInstructorAssignment;

class CheckCourseAvailabilityPipe implements CourseEligibilityPipe
{
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext
    {
        // Check if course is available in current semester
        $isAvailable = CourseInstructorAssignment::where('course_id', $context->course->course_id)
            ->where('semester_id', $context->semester->semester_id)
            ->exists();

        if (!$isAvailable) {
            $context->addBlockingReason(
                __('filament.course_registration.not_available')
            );
        }

        return $next($context);
    }
}
