<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Semester;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Prerequisite;
use App\Models\CourseInstructorAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistrationService
{
    /**
     * Get all courses a student is eligible to register for in a given semester.
     *
     * @param Student $student
     * @param Semester $semester
     * @return Collection
     */
    public function getEligibleCourses(Student $student, Semester $semester): Collection
    {
        // 1. Get all courses offered in the target semester
        // We must join to get courses that are actually assigned to an instructor
        $offeredCourseIds = Cache::remember("offered_courses_{$semester->semester_id}", 600, function () use ($semester) {
            return CourseInstructorAssignment::where('semester_id', $semester->semester_id)
                ->distinct()
                ->pluck('course_id');
        });

        $allOfferedCourses = Course::whereIn('course_id', $offeredCourseIds)->get();

        // 2. Get all course IDs the student has already passed
        $passedCourseIds = Cache::remember("student_{$student->student_id}_passed_courses", 60, function () use ($student) {
            return Enrollment::where('student_id', $student->student_id)
                // Assumes grade_points > 0 is a pass. Adjust if 'F' has points.
                ->where('grade_points', '>', 0)
                ->pluck('course_id');
        });

        // 3. Get all course prerequisites
        // We get all of them and cache to avoid N+1 queries in the loop
        $allPrerequisites = Cache::remember('all_prerequisites', 3600, function () {
            // Group by the course that *has* the prerequisite
            return Prerequisite::all()->groupBy('course_id');
        });

        // 4. Filter the offered courses based on eligibility
        $eligibleCourses = $allOfferedCourses->filter(function ($course) use ($passedCourseIds, $allPrerequisites) {

            // Check 1: Has the student already passed this course?
            if ($passedCourseIds->contains($course->course_id)) {
                return false;
            }

            // Check 2: Does the student meet the prerequisites?
            $prereqsForThisCourse = $allPrerequisites->get($course->course_id);

            // If the course has no prerequisites, it's eligible (based on this check)
            if (!$prereqsForThisCourse || $prereqsForThisCourse->isEmpty()) {
                return true;
            }

            // Get just the IDs of the required courses
            $requiredCourseIds = $prereqsForThisCourse->pluck('prerequisite_course_id');

            // Check if the student's passed list contains all required courses
            $missingPrereqs = $requiredCourseIds->diff($passedCourseIds);

            // If the diff is empty, the student has all prerequisites
            return $missingPrereqs->isEmpty();
        });

        return $eligibleCourses;
    }
}
