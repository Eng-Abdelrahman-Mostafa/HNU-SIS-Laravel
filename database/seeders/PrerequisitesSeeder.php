<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrerequisitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/prerequisites.json'));
        $prerequisites = json_decode($json, true);

        foreach ($prerequisites as $prerequisite) {
            // Get course_id from course_code
            $course = \DB::table('courses')
                ->where('course_code', $prerequisite['course_code'])
                ->first();

            // Get prerequisite_course_id from prerequisite_code
            $prereqCourse = \DB::table('courses')
                ->where('course_code', $prerequisite['prerequisite_code'])
                ->first();

            if ($course && $prereqCourse) {
                \DB::table('prerequisites')->insert([
                    'course_id' => $course->course_id,
                    'prerequisite_course_id' => $prereqCourse->course_id
                ]);
            }
        }
    }
}
