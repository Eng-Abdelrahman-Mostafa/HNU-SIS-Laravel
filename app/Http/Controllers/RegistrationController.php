<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistrationService;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    protected $registrationService;

    /**
     * Create a new controller instance.
     *
     * @param RegistrationService $registrationService
     * @return void
     */
    public function __construct(RegistrationService $registrationService)
    {
        $this->middleware('auth:student');
        $this->registrationService = $registrationService;
    }

    /**
     * Display the course registration page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user();

        // Find the "current" semester for registration.
        // In a real app, you'd have a setting for this. Here, we'll find the latest one.
        $currentSemester = Semester::orderBy('year', 'desc')
                                    ->orderBy('start_date', 'desc')
                                    ->first();

        if (!$currentSemester) {
            // Handle case with no semesters in DB
            return view('registration.index', [
                'student' => $student,
                'availableCourses' => collect(),
                'registeredCourses' => collect(),
                'currentSemester' => null,
                'totalRegisteredCredits' => 0
            ]);
        }

        // Get all eligible courses
        $eligibleCourses = $this->registrationService->getEligibleCourses($student, $currentSemester);

        // Get courses student is *already* registered for this semester
        $registeredCourses = Enrollment::where('student_id', $student->student_id)
            ->where('semester_id', $currentSemester->semester_id)
            ->where('status', 'Registered')
            ->with('course')
            ->get();

        $registeredCourseIds = $registeredCourses->pluck('course_id');

        // Available courses are eligible courses that the student is not *already* registered for
        $availableCourses = $eligibleCourses->whereNotIn('course_id', $registeredCourseIds);

        $totalRegisteredCredits = $registeredCourses->sum('course.credit_hours');

        return view('registration.index', [
            'student' => $student,
            'availableCourses' => $availableCourses,
            'registeredCourses' => $registeredCourses,
            'currentSemester' => $currentSemester,
            'totalRegisteredCredits' => $totalRegisteredCredits
        ]);
    }

    /**
     * Store the student's course selections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_ids' => 'sometimes|array',
            'course_ids.*' => 'integer|exists:courses,course_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        /** @var Student $student */
        $student = Auth::guard('student')->user();

        // Find the current semester (must match the logic in index)
        $currentSemester = Semester::orderBy('year', 'desc')
                                    ->orderBy('start_date', 'desc')
                                    ->firstOrFail();

        $selectedCourseIds = $request->input('course_ids', []);

        // Find courses they are already registered for
        $alreadyRegistered = Enrollment::where('student_id', $student->student_id)
            ->where('semester_id', $currentSemester->semester_id)
            ->where('status', 'Registered')
            ->pluck('course_id')
            ->toArray();

        // Courses to add
        $coursesToAdd = array_diff($selectedCourseIds, $alreadyRegistered);
        // Courses to remove
        $coursesToRemove = array_diff($alreadyRegistered, $selectedCourseIds);

        // Add new enrollments
        $newEnrollments = [];
        foreach ($coursesToAdd as $courseId) {
            $newEnrollments[] = [
                'student_id' => $student->student_id,
                'course_id' => $courseId,
                'semester_id' => $currentSemester->semester_id,
                'enrollment_date' => Carbon::now(),
                'status' => 'Registered',
                'is_retake' => $student->hasPassedCourse($courseId),
                'grade_points' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        if (!empty($newEnrollments)) {
            Enrollment::insert($newEnrollments);
        }

        // Remove unselected enrollments
        if (!empty($coursesToRemove)) {
            Enrollment::where('student_id', $student->student_id)
                ->where('semester_id', $currentSemester->semester_id)
                ->whereIn('course_id', $coursesToRemove)
                ->delete();
        }

        return redirect()->route('registration.index')
                         ->with('success', 'Your registration has been updated successfully!');
    }
}
