<?php

namespace App\Services\CourseEligibility\Pipes;

use App\Contracts\CourseEligibilityPipe;
use App\DTOs\CourseEligibilityContext;

class CheckCGPACourseLimitPipe implements CourseEligibilityPipe
{
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext
    {
        $cgpa = $context->student->cgpa ?? 0;
        $maxCourses = $this->getMaxCoursesForCGPA($cgpa);

        // Count current enrollments for this semester (excluding dropped)
        $currentEnrollmentCount = $context->currentEnrollments
            ->where('semester_id', $context->semester->semester_id)
            ->where('status', '!=', 'dropped')
            ->count();

        // Store for later use
        $context->setCurrentEnrollmentCount($currentEnrollmentCount);

        if ($currentEnrollmentCount >= $maxCourses) {
            $context->addBlockingReason(
                __('filament.course_registration.course_limit_reached', [
                    'cgpa' => $cgpa,
                    'max' => $maxCourses,
                    'count' => $currentEnrollmentCount
                ])
            );
        }

        return $next($context);
    }

    private function getMaxCoursesForCGPA(float $cgpa): int
    {
        if ($cgpa <= 1) {
            return 4;
        } elseif ($cgpa > 1 && $cgpa < 2) {
            return 5;
        } elseif ($cgpa >= 2 && $cgpa < 3) {
            return 6;
        } else { // $cgpa >= 3
            return 7;
        }
    }
}
