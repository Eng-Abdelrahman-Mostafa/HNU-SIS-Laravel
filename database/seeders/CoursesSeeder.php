<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/courses.json'));
        $courses = json_decode($json, true);

        foreach ($courses as $course) {
            // Get department_id from department_code
            $department = \DB::table('departments')
                ->where('department_code', $course['department_code'])
                ->first();

            $course['department_id'] = $department ? $department->department_id : null;

            if (isset($course['department_code'])) {
                $course['category'] = $course['department_code'];
                unset($course['department_code']);
            }
            \DB::table('courses')->insert($course);
        }
    }
}
