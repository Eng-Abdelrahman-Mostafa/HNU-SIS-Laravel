<?php

namespace Database\Seeders;

use App\Models\AcademicLevel;
use App\Models\Course;
use App\Models\CourseInstructorAssignment;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Prerequisite;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentCourseGrade;
use Illuminate\Database\Seeder;

class CourseRegistrationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create departments
        $csDepartment = Department::firstOrCreate(
            ['department_code' => 'COM'],
            ['department_name' => 'Computer Science', 'department_prefix' => 100]
        );

        $mathDepartment = Department::firstOrCreate(
            ['department_code' => 'BAS'],
            ['department_name' => 'Basic Sciences', 'department_prefix' => 100]
        );

        $csDeptId = $csDepartment->department_id;
        $mathDeptId = $mathDepartment->department_id;

        // Create academic levels
        $level1 = AcademicLevel::firstOrCreate(
            ['level_id' => 1],
            ['level_name' => 'Level 1', 'level_number' => 1]
        );

        $level2 = AcademicLevel::firstOrCreate(
            ['level_id' => 2],
            ['level_name' => 'Level 2', 'level_number' => 2]
        );

        $level3 = AcademicLevel::firstOrCreate(
            ['level_id' => 3],
            ['level_name' => 'Level 3', 'level_number' => 3]
        );

        // Create active semester
        $currentSemester = Semester::firstOrCreate(
            ['semester_code' => '2024-1'],
            [
                'semester_name' => 'FALL',
                'year' => 2024,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->addMonths(4),
                'is_active' => true,
                'student_registeration_start_at' => now()->subDays(7),
                'student_registeration_end_at' => now()->addDays(7),
            ]
        );

        // Create past semester
        $pastSemester = Semester::firstOrCreate(
            ['semester_code' => '2023-2'],
            [
                'semester_name' => 'SPRING',
                'year' => 2023,
                'start_date' => now()->subMonths(8),
                'end_date' => now()->subMonths(4),
                'is_active' => false,
                'student_registeration_start_at' => now()->subMonths(9),
                'student_registeration_end_at' => now()->subMonths(8),
            ]
        );

        // Create instructors
        $instructor1 = Instructor::firstOrCreate(
            ['email' => 'ahmed.ali@university.edu'],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Ali',
                'full_name_arabic' => 'أحمد علي',
                'department_id' => $csDeptId,
            ]
        );

        $instructor2 = Instructor::firstOrCreate(
            ['email' => 'sarah.mohammed@university.edu'],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Mohammed',
                'full_name_arabic' => 'سارة محمد',
                'department_id' => $mathDeptId,
            ]
        );

        // Create courses
        $courses = [
            // Level 1 courses
            [
                'course_code' => 'CS101',
                'course_name' => 'Introduction to Programming',
                'credit_hours' => 3,
                'department_id' => $csDeptId,
                'course_type' => 'M',
                'category' => 'COM',
                'level_id' => 1,
            ],
            [
                'course_code' => 'MATH101',
                'course_name' => 'Calculus I',
                'credit_hours' => 3,
                'department_id' => $mathDeptId,
                'course_type' => 'G',
                'category' => 'GEN',
                'level_id' => 1,
            ],
            [
                'course_code' => 'CS102',
                'course_name' => 'Data Structures',
                'credit_hours' => 3,
                'department_id' => $csDeptId,
                'course_type' => 'M',
                'category' => 'COM',
                'level_id' => 1,
            ],

            // Level 2 courses
            [
                'course_code' => 'CS201',
                'course_name' => 'Object-Oriented Programming',
                'credit_hours' => 3,
                'department_id' => $csDeptId,
                'course_type' => 'M',
                'category' => 'COM',
                'level_id' => 2,
            ],
            [
                'course_code' => 'CS202',
                'course_name' => 'Database Systems',
                'credit_hours' => 3,
                'department_id' => $csDeptId,
                'course_type' => 'M',
                'category' => 'COM',
                'level_id' => 2,
            ],
            [
                'course_code' => 'MATH201',
                'course_name' => 'Discrete Mathematics',
                'credit_hours' => 3,
                'department_id' => $mathDeptId,
                'course_type' => 'G',
                'category' => 'GEN',
                'level_id' => 2,
            ],

            // Level 3 courses
            [
                'course_code' => 'CS301',
                'course_name' => 'Software Engineering',
                'credit_hours' => 3,
                'department_id' => $csDeptId,
                'course_type' => 'M',
                'category' => 'COM',
                'level_id' => 3,
            ],
            [
                'course_code' => 'CS302',
                'course_name' => 'Web Development',
                'credit_hours' => 3,
                'department_id' => $csDeptId,
                'course_type' => 'M',
                'category' => 'DSC',
                'level_id' => 3,
            ],
        ];

        $createdCourses = [];
        foreach ($courses as $courseData) {
            $createdCourses[$courseData['course_code']] = Course::firstOrCreate(
                ['course_code' => $courseData['course_code']],
                $courseData
            );
        }

        // Create prerequisites
        $prerequisites = [
            ['course' => 'CS102', 'prerequisite' => 'CS101'],
            ['course' => 'CS201', 'prerequisite' => 'CS101'],
            ['course' => 'CS202', 'prerequisite' => 'CS102'],
            ['course' => 'CS301', 'prerequisite' => 'CS201'],
            ['course' => 'CS301', 'prerequisite' => 'CS202'],
            ['course' => 'CS302', 'prerequisite' => 'CS201'],
        ];

        foreach ($prerequisites as $prereq) {
            Prerequisite::firstOrCreate([
                'course_id' => $createdCourses[$prereq['course']]->course_id,
                'prerequisite_course_id' => $createdCourses[$prereq['prerequisite']]->course_id,
            ]);
        }

        // Assign courses to current semester
        $currentSemesterAssignments = [
            ['course' => 'CS101', 'instructor' => $instructor1, 'section' => 1],
            ['course' => 'CS102', 'instructor' => $instructor1, 'section' => 1],
            ['course' => 'CS201', 'instructor' => $instructor1, 'section' => 1],
            ['course' => 'CS202', 'instructor' => $instructor1, 'section' => 1],
            ['course' => 'CS301', 'instructor' => $instructor1, 'section' => 1],
            ['course' => 'CS302', 'instructor' => $instructor1, 'section' => 1],
            ['course' => 'MATH101', 'instructor' => $instructor2, 'section' => 1],
            ['course' => 'MATH201', 'instructor' => $instructor2, 'section' => 1],
        ];

        foreach ($currentSemesterAssignments as $assignment) {
            CourseInstructorAssignment::firstOrCreate([
                'course_id' => $createdCourses[$assignment['course']]->course_id,
                'instructor_id' => $assignment['instructor']->instructor_id,
                'semester_id' => $currentSemester->semester_id,
                'section_number' => $assignment['section'],
            ], [
                'student_count' => 0,
            ]);
        }

        // Create test students
        $students = [
            // Student with high CGPA (can take 7 courses)
            [
                'student_id' => 'S001',
                'full_name_arabic' => 'محمد أحمد علي',
                'email' => 's001@student.edu',
                'password_hash' => bcrypt('password'),
                'department_id' => $csDeptId,
                'current_level_id' => 2,
                'cgpa' => 3.75,
                'status' => 'active',
            ],

            // Student with medium CGPA (can take 6 courses)
            [
                'student_id' => 'S002',
                'full_name_arabic' => 'فاطمة حسن',
                'email' => 's002@student.edu',
                'password_hash' => bcrypt('password'),
                'department_id' => $csDeptId,
                'current_level_id' => 2,
                'cgpa' => 2.50,
                'status' => 'active',
            ],

            // Student with low CGPA (can take 4 courses)
            [
                'student_id' => 'S003',
                'full_name_arabic' => 'عبدالله محمود',
                'email' => 's003@student.edu',
                'password_hash' => bcrypt('password'),
                'department_id' => $csDeptId,
                'current_level_id' => 1,
                'cgpa' => 1.50,
                'status' => 'active',
            ],

            // New student (fresh start, CGPA 0.00, can take 4 courses)
            [
                'student_id' => 'S004',
                'full_name_arabic' => 'نورة سعيد',
                'email' => 's004@student.edu',
                'password_hash' => bcrypt('password'),
                'department_id' => $csDeptId,
                'current_level_id' => 1,
                'cgpa' => 0.00,
                'status' => 'active',
            ],
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $createdStudents[$studentData['student_id']] = Student::firstOrCreate(
                ['student_id' => $studentData['student_id']],
                $studentData
            );
        }

        // Add some completed courses for high CGPA student
        $completedEnrollments = [
            [
                'student_id' => 'S001',
                'course_code' => 'CS101',
                'grade' => 'A',
                'points' => 4.0,
            ],
            [
                'student_id' => 'S001',
                'course_code' => 'MATH101',
                'grade' => 'B+',
                'points' => 3.5,
            ],
            [
                'student_id' => 'S001',
                'course_code' => 'CS102',
                'grade' => 'A',
                'points' => 4.0,
            ],
        ];

        foreach ($completedEnrollments as $enrollmentData) {
            $enrollment = Enrollment::firstOrCreate([
                'student_id' => $enrollmentData['student_id'],
                'course_id' => $createdCourses[$enrollmentData['course_code']]->course_id,
                'semester_id' => $pastSemester->semester_id,
            ], [
                'enrollment_date' => $pastSemester->start_date,
                'status' => 'completed',
                'is_retake' => false,
            ]);

            StudentCourseGrade::firstOrCreate([
                'enrollment_id' => $enrollment->enrollment_id,
            ], [
                'marks' => 90,
                'grade_percent' => 90,
                'grade_letter' => $enrollmentData['grade'],
                'points' => $enrollmentData['points'],
                'credit_hours' => 3,
                'act_credit_hours' => 3,
            ]);
        }

        // Add one enrollment for medium CGPA student in current semester
        $currentEnrollment = Enrollment::firstOrCreate([
            'student_id' => 'S002',
            'course_id' => $createdCourses['CS101']->course_id,
            'semester_id' => $currentSemester->semester_id,
        ], [
            'enrollment_date' => now(),
            'status' => 'enrolled',
            'is_retake' => false,
        ]);

        $this->command->info('Course registration test data seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Students:');
        $this->command->info('  S001 (CGPA: 3.75) - Can register for 7 courses, has completed 3 courses');
        $this->command->info('  S002 (CGPA: 2.50) - Can register for 6 courses, already enrolled in 1 course');
        $this->command->info('  S003 (CGPA: 1.50) - Can register for 4 courses only');
        $this->command->info('  S004 (No CGPA) - Can register for 4 courses (new student)');
        $this->command->info('');
        $this->command->info('Login credentials for all students: password');
        $this->command->info('Current semester: Fall 2024 (Registration is OPEN)');
    }
}
