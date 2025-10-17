<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseInstructorAssignmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/course_instructor_assignments.json'));
        $assignments = json_decode($json, true);

        foreach ($assignments as $assignment) {
            // Get course_id from course_code
            $course = \DB::table('courses')
                ->where('course_code', $assignment['course_code'])
                ->first();

            // Get instructor_id from instructor_code
            $instructor = \DB::table('instructors')
                ->where('instructor_code', $assignment['instructor_code'])
                ->first();

            // Get semester_id from semester_code
            $semester = \DB::table('semesters')
                ->where('semester_code', $assignment['semester_code'])
                ->first();

            if ($course && $instructor) {
                \DB::table('course_instructor_assignments')->insert([
                    'course_id' => $course->course_id,
                    'instructor_id' => $instructor->instructor_id,
                    'semester_id' => $semester ? $semester->semester_id : null,
                    'section_number' => $assignment['section_number'],
                    'student_count' => $assignment['student_count']
                ]);
            }
        }
    }
}
