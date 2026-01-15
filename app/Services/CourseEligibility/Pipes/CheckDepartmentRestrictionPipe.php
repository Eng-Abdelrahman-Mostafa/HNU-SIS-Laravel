<?php

namespace App\Services\CourseEligibility\Pipes;

use App\Contracts\CourseEligibilityPipe;
use App\DTOs\CourseEligibilityContext;

class CheckDepartmentRestrictionPipe implements CourseEligibilityPipe
{
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext
    {
        // If course is department-specific (course_type 'M' = Major), check if student is in same department
        // M = Major/Department, E = Elective, G = General
        if ($context->course->course_type === 'M' && $context->course->department_id) {
            if ($context->course->department_id !== $context->student->department_id) {
                $context->addBlockingReason(
                    __('filament.course_registration.department_restricted', [
                        'department' => $context->course->department->department_name
                    ])
                );
            }
        }

        // Students in level 3 or 4 should only register for courses in their department
        // This ensures senior students focus on their department/major requirements
        $studentLevel = $context->student->current_level_id;

        if (in_array($studentLevel, [3, 4]) && $context->course->department_id) {
            if ($context->course->department_id !== $context->student->department_id) {
                $levelName = $context->student->academicLevel->level_name ?? "Level {$studentLevel}";
                $context->addBlockingReason(
                    __('filament.course_registration.level_department_restricted', [
                        'level' => $levelName,
                        'department' => $context->student->department->department_name
                    ])
                );
            }
        }

        return $next($context);
    }
}
