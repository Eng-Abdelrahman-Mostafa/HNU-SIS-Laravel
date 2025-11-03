<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models Configuration
    |--------------------------------------------------------------------------
    |
    | Define all models that need permissions. Each model will have CRUD
    | permissions automatically generated.
    |
    */

    'models' => [
        'User' => [
            'name' => 'User',
            'permissions' => ['view', 'create', 'update', 'delete', 'restore', 'force_delete'],
        ],
        'Department' => [
            'name' => 'Department',
            'permissions' => ['view', 'create', 'update', 'delete'],
        ],
        'AcademicLevel' => [
            'name' => 'AcademicLevel',
            'permissions' => ['view', 'create', 'update', 'delete'],
        ],
        'GradeScale' => [
            'name' => 'GradeScale',
            'permissions' => ['view', 'create', 'update', 'delete'],
        ],
        'Semester' => [
            'name' => 'Semester',
            'permissions' => ['view', 'create', 'update', 'delete'],
        ],
        'Student' => [
            'name' => 'Student',
            'permissions' => ['view', 'create', 'update', 'delete', 'restore', 'force_delete', 'import', 'export'],
        ],
        'Instructor' => [
            'name' => 'Instructor',
            'permissions' => ['view', 'create', 'update', 'delete', 'restore', 'force_delete'],
        ],
        'Course' => [
            'name' => 'Course',
            'permissions' => ['view', 'create', 'update', 'delete', 'import', 'export'],
        ],
        'Prerequisite' => [
            'name' => 'Prerequisite',
            'permissions' => ['view', 'create', 'update', 'delete'],
        ],
        'ProgramRequirement' => [
            'name' => 'ProgramRequirement',
            'permissions' => ['view', 'create', 'update', 'delete'],
        ],
        'CourseInstructorAssignment' => [
            'name' => 'CourseInstructorAssignment',
            'permissions' => ['view', 'create', 'update', 'delete'],
        ],
        'Enrollment' => [
            'name' => 'Enrollment',
            'permissions' => ['view', 'create', 'update', 'delete', 'approve', 'reject', 'export'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Permissions
    |--------------------------------------------------------------------------
    |
    | Define custom permissions that don't belong to specific models.
    |
    */

    'additional_permissions' => [
        'access_admin_panel' => 'Access Admin Panel',
        'view_dashboard' => 'View Dashboard',
        'view_reports' => 'View Reports',
        'export_reports' => 'Export Reports',
        'manage_settings' => 'Manage Settings',
        'manage_roles' => 'Manage Roles',
        'manage_permissions' => 'Manage Permissions',
        'view_activity_logs' => 'View Activity Logs',
        'manage_academic_calendar' => 'Manage Academic Calendar',
        'approve_registrations' => 'Approve Registrations',
        'manage_grades' => 'Manage Grades',
        'view_student_transcripts' => 'View Student Transcripts',
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles Configuration
    |--------------------------------------------------------------------------
    |
    | Define default roles and their permissions.
    |
    */

    'roles' => [
        'super_admin' => [
            'name' => 'super_admin',
            'guard_name' => 'web',
            'permissions' => '*', // All permissions
            'description' => 'Super Administrator with full access',
        ],

        'admin' => [
            'name' => 'admin',
            'guard_name' => 'web',
            'permissions' => [
                // User Management
                'view_User',
                'create_User',
                'update_User',
                'delete_User',

                // Department Management
                'view_Department',
                'create_Department',
                'update_Department',
                'delete_Department',

                // Academic Level Management
                'view_AcademicLevel',
                'create_AcademicLevel',
                'update_AcademicLevel',
                'delete_AcademicLevel',

                // Grade Scale Management
                'view_GradeScale',
                'create_GradeScale',
                'update_GradeScale',
                'delete_GradeScale',

                // Semester Management
                'view_Semester',
                'create_Semester',
                'update_Semester',
                'delete_Semester',

                // Student Management
                'view_Student',
                'create_Student',
                'update_Student',
                'delete_Student',
                'import_Student',
                'export_Student',

                // Instructor Management
                'view_Instructor',
                'create_Instructor',
                'update_Instructor',
                'delete_Instructor',

                // Course Management
                'view_Course',
                'create_Course',
                'update_Course',
                'delete_Course',
                'import_Course',
                'export_Course',

                // Prerequisite Management
                'view_Prerequisite',
                'create_Prerequisite',
                'update_Prerequisite',
                'delete_Prerequisite',

                // Program Requirement Management
                'view_ProgramRequirement',
                'create_ProgramRequirement',
                'update_ProgramRequirement',
                'delete_ProgramRequirement',

                // Course Instructor Assignment Management
                'view_CourseInstructorAssignment',
                'create_CourseInstructorAssignment',
                'update_CourseInstructorAssignment',
                'delete_CourseInstructorAssignment',

                // Enrollment Management
                'view_Enrollment',
                'create_Enrollment',
                'update_Enrollment',
                'delete_Enrollment',
                'approve_Enrollment',
                'reject_Enrollment',
                'export_Enrollment',

                // Additional Permissions
                'access_admin_panel',
                'view_dashboard',
                'view_reports',
                'export_reports',
                'manage_settings',
                'manage_roles',
                'manage_permissions',
                'view_activity_logs',
                'manage_academic_calendar',
                'approve_registrations',
                'manage_grades',
                'view_student_transcripts',
            ],
            'description' => 'Administrator with comprehensive access',
        ],

        'registrar' => [
            'name' => 'registrar',
            'guard_name' => 'web',
            'permissions' => [
                // Student Management
                'view_Student',
                'create_Student',
                'update_Student',
                'import_Student',
                'export_Student',

                // Enrollment Management
                'view_Enrollment',
                'create_Enrollment',
                'update_Enrollment',
                'approve_Enrollment',
                'reject_Enrollment',
                'export_Enrollment',

                // Course Management (Read-only)
                'view_Course',
                'export_Course',

                // Department Management (Read-only)
                'view_Department',

                // Academic Level Management (Read-only)
                'view_AcademicLevel',

                // Semester Management
                'view_Semester',
                'create_Semester',
                'update_Semester',

                // Additional Permissions
                'access_admin_panel',
                'view_dashboard',
                'view_reports',
                'export_reports',
                'manage_academic_calendar',
                'approve_registrations',
                'view_student_transcripts',
            ],
            'description' => 'Registrar Office staff managing student registrations',
        ],

        'department_head' => [
            'name' => 'department_head',
            'guard_name' => 'web',
            'permissions' => [
                // Department Management (Limited)
                'view_Department',
                'update_Department',

                // Instructor Management
                'view_Instructor',
                'create_Instructor',
                'update_Instructor',

                // Course Management
                'view_Course',
                'create_Course',
                'update_Course',
                'delete_Course',

                // Prerequisite Management
                'view_Prerequisite',
                'create_Prerequisite',
                'update_Prerequisite',
                'delete_Prerequisite',

                // Program Requirement Management
                'view_ProgramRequirement',
                'create_ProgramRequirement',
                'update_ProgramRequirement',
                'delete_ProgramRequirement',

                // Course Instructor Assignment Management
                'view_CourseInstructorAssignment',
                'create_CourseInstructorAssignment',
                'update_CourseInstructorAssignment',
                'delete_CourseInstructorAssignment',

                // Student Management (Read-only)
                'view_Student',
                'export_Student',

                // Enrollment Management (Read-only)
                'view_Enrollment',
                'export_Enrollment',

                // Additional Permissions
                'access_admin_panel',
                'view_dashboard',
                'view_reports',
                'export_reports',
            ],
            'description' => 'Department Head managing department resources',
        ],

        'instructor' => [
            'name' => 'instructor',
            'guard_name' => 'web',
            'permissions' => [
                // Course Management (Read-only for assigned courses)
                'view_Course',

                // Student Management (Read-only)
                'view_Student',

                // Enrollment Management (Read-only for own courses)
                'view_Enrollment',

                // Grade Management
                'manage_grades',

                // Additional Permissions
                'access_admin_panel',
                'view_dashboard',
            ],
            'description' => 'Instructor managing their assigned courses',
        ],

        'academic_advisor' => [
            'name' => 'academic_advisor',
            'guard_name' => 'web',
            'permissions' => [
                // Student Management
                'view_Student',
                'update_Student',

                // Enrollment Management
                'view_Enrollment',
                'create_Enrollment',
                'approve_Enrollment',

                // Course Management (Read-only)
                'view_Course',

                // Department Management (Read-only)
                'view_Department',

                // Program Requirement Management (Read-only)
                'view_ProgramRequirement',

                // Additional Permissions
                'access_admin_panel',
                'view_dashboard',
                'view_student_transcripts',
            ],
            'description' => 'Academic Advisor helping students with course selection',
        ],

        'data_entry' => [
            'name' => 'data_entry',
            'guard_name' => 'web',
            'permissions' => [
                // Student Management
                'view_Student',
                'create_Student',
                'update_Student',

                // Course Management
                'view_Course',
                'create_Course',
                'update_Course',

                // Instructor Management
                'view_Instructor',
                'create_Instructor',
                'update_Instructor',

                // Department Management (Read-only)
                'view_Department',

                // Additional Permissions
                'access_admin_panel',
                'view_dashboard',
            ],
            'description' => 'Data Entry staff for basic CRUD operations',
        ],

        'viewer' => [
            'name' => 'viewer',
            'guard_name' => 'web',
            'permissions' => [
                // Read-only access to all models
                'view_User',
                'view_Department',
                'view_AcademicLevel',
                'view_GradeScale',
                'view_Semester',
                'view_Student',
                'view_Instructor',
                'view_Course',
                'view_Prerequisite',
                'view_ProgramRequirement',
                'view_CourseInstructorAssignment',
                'view_Enrollment',

                // Additional Permissions
                'access_admin_panel',
                'view_dashboard',
                'view_reports',
            ],
            'description' => 'Read-only access to the system',
        ],

        'panel_user' => [
            'name' => 'panel_user',
            'guard_name' => 'web',
            'permissions' => [
                'access_admin_panel',
                'view_dashboard',
            ],
            'description' => 'Basic panel access',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Naming Convention
    |--------------------------------------------------------------------------
    |
    | Define how permissions should be named.
    | Format: {action}_{model}
    |
    */

    'permission_format' => '{action}_{model}',

    /*
    |--------------------------------------------------------------------------
    | Default Guard
    |--------------------------------------------------------------------------
    |
    | Default guard name for permissions and roles.
    |
    */

    'guard_name' => 'web',
];
