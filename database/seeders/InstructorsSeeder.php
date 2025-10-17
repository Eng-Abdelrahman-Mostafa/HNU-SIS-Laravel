<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstructorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/instructors.json'));
        $instructors = json_decode($json, true);

        foreach ($instructors as $instructor) {
            // Get department_id from department_code
            $department = \DB::table('departments')
                ->where('department_code', $instructor['department_code'])
                ->first();

            $instructor['department_id'] = $department ? $department->department_id : null;
            unset($instructor['department_code']);

            \DB::table('instructors')->insert($instructor);
        }
    }
}
