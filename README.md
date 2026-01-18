# Students Registration System

A Laravel 12 application for managing student course registration with clear eligibility rules and a guided student portal experience.

## Project Overview

This system focuses on the course registration workflow and enforces academic policies before a student can enroll. It uses a pipeline pattern to keep each eligibility rule isolated, testable, and easy to extend.

## Key Features

- Course registration with eligibility checks (registration period, availability, prerequisites, duplicate enrollment)
- CGPA-based course limits
- Department and level-based restrictions
- Retake rules for failed or low-grade courses
- Student portal built with Filament

## Architecture Highlights

The course eligibility logic is implemented as a pipeline of rules in `app/Services/CourseEligibility/Pipes/`. The main service, `app/Services/CourseRegistrationService.php`, composes the pipeline and exposes methods for:

- Listing available courses with eligibility status
- Validating a selected set of courses
- Registering eligible courses and dropping enrollments

For a full walkthrough, see `Docs/COURSE_REGISTRATION_WORKFLOW.md`.

## Domain Model Summary

Core entities and relationships based on `app/Models/`:

- Students belong to a department and academic level, and have many enrollments.
- Courses belong to a department and academic level; they can have prerequisites and instructor assignments.
- Enrollments link students, courses, and semesters; grades are stored separately per enrollment.
- Semesters define registration windows and are linked to enrollments and instructor assignments.
- Departments own courses, instructors, program requirements, and students.

Supporting entities:

- AcademicLevel: level metadata and program requirements.
- ProgramRequirement: links required courses to departments and levels.
- Prerequisite: links a course to its required prerequisite course.
- CourseInstructorAssignment: course, instructor, semester, and section details.
- StudentCourseGrade: marks, grade letter, points, and credit hours per enrollment.
- Instructor: instructor profile and department association.
- GradeScale: grade letter to points/percentage mapping.

## Requirements

- PHP 8.2
- Composer
- Node.js and npm
- A configured database (see `.env`)

## Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Or run the included setup script:

```bash
composer run setup
```

## Development

```bash
composer run dev
```

## Testing

```bash
composer run test
```

## Documentation

- Course registration workflow: `Docs/COURSE_REGISTRATION_WORKFLOW.md`
- Database ERD: `database-erd.md`

## Author

Abdelrahman Mostafa  
LinkedIn: https://www.linkedin.com/in/eng-abdelrahman-mustafa/
