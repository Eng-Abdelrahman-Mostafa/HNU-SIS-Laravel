<?php

namespace App\Providers;

use App\Models\AcademicLevel;
use App\Models\Course;
use App\Models\CourseInstructorAssignment;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\GradeScale;
use App\Models\Instructor;
use App\Models\Prerequisite;
use App\Models\ProgramRequirement;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use App\Policies\AcademicLevelPolicy;
use App\Policies\CourseInstructorAssignmentPolicy;
use App\Policies\CoursePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\GradeScalePolicy;
use App\Policies\InstructorPolicy;
use App\Policies\PrerequisitePolicy;
use App\Policies\ProgramRequirementPolicy;
use App\Policies\SemesterPolicy;
use App\Policies\StudentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Department::class => DepartmentPolicy::class,
        AcademicLevel::class => AcademicLevelPolicy::class,
        GradeScale::class => GradeScalePolicy::class,
        Semester::class => SemesterPolicy::class,
        Student::class => StudentPolicy::class,
        Instructor::class => InstructorPolicy::class,
        Course::class => CoursePolicy::class,
        Prerequisite::class => PrerequisitePolicy::class,
        ProgramRequirement::class => ProgramRequirementPolicy::class,
        CourseInstructorAssignment::class => CourseInstructorAssignmentPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a super-admin gate
        Gate::before(function ($user, $ability) {
            // Super admins can do anything
            if ($user->hasRole('super_admin')) {
                return true;
            }

            return null; // Continue checking other gates/policies
        });

        // Define additional gates for custom permissions
        Gate::define('access_admin_panel', function (User $user) {
            return $user->can('access_admin_panel');
        });

        Gate::define('view_dashboard', function (User $user) {
            return $user->can('view_dashboard');
        });

        Gate::define('view_reports', function (User $user) {
            return $user->can('view_reports');
        });

        Gate::define('export_reports', function (User $user) {
            return $user->can('export_reports');
        });

        Gate::define('manage_settings', function (User $user) {
            return $user->can('manage_settings');
        });

        Gate::define('manage_roles', function (User $user) {
            return $user->can('manage_roles');
        });

        Gate::define('manage_permissions', function (User $user) {
            return $user->can('manage_permissions');
        });

        Gate::define('view_activity_logs', function (User $user) {
            return $user->can('view_activity_logs');
        });

        Gate::define('manage_academic_calendar', function (User $user) {
            return $user->can('manage_academic_calendar');
        });

        Gate::define('approve_registrations', function (User $user) {
            return $user->can('approve_registrations');
        });

        Gate::define('manage_grades', function (User $user) {
            return $user->can('manage_grades');
        });

        Gate::define('view_student_transcripts', function (User $user) {
            return $user->can('view_student_transcripts');
        });
    }
}
