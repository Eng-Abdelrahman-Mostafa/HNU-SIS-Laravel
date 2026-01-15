<?php

namespace App\Services\CourseEligibility\Pipes;

use App\Contracts\CourseEligibilityPipe;
use App\DTOs\CourseEligibilityContext;

class CheckPrerequisitesPipe implements CourseEligibilityPipe
{
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext
    {
        // Get prerequisites for this course
        $prerequisites = $context->course->prerequisites()->with('prerequisiteCourse')->get();

        if ($prerequisites->isEmpty()) {
            return $next($context);
        }

        $completedCourseIds = $context->completedCourses->pluck('course_id')->toArray();
        $missingPrerequisites = [];

        foreach ($prerequisites as $prerequisite) {
            if (!in_array($prerequisite->prerequisite_course_id, $completedCourseIds)) {
                $missingPrerequisites[] = $prerequisite->prerequisiteCourse->course_name
                    . ' (' . $prerequisite->prerequisiteCourse->course_code . ')';
            }
        }

        if (!empty($missingPrerequisites)) {
            $context->addBlockingReason(
                __('filament.course_registration.missing_prerequisites', [
                    'prerequisites' => implode(', ', $missingPrerequisites)
                ])
            );
        }

        return $next($context);
    }
}
