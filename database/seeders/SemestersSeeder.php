<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemestersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/semesters.json'));
        $semesters = json_decode($json, true);

        foreach ($semesters as $semester) {
            \DB::table('semesters')->insert($semester);
        }
    }
}
