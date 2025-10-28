<?php
// app/Http/Controllers/Admin/CurriculumController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\AcademicLevel;
use App\Models\Course;
use App\Models\ProgramRequirement;
use App\Models\Prerequisite;
use Illuminate\Validation\Rule; // <-- Add this import for Rule::in

class CurriculumController extends Controller
{
    /**
     * Store a new course, along with its requirements and prerequisites.
     */
    public function storeCourse(Request $request)
    {
        // Define the allowed category prefixes based on your migration
        $allowedCategories = ['GEN', 'BAS', 'COM', 'DSC', 'MMD', 'RSE'];

        $request->validate([
            'department_id' => 'required|integer|exists:departments,department_id',
            // Add validation to ensure the course code starts with a valid prefix
            'course_code' => [
                'required',
                'string',
                'unique:courses,course_code',
                'regex:/^([A-Z]{3})\s\d{3}$/', // Validate format like 'XXX 123'
                function ($attribute, $value, $fail) use ($allowedCategories) {
                    $prefix = strtoupper(substr($value, 0, 3));
                    if (!in_array($prefix, $allowedCategories)) {
                        $fail("The $attribute must start with one of the following prefixes: " . implode(', ', $allowedCategories));
                    }
                },
            ],
            'course_name' => 'required|string',
            'credit_hours' => 'required|integer|min:0',
            'level_id' => 'required|integer|exists:academic_levels,level_id',
            'course_type' => ['required', 'string', Rule::in(['M', 'E', 'G'])],
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'integer|exists:courses,course_id',
        ]);

        // --- FIX: Extract the category prefix from the course code ---
        $categoryPrefix = strtoupper(substr($request->course_code, 0, 3));

        // 1. Create the Course
        $course = Course::create([
            'department_id' => $request->department_id,
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'credit_hours' => $request->credit_hours,
            'course_type' => $request->course_type,
            'category' => $categoryPrefix, // <-- Save the extracted prefix
        ]);

        // 2. Create the Program Requirement link
        ProgramRequirement::create([
            'department_id' => $request->department_id,
            'course_id' => $course->course_id,
            'level_id' => $request->level_id,
            'requirement_type' => $request->course_type,
        ]);

        // 3. Link Prerequisites
        if ($request->has('prerequisites')) {
            foreach ($request->prerequisites as $prereqId) {
                Prerequisite::create([
                    'course_id' => $course->course_id,
                    'prerequisite_course_id' => $prereqId,
                ]);
            }
        }

        return back()->with('success-curriculum', "Course '{$course->course_code}' created successfully.");
    }

    /**
     * Store a new Department.
     */
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|unique:departments,department_name',
            'department_code' => 'required|string|unique:departments,department_code',
            'department_prefix' => 'required|string|unique:departments,department_prefix',
        ]);

        Department::create($request->all());

        return back()->with('success-curriculum', "Department '{$request->department_name}' created successfully.");
    }

    /**
     * Store a new Academic Level.
     */
    public function storeLevel(Request $request)
    {
        $request->validate([
            'level_name' => 'required|string|unique:academic_levels,level_name',
            'level_number' => 'required|integer|unique:academic_levels,level_number',
            'min_credit_hours' => 'required|integer',
            'max_credit_hours' => 'required|integer|gt:min_credit_hours',
        ]);

        AcademicLevel::create($request->all());

        return back()->with('success-curriculum', "Level '{$request->level_name}' created successfully.");
    }
}
