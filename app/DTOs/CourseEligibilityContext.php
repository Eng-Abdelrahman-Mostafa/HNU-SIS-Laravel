<?php

namespace App\DTOs;

use App\Models\Course;
use App\Models\Student;
use App\Models\Semester;
use Illuminate\Support\Collection;

class CourseEligibilityContext
{
    public Student $student;
    public Course $course;
    public Semester $semester;
    public bool $isEligible = true;
    public array $blockingReasons = [];
    public ?int $currentEnrollmentCount = null;
    public Collection $completedCourses;
    public Collection $currentEnrollments;
    public bool $shouldExclude = false;

    public function __construct(
        Student $student,
        Course $course,
        Semester $semester,
        ?Collection $completedCourses = null,
        ?Collection $currentEnrollments = null
    ) {
        $this->student = $student;
        $this->course = $course;
        $this->semester = $semester;
        $this->completedCourses = $completedCourses ?? collect();
        $this->currentEnrollments = $currentEnrollments ?? collect();
    }

    public function addBlockingReason(string $reason): void
    {
        $this->isEligible = false;
        $this->blockingReasons[] = $reason;
    }

    public function hasBlockingReasons(): bool
    {
        return !empty($this->blockingReasons);
    }

    public function getBlockingReasons(): array
    {
        return $this->blockingReasons;
    }

    public function setCurrentEnrollmentCount(int $count): void
    {
        $this->currentEnrollmentCount = $count;
    }

    public function setShouldExclude(bool $shouldExclude): void
    {
        $this->shouldExclude = $shouldExclude;
    }

    public function shouldExclude(): bool
    {
        return $this->shouldExclude;
    }
}
