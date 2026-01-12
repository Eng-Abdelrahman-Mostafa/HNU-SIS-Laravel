<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/program_requirements.json'));
        $programRequirements = json_decode($json, true);

        foreach ($programRequirements as $requirement) {
            // Get department_id from department_code
            $department = \DB::table('departments')
                ->where('department_code', $requirement['program_code'])
                ->first();

            // Get course_id from course_code
            $course = \DB::table('courses')
                ->where('course_code', $requirement['course_code'])
                ->first();

            // Get level_id from level_number
            $level = \DB::table('academic_levels')
                ->where('level_number', $requirement['level'])
                ->first();

            if ($department && $course && $level) {
                \DB::table('program_requirements')->insert([
                    'department_id' => $department->department_id,
                    'course_id' => $course->course_id,
                    'level_id' => $level->level_id,
                    'requirement_type' => $requirement['requirement_type']
                ]);
            }
        }
    }
}
