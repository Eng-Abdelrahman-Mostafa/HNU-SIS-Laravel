<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/academic_levels.json'));
        $levels = json_decode($json, true);

        foreach ($levels as $level) {
            \DB::table('academic_levels')->insert($level);
        }
    }
}
