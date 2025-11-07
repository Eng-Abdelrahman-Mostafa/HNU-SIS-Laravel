<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Resource Translations (English)
    |--------------------------------------------------------------------------
    |
    | English translations for Filament resources and form components.
    |
    */

    // Sections
    'sections' => [
        'department_information' => 'Department Information',
        'academic_level_information' => 'Academic Level Information',
        'grade_scale_information' => 'Grade Scale Information',
        'semester_information' => 'Semester Information',
        'student_registration_information' => 'Student Registration Information',
    ],

    // Descriptions
    'descriptions' => [
        'department_info' => 'Fill in the department details to create or update a department',
        'academic_level_info' => 'Define the academic levels and their credit hour requirements',
        'grade_scale_info' => 'Set up grading scales with letter grades and point values',
        'semester_info' => 'Create and manage academic semesters',
        'student_registration_info' => 'Set the student registration period and semester status',
    ],

    // Field Labels
    'field' => [
        'department_code' => 'Department Code',
        'department_prefix' => 'Department Prefix',
        'department_name' => 'Department Name',
        'students_count' => 'Number of Students',
        'courses_count' => 'Number of Courses',
        'instructors_count' => 'Number of Instructors',
        'level_name' => 'Level Name',
        'level_number' => 'Level Number',
        'min_credit_hours' => 'Minimum Credit Hours',
        'max_credit_hours' => 'Maximum Credit Hours',
        'grade_letter' => 'Grade Letter',
        'min_percentage' => 'Minimum Percentage',
        'max_percentage' => 'Maximum Percentage',
        'grade_points' => 'Grade Points',
        'status' => 'Status',
        'semester_code' => 'Semester Code',
        'semester_name' => 'Semester Name',
        'year' => 'Year',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'enrollments_count' => 'Number of Enrollments',
        'assignments_count' => 'Number of Assignments',
        'is_active' => 'Is Active',
        'student_registeration_start_at' => 'Student Registration Start',
        'student_registeration_end_at' => 'Student Registration End',
    ],

    // Helper Text
    'helper_text' => [
        'department_code' => 'Enter a unique code for this department (e.g., CS)',
        'department_prefix' => 'Enter the course prefix for this department (e.g., CSC)',
        'department_name' => 'Enter the full name of the department',
        'level_name' => 'Enter the name of the academic level',
        'level_number' => 'Enter the level sequence number',
        'min_credit_hours' => 'Minimum credit hours for this level',
        'max_credit_hours' => 'Maximum credit hours for this level',
        'grade_letter' => 'Letter grade (A, B, C, D, F, etc.)',
        'min_percentage' => 'Minimum percentage to achieve this grade',
        'max_percentage' => 'Maximum percentage for this grade',
        'grade_points' => 'Grade point value (0.0 - 4.0)',
        'status' => 'Enable or disable this grade scale',
        'semester_code' => 'Unique code for the semester (e.g., F2024)',
        'semester_name' => 'Full name of the semester (e.g., Fall 2024)',
        'year' => 'Academic year',
        'start_date' => 'Date when the semester starts',
        'end_date' => 'Date when the semester ends',
        'is_active' => 'Enable this semester for student operations',
        'student_registeration_start_at' => 'Date and time when students can start registering',
        'student_registeration_end_at' => 'Date and time when student registration closes',
    ],

    // Navigation
    'navigation' => [
        'department' => 'Departments',
        'academic_level' => 'Academic Levels',
        'grade_scale' => 'Grade Scales',
        'semester' => 'Semesters',
    ],

    // Page Titles
    'titles' => [
        'department' => 'Departments',
        'academic_level' => 'Academic Levels',
        'grade_scale' => 'Grade Scales',
        'semester' => 'Semesters',
        'create_department' => 'Create Department',
        'create_academic_level' => 'Create Academic Level',
        'create_grade_scale' => 'Create Grade Scale',
        'create_semester' => 'Create Semester',
        'edit_department' => 'Edit Department',
        'edit_academic_level' => 'Edit Academic Level',
        'edit_grade_scale' => 'Edit Grade Scale',
        'edit_semester' => 'Edit Semester',
        'view_department' => 'View Department',
        'view_academic_level' => 'View Academic Level',
        'view_grade_scale' => 'View Grade Scale',
        'view_semester' => 'View Semester',
    ],

    // Buttons
    'buttons' => [
        'create' => 'Create',
        'save' => 'Save',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'view' => 'View',
        'cancel' => 'Cancel',
    ],

    // Confirmation
    'confirmation' => [
        'delete_title' => 'Delete',
        'delete_message' => 'Are you sure you want to delete this record?',
    ],

    // Actions
    'actions' => [
        'create_department' => 'Create Department',
        'edit_department' => 'Edit Department',
        'delete_department' => 'Delete Department',
        'view_department' => 'View Department',
        'create_academic_level' => 'Create Academic Level',
        'edit_academic_level' => 'Edit Academic Level',
        'delete_academic_level' => 'Delete Academic Level',
        'view_academic_level' => 'View Academic Level',
        'create_grade_scale' => 'Create Grade Scale',
        'edit_grade_scale' => 'Edit Grade Scale',
        'delete_grade_scale' => 'Delete Grade Scale',
        'view_grade_scale' => 'View Grade Scale',
        'create_semester' => 'Create Semester',
        'edit_semester' => 'Edit Semester',
        'delete_semester' => 'Delete Semester',
        'view_semester' => 'View Semester',
    ],

    // Messages
    'messages' => [
        'department_created' => 'Department created successfully',
        'department_updated' => 'Department updated successfully',
        'department_deleted' => 'Department deleted successfully',
        'academic_level_created' => 'Academic level created successfully',
        'academic_level_updated' => 'Academic level updated successfully',
        'academic_level_deleted' => 'Academic level deleted successfully',
        'grade_scale_created' => 'Grade scale created successfully',
        'grade_scale_updated' => 'Grade scale updated successfully',
        'grade_scale_deleted' => 'Grade scale deleted successfully',
        'semester_created' => 'Semester created successfully',
        'semester_updated' => 'Semester updated successfully',
        'semester_deleted' => 'Semester deleted successfully',
    ],

    // Validation
    'validation' => [
        'department_code_unique' => 'This department code already exists',
        'department_prefix_unique' => 'This department prefix already exists',
        'grade_letter_unique' => 'This grade letter already exists',
        'semester_code_unique' => 'This semester code already exists',
    ],

    // Filters
    'filters' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],

    // Table Actions
    'table_actions' => [
        'edit' => 'Edit',
        'view' => 'View',
        'delete' => 'Delete',
    ],

];
