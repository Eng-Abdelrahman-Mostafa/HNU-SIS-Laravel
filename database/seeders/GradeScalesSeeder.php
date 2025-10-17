<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeScalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/grade_scales.json'));
        $gradeScales = json_decode($json, true);

        foreach ($gradeScales as $gradeScale) {
            \DB::table('grade_scales')->insert($gradeScale);
        }
    }
}
