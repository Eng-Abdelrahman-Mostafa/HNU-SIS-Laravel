<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/departments.json'));
        $departments = json_decode($json, true);

        foreach ($departments as $department) {
            \DB::table('departments')->insert($department);
        }
    }
}
