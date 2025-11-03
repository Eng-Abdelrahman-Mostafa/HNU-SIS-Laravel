<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions Translations (Arabic)
    |--------------------------------------------------------------------------
    |
    | Arabic translations for all permissions in the system.
    | These can be used in Filament resources and throughout the application.
    |
    */

    // Actions
    'actions' => [
        'view' => 'عرض',
        'create' => 'إنشاء',
        'update' => 'تحديث',
        'delete' => 'حذف',
        'restore' => 'استعادة',
        'force_delete' => 'حذف نهائي',
        'import' => 'استيراد',
        'export' => 'تصدير',
        'approve' => 'موافقة',
        'reject' => 'رفض',
    ],

    // Models
    'models' => [
        'User' => 'المستخدم',
        'Users' => 'المستخدمون',
        'Department' => 'القسم',
        'Departments' => 'الأقسام',
        'AcademicLevel' => 'المستوى الدراسي',
        'AcademicLevels' => 'المستويات الدراسية',
        'GradeScale' => 'سلم التقديرات',
        'GradeScales' => 'سلالم التقديرات',
        'Semester' => 'الفصل الدراسي',
        'Semesters' => 'الفصول الدراسية',
        'Student' => 'الطالب',
        'Students' => 'الطلاب',
        'Instructor' => 'المدرس',
        'Instructors' => 'المدرسون',
        'Course' => 'المقرر',
        'Courses' => 'المقررات',
        'Prerequisite' => 'المتطلب السابق',
        'Prerequisites' => 'المتطلبات السابقة',
        'ProgramRequirement' => 'متطلب البرنامج',
        'ProgramRequirements' => 'متطلبات البرنامج',
        'CourseInstructorAssignment' => 'تعيين مدرس المقرر',
        'CourseInstructorAssignments' => 'تعيينات مدرسي المقررات',
        'Enrollment' => 'التسجيل',
        'Enrollments' => 'التسجيلات',
    ],

    // User Permissions
    'user' => [
        'view_User' => 'عرض المستخدمين',
        'create_User' => 'إنشاء مستخدم',
        'update_User' => 'تحديث مستخدم',
        'delete_User' => 'حذف مستخدم',
        'restore_User' => 'استعادة مستخدم',
        'force_delete_User' => 'حذف مستخدم نهائيًا',
    ],

    // Department Permissions
    'department' => [
        'view_Department' => 'عرض الأقسام',
        'create_Department' => 'إنشاء قسم',
        'update_Department' => 'تحديث قسم',
        'delete_Department' => 'حذف قسم',
    ],

    // Academic Level Permissions
    'academic_level' => [
        'view_AcademicLevel' => 'عرض المستويات الدراسية',
        'create_AcademicLevel' => 'إنشاء مستوى دراسي',
        'update_AcademicLevel' => 'تحديث مستوى دراسي',
        'delete_AcademicLevel' => 'حذف مستوى دراسي',
    ],

    // Grade Scale Permissions
    'grade_scale' => [
        'view_GradeScale' => 'عرض سلالم التقديرات',
        'create_GradeScale' => 'إنشاء سلم تقديرات',
        'update_GradeScale' => 'تحديث سلم تقديرات',
        'delete_GradeScale' => 'حذف سلم تقديرات',
    ],

    // Semester Permissions
    'semester' => [
        'view_Semester' => 'عرض الفصول الدراسية',
        'create_Semester' => 'إنشاء فصل دراسي',
        'update_Semester' => 'تحديث فصل دراسي',
        'delete_Semester' => 'حذف فصل دراسي',
    ],

    // Student Permissions
    'student' => [
        'view_Student' => 'عرض الطلاب',
        'create_Student' => 'إنشاء طالب',
        'update_Student' => 'تحديث طالب',
        'delete_Student' => 'حذف طالب',
        'restore_Student' => 'استعادة طالب',
        'force_delete_Student' => 'حذف طالب نهائيًا',
        'import_Student' => 'استيراد الطلاب',
        'export_Student' => 'تصدير الطلاب',
    ],

    // Instructor Permissions
    'instructor' => [
        'view_Instructor' => 'عرض المدرسين',
        'create_Instructor' => 'إنشاء مدرس',
        'update_Instructor' => 'تحديث مدرس',
        'delete_Instructor' => 'حذف مدرس',
        'restore_Instructor' => 'استعادة مدرس',
        'force_delete_Instructor' => 'حذف مدرس نهائيًا',
    ],

    // Course Permissions
    'course' => [
        'view_Course' => 'عرض المقررات',
        'create_Course' => 'إنشاء مقرر',
        'update_Course' => 'تحديث مقرر',
        'delete_Course' => 'حذف مقرر',
        'import_Course' => 'استيراد المقررات',
        'export_Course' => 'تصدير المقررات',
    ],

    // Prerequisite Permissions
    'prerequisite' => [
        'view_Prerequisite' => 'عرض المتطلبات السابقة',
        'create_Prerequisite' => 'إنشاء متطلب سابق',
        'update_Prerequisite' => 'تحديث متطلب سابق',
        'delete_Prerequisite' => 'حذف متطلب سابق',
    ],

    // Program Requirement Permissions
    'program_requirement' => [
        'view_ProgramRequirement' => 'عرض متطلبات البرنامج',
        'create_ProgramRequirement' => 'إنشاء متطلب برنامج',
        'update_ProgramRequirement' => 'تحديث متطلب برنامج',
        'delete_ProgramRequirement' => 'حذف متطلب برنامج',
    ],

    // Course Instructor Assignment Permissions
    'course_instructor_assignment' => [
        'view_CourseInstructorAssignment' => 'عرض تعيينات المدرسين',
        'create_CourseInstructorAssignment' => 'إنشاء تعيين مدرس',
        'update_CourseInstructorAssignment' => 'تحديث تعيين مدرس',
        'delete_CourseInstructorAssignment' => 'حذف تعيين مدرس',
    ],

    // Enrollment Permissions
    'enrollment' => [
        'view_Enrollment' => 'عرض التسجيلات',
        'create_Enrollment' => 'إنشاء تسجيل',
        'update_Enrollment' => 'تحديث تسجيل',
        'delete_Enrollment' => 'حذف تسجيل',
        'approve_Enrollment' => 'الموافقة على التسجيل',
        'reject_Enrollment' => 'رفض التسجيل',
        'export_Enrollment' => 'تصدير التسجيلات',
    ],

    // Additional Permissions
    'additional' => [
        'access_admin_panel' => 'الوصول إلى لوحة الإدارة',
        'view_dashboard' => 'عرض لوحة المعلومات',
        'view_reports' => 'عرض التقارير',
        'export_reports' => 'تصدير التقارير',
        'manage_settings' => 'إدارة الإعدادات',
        'manage_roles' => 'إدارة الأدوار',
        'manage_permissions' => 'إدارة الصلاحيات',
        'view_activity_logs' => 'عرض سجلات النشاط',
        'manage_academic_calendar' => 'إدارة التقويم الأكاديمي',
        'approve_registrations' => 'الموافقة على التسجيلات',
        'manage_grades' => 'إدارة الدرجات',
        'view_student_transcripts' => 'عرض السجلات الأكاديمية للطلاب',
    ],

    // Roles
    'roles' => [
        'super_admin' => [
            'name' => 'مدير النظام الرئيسي',
            'description' => 'مدير رئيسي بصلاحيات كاملة',
        ],
        'admin' => [
            'name' => 'مدير النظام',
            'description' => 'مدير بصلاحيات شاملة',
        ],
        'registrar' => [
            'name' => 'موظف القبول والتسجيل',
            'description' => 'موظف القبول والتسجيل المسؤول عن تسجيل الطلاب',
        ],
        'department_head' => [
            'name' => 'رئيس القسم',
            'description' => 'رئيس القسم المسؤول عن موارد القسم',
        ],
        'instructor' => [
            'name' => 'مدرس',
            'description' => 'مدرس يدير المقررات المسندة إليه',
        ],
        'academic_advisor' => [
            'name' => 'المرشد الأكاديمي',
            'description' => 'مرشد أكاديمي يساعد الطلاب في اختيار المقررات',
        ],
        'data_entry' => [
            'name' => 'موظف إدخال البيانات',
            'description' => 'موظف إدخال البيانات للعمليات الأساسية',
        ],
        'viewer' => [
            'name' => 'مشاهد',
            'description' => 'صلاحية قراءة فقط للنظام',
        ],
        'panel_user' => [
            'name' => 'مستخدم اللوحة',
            'description' => 'وصول أساسي للوحة الإدارة',
        ],
    ],

    // Common Terms
    'common' => [
        'all_permissions' => 'جميع الصلاحيات',
        'no_permissions' => 'لا توجد صلاحيات',
        'select_permissions' => 'اختر الصلاحيات',
        'permission_granted' => 'تم منح الصلاحية',
        'permission_denied' => 'تم رفض الصلاحية',
        'role_assigned' => 'تم تعيين الدور',
        'role_removed' => 'تم إزالة الدور',
    ],

    // Messages
    'messages' => [
        'permission_created' => 'تم إنشاء الصلاحية بنجاح',
        'permission_updated' => 'تم تحديث الصلاحية بنجاح',
        'permission_deleted' => 'تم حذف الصلاحية بنجاح',
        'role_created' => 'تم إنشاء الدور بنجاح',
        'role_updated' => 'تم تحديث الدور بنجاح',
        'role_deleted' => 'تم حذف الدور بنجاح',
        'permissions_synced' => 'تم مزامنة الصلاحيات بنجاح',
    ],

    // Grouped Permissions for Shield UI
    'groups' => [
        'system' => 'النظام',
        'academic' => 'الأكاديمي',
        'users' => 'المستخدمون',
        'departments' => 'الأقسام',
        'students' => 'الطلاب',
        'instructors' => 'المدرسون',
        'courses' => 'المقررات',
        'enrollments' => 'التسجيلات',
        'reports' => 'التقارير',
        'settings' => 'الإعدادات',
    ],

];
