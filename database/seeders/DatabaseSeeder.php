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


        $super_admin=User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);
        $super_admin->assignRole('super_admin');
    }
}
