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

    'hnu_full_name' => 'Helwan National University',
    // Sections
    'sections' => [
        'department_information' => 'Department Information',
        'academic_level_information' => 'Academic Level Information',
        'grade_scale_information' => 'Grade Scale Information',
        'semester_information' => 'Semester Information',
        'student_registration_information' => 'Student Registration Information',
        'instructor_information' => 'Instructor Information',
        'course_information' => 'Course Information',
        'student_information' => 'Student Information',
        'prerequisite_information' => 'Prerequisite Information',
        'program_requirement_information' => 'Program Requirement Information',
        'assignment_information' => 'Assignment Information',
        'enrollment_information' => 'Enrollment Information',
    ],

    // Descriptions
    'descriptions' => [
        'department_info' => 'Fill in the department details to create or update a department',
        'academic_level_info' => 'Define the academic levels and their credit hour requirements',
        'grade_scale_info' => 'Set up grading scales with letter grades and point values',
        'semester_info' => 'Create and manage academic semesters',
        'student_registration_info' => 'Set the student registration period and semester status',
        'instructor_info' => 'Fill in the instructor details including contact information and status',
        'course_info' => 'Enter course information including code, name, and credit hours',
        'student_info' => 'Fill in the student details and academic information',
        'prerequisite_info' => 'Define which courses are prerequisites for other courses',
        'program_requirement_info' => 'Set up program requirements by department and academic level',
        'assignment_info' => 'Assign instructors to courses for a specific semester',
        'enrollment_info' => 'Manage student enrollments in courses',
    ],

    // Field Labels
    'field' => [
        'department_code' => 'Department Code',
        'department_prefix' => 'Department Prefix',
        'department_name' => 'Department Name',
        'department' => 'Department',
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
        'semester' => 'Semester',
        'year' => 'Year',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'enrollments_count' => 'Number of Enrollments',
        'assignments_count' => 'Number of Assignments',
        'is_active' => 'Is Active',
        'student_registeration_start_at' => 'Student Registration Start',
        'student_registeration_end_at' => 'Student Registration End',
        // Instructor Fields
        'instructor' => 'Instructor',
        'instructor_code' => 'Instructor Code',
        'instructor_name' => 'Instructor Name',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'full_name_arabic' => 'Full Name (Arabic)',
        'email' => 'Email',
        'phone' => 'Phone',
        'title' => 'Title',
        // Course Fields
        'course' => 'Course',
        'course_code' => 'Course Code',
        'course_name' => 'Course Name',
        'credit_hours' => 'Credit Hours',
        'course_type' => 'Course Type',
        'category' => 'Category',
        'prerequisites_count' => 'Number of Prerequisites',
        // Student Fields
        'student' => 'Student',
        'student_id' => 'Student ID',
        'student_name' => 'Student Name',
        'current_level' => 'Current Level',
        'academic_level' => 'Academic Level',
        'enrollment_date' => 'Enrollment Date',
        'cgpa' => 'CGPA',
        'credit_hours_completed' => 'Credit Hours Completed',
        'credit_hours_in_progress' => 'Credit Hours In Progress',
        'credit_hours_failed' => 'Credit Hours Failed',
        // Prerequisite Fields
        'prerequisite_course' => 'Prerequisite Course',
        'prerequisite_code' => 'Prerequisite Code',
        'prerequisite_name' => 'Prerequisite Name',
        // Program Requirement Fields
        'requirement_type' => 'Requirement Type',
        // Assignment Fields
        'section_number' => 'Section Number',
        'student_count' => 'Student Count',
        // Enrollment Fields
        'is_retake' => 'Is Retake',
        'comment' => 'Comment',
    ],

    // Helper Text
    'helper_text' => [
        'department_code' => 'Enter a unique code for this department (e.g., CS)',
        'department_prefix' => 'Enter the course prefix for this department (e.g., CSC)',
        'department_name' => 'Enter the full name of the department',
        'department' => 'Select the department',
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
        'semester' => 'Select the semester',
        'year' => 'Academic year',
        'start_date' => 'Date when the semester starts',
        'end_date' => 'Date when the semester ends',
        'is_active' => 'Enable this semester for student operations',
        'student_registeration_start_at' => 'Date and time when students can start registering',
        'student_registeration_end_at' => 'Date and time when student registration closes',
        // Instructor Helper Text
        'instructor_code' => 'Enter a unique code for this instructor',
        'instructor' => 'Select the instructor',
        'first_name' => 'Enter the instructor\'s first name',
        'last_name' => 'Enter the instructor\'s last name',
        'full_name_arabic' => 'Enter the full name in Arabic',
        'email' => 'Enter a valid email address',
        'phone' => 'Enter the phone number',
        'instructor_title' => 'Enter the instructor\'s academic title',
        'instructor_status' => 'Select the instructor\'s current status',
        // Course Helper Text
        'course_code' => 'Enter a unique code for this course (e.g., CSC101)',
        'course_name' => 'Enter the full name of the course',
        'course' => 'Select the course',
        'credit_hours' => 'Number of credit hours for this course',
        'course_type' => 'Select the type of course',
        'course_category' => 'Select the category of the course',
        // Student Helper Text
        'student_id' => 'Enter a unique ID for this student',
        'student' => 'Select the student',
        'current_level' => 'Select the student\'s current academic level',
        'academic_level' => 'Select the academic level',
        'enrollment_date' => 'Date when the student enrolled',
        'cgpa' => 'Cumulative Grade Point Average (0.0 - 4.0)',
        'credit_hours_completed' => 'Total credit hours completed',
        'credit_hours_in_progress' => 'Credit hours currently in progress',
        'credit_hours_failed' => 'Credit hours failed',
        'student_status' => 'Select the student\'s current status',
        // Prerequisite Helper Text
        'prerequisite_course' => 'Select the main course',
        'prerequisite_course_required' => 'Select the required prerequisite course',
        // Program Requirement Helper Text
        'requirement_type' => 'Select whether this course is mandatory or elective',
        // Assignment Helper Text
        'section_number' => 'Enter the section number',
        'student_count' => 'Number of students in this section',
        // Enrollment Helper Text
        'enrollment_status' => 'Select the enrollment status',
        'is_retake' => 'Check if this is a retake course',
    ],

    // Navigation
    'navigation' => [
        'department' => 'Departments',
        'academic_level' => 'Academic Levels',
        'grade_scale' => 'Grade Scales',
        'semester' => 'Semesters',
    ],

    // Navigation Groups
    'navigation_groups' => [
        'academic_structure' => 'Academic Structure',
        'people' => 'People',
        'course_management' => 'Course Management',
        'operations' => 'Operations',
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
        'export_enrollments' => 'Export Enrollments',
    ],

    // Status Values
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'on_leave' => 'On Leave',
        'graduated' => 'Graduated',
        'suspended' => 'Suspended',
    ],

    // Course Types
    'course_type' => [
        'theory' => 'Theory',
        'practical' => 'Practical',
        'mixed' => 'Mixed',
        'M' => 'Mandatory',
        'E' => 'Elective',
        'G' => 'Graduation',
    ],

    // Course Categories
    'category' => [
        'university_requirement' => 'University Requirement',
        'college_requirement' => 'College Requirement',
        'department_requirement' => 'Department Requirement',
        'elective' => 'Elective',
    ],

    // Requirement Types
    'requirement_type' => [
        'mandatory' => 'Mandatory',
        'elective' => 'Elective',
    ],

    // Enrollment Status
    'enrollment_status' => [
        'registered' => 'Registered',
        'withdrawn' => 'Withdrawn',
        'completed' => 'Completed',
    ],

    // Filter Labels
    'filter' => [
        'retake_only' => 'Retake Only',
        'first_attempt' => 'First Attempt',
    ],

    // Grades Import Messages
    'grades_import' => [
        'title' => 'Bulk Import Student Grades',
        'description' => 'Upload an Excel file to import or update student course grades. Required columns: STUDENT_ID, STUDENT_NAME, YEAR, SEMESTER, COURSE_TITLE, MARKS, GRADE_PERCENT, GRADE_LETTER, POINTS, CREDIT_HOURS, ACT_CREDIT_HOURS, COMMENT',
        'submit_button' => 'Start Import',
        'cancel_button' => 'Cancel',
        'file_label' => 'Excel File',
        'file_help' => 'Supported formats: .xls, .xlsx. Course titles should be in format: "ABC 123-Course Name"',
        'started' => 'Import Started',
        'started_message' => 'Your file is being processed. You will be notified when complete.',
        'completed' => 'Grades Imported Successfully',
        'completed_message' => 'Enrollments Created: :created, Updated: :updated, Grades Processed: :grades',
        'errors_count' => 'Errors: :count',
        'failed' => 'Grades Import Failed',
        'file_not_found' => 'File not found. Please try uploading again.',
    ],

    // Dashboard
    'dashboard' => [
        'quick_actions' => 'Quick Actions',
        'quick_actions_description' => 'Jump to common areas of the system.',
        'actions' => [
            'students' => 'Students',
            'students_description' => 'Manage student records',
            'enrollments' => 'Enrollments',
            'enrollments_description' => 'Review and manage enrollments',
            'courses' => 'Courses',
            'courses_description' => 'Browse and edit courses',
            'semesters' => 'Semesters',
            'semesters_description' => 'Manage academic terms',
            'departments' => 'Departments',
            'departments_description' => 'Manage departments',
        ],
        'stats' => [
            'total_students' => 'Total Students',
            'total_departments' => 'Total Departments',
            'active_semester' => 'Active Semester',
            'active_semester_enrollments' => 'Active Semester Enrollments',
            'top_student_status' => 'Top Student Status',
        ],
        'registration_window' => 'Registration Window',
        'semester' => 'Semester',
        'starts' => 'Starts',
        'ends' => 'Ends',
        'open_now' => 'Open now',
        'yes' => 'Yes',
        'no' => 'No',
        'not_set' => 'Not set',
        'not_available' => 'n/a',
        'no_active_semester' => 'No active semester',
        'enrollments_last_14_days' => 'Enrollments (Last 14 Days)',
    ],

    // Common
    'common' => [
        'yes' => 'Yes',
        'no' => 'No',
        'export' => 'Export',
        'download' => 'Download',
    ],

    // Enrollments Export
    'enrollments_export' => [
        'processing' => 'Export Started',
        'processing_message' => 'Your enrollments report is being generated. You will be notified when it is ready for download.',
        'completed' => 'Export Completed',
        'completed_message' => 'Your enrollments report (:filename) is ready for download.',
        'failed' => 'Export Failed',
        'confirmation_message' => 'Are you sure you want to export enrollments for :semester? This will generate an Excel file with all enrollment records.',
        'download' => 'Download Report',
    ],

    // Exports
    'exports' => [
        'enrollments_sheet_title' => 'Enrollments Report',
    ],

    // Course Registration
    'course_registration' => [
        'title' => 'Course Registration',
        'heading' => 'Course Registration - :semester :year',
        'no_active_semester' => 'No Active Semester',
        'no_active_semester_description' => 'There is currently no active semester. Course registration is not available at this time. Please contact your academic advisor for more information.',
        'registration_closed' => 'Registration Period Closed',
        'registration_closed_description' => 'Course registration for :semester :year is currently closed.',
        'registration_opens' => 'Registration Opens',
        'registration_closes' => 'Registration Closes',
        'registration_ends' => 'Registration Ends',
        'not_set' => 'Not set',

        // Blocking Reasons
        'blocking_reasons_title' => 'Blocking Reasons',
        'missing_prerequisites' => 'Missing prerequisites: :prerequisites.',
        'already_enrolled' => 'You are already enrolled in this course for the current semester.',
        'already_completed' => 'You have already completed this course with grade :grade. Retaking is not allowed for passing grades.',
        'not_available' => 'Course is not available in the current semester.',
        'registration_not_configured' => 'Registration period not configured for this semester.',
        'registration_not_started' => 'Registration period has not started yet. Registration opens on :date.',
        'registration_ended' => 'Registration period has ended. Registration closed on :date.',
        'department_restricted' => 'This course is restricted to students in the :department department.',
        'level_department_restricted' => 'As a :level student, you are restricted to courses within your department (:department). This ensures focus on your department requirements.',
        'course_limit_reached' => 'Course limit reached. With CGPA of :cgpa, you can register for a maximum of :max courses. Currently enrolled in :count courses.',

        // Registration Information
        'registration_info' => 'Registration Information',
        'your_cgpa' => 'Your CGPA',
        'max_courses_allowed' => 'Max Courses Allowed',
        'currently_enrolled' => 'Currently Enrolled',
        'remaining_slots' => 'Remaining Slots',

        // Available Courses
        'available_courses' => 'Available Courses',
        'available_courses_description' => 'Select the courses you want to register for this semester. You can select up to :max courses.',
        'unavailable_courses' => 'Unavailable Courses',
        'unavailable_courses_description' => 'The following courses are not available for registration due to the reasons listed.',
        'no_available_courses' => 'No courses available for registration at this time.',
        'no_unavailable_courses' => 'All courses are available for registration.',

        // Course Card
        'credit_hours_short' => 'CH',
        'sections' => 'Sections',
        'section' => 'Section',
        'instructor' => 'Instructor',
        'blocking_reasons' => 'Blocking Reasons',
        'select_course' => 'Select this course',

        // Actions
        'register_courses' => 'Register Selected Courses',
        'drop_course' => 'Drop Course',
        'cancel' => 'Cancel',

        // My Registered Courses
        'my_registered_courses' => 'My Registered Courses',
        'my_registered_courses_description' => 'Below are the courses you are currently registered for this semester.',
        'no_registered_courses' => 'No Courses Registered',
        'no_registered_courses_description' => 'You have not registered for any courses yet.',

        // Messages
        'no_courses_selected' => 'No Courses Selected',
        'no_courses_selected_description' => 'Please select at least one course to register.',
        'minimum_courses_required' => 'Minimum Courses Required',
        'minimum_courses_required_description' => 'You must select at least :required courses. You have selected only :selected courses.',
        'must_select_minimum' => 'You must select at least :required courses',
        'selected_count' => 'Selected: :count of :required required',
        'course_limit_exceeded' => 'Course Limit Exceeded',
        'course_limit_exceeded_description' => 'You can only register for :max courses. You have selected :total courses in total.',
        'registration_successful' => 'Registration Successful',
        'registration_failed' => 'Registration Failed',
        'course_dropped' => 'Course Dropped',
        'drop_failed' => 'Drop Failed',
        'confirm_drop' => 'Are you sure you want to drop this course?',

        // Status
        'enrolled' => 'Enrolled',
        'pending' => 'Pending',
        'dropped' => 'Dropped',
        'retake' => 'Retake',
        'yes' => 'Yes',
        'no' => 'No',
    ],

];
