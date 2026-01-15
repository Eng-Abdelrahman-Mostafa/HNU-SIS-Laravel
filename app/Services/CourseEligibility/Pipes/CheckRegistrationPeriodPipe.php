<?php

namespace App\Services\CourseEligibility\Pipes;

use App\Contracts\CourseEligibilityPipe;
use App\DTOs\CourseEligibilityContext;
use Carbon\Carbon;

class CheckRegistrationPeriodPipe implements CourseEligibilityPipe
{
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext
    {
        $now = Carbon::now();
        $registrationStart = $context->semester->student_registeration_start_at;
        $registrationEnd = $context->semester->student_registeration_end_at;

        if (!$registrationStart || !$registrationEnd) {
            $context->addBlockingReason(
                __('filament.course_registration.registration_not_configured')
            );
            return $next($context);
        }

        if ($now->isBefore($registrationStart)) {
            $context->addBlockingReason(
                __('filament.course_registration.registration_not_started', [
                    'date' => $registrationStart->format('Y-m-d H:i')
                ])
            );
        } elseif ($now->isAfter($registrationEnd)) {
            $context->addBlockingReason(
                __('filament.course_registration.registration_ended', [
                    'date' => $registrationEnd->format('Y-m-d H:i')
                ])
            );
        }

        return $next($context);
    }
}
