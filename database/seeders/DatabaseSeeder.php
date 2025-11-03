<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in order based on dependencies
        $this->call([
            PermissionsAndRolesSeeder::class,
            DepartmentsSeeder::class,
            AcademicLevelsSeeder::class,
            GradeScalesSeeder::class,
            SemestersSeeder::class,
            InstructorsSeeder::class,
            CoursesSeeder::class,
            PrerequisitesSeeder::class,
            ProgramRequirementsSeeder::class,
            CourseInstructorAssignmentsSeeder::class,
        ]);

        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
