<?php

namespace App\Contracts;

use App\DTOs\CourseEligibilityContext;

interface CourseEligibilityPipe
{
    /**
     * Process the course eligibility check
     *
     * @param CourseEligibilityContext $context
     * @param \Closure $next
     * @return CourseEligibilityContext
     */
    public function handle(CourseEligibilityContext $context, \Closure $next): CourseEligibilityContext;
}
