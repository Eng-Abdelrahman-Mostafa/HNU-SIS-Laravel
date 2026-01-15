<?php

namespace App\Filament\Student\Pages;

use App\Models\Enrollment;
use App\Models\Semester;
use App\Services\CourseRegistrationService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class CourseRegistration extends Page
{
    protected string $view = 'filament.student.pages.course-registration';

    public static function getNavigationLabel(): string
    {
        return __('filament.course_registration.title');
    }

    public function getTitle(): string
    {
        return __('filament.course_registration.title');
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public ?Semester $currentSemester = null;

    public Collection $availableCourses;

    public Collection $currentEnrollments;

    public array $selectedCourses = [];

    public int $maxCourses = 0;

    public bool $isRegistrationOpen = false;

    public Collection $eligibleCourses;

    public Collection $ineligibleCourses;

    public function mount(): void
    {
        $service = app(CourseRegistrationService::class);
        $student = auth()->user();

        $this->currentSemester = $service->getCurrentSemester();

        if (!$this->currentSemester) {
            return;
        }

        $this->isRegistrationOpen = $service->isRegistrationOpen($this->currentSemester);
        $this->maxCourses = $service->getMaxCoursesForStudent($student);

        // Get available courses
        $this->availableCourses = $service->getAvailableCoursesForStudent(
            $student,
            $this->currentSemester
        );

        // Separate eligible and ineligible courses
        $this->eligibleCourses = $this->availableCourses->filter(fn($item) => $item['is_eligible']);
        $this->ineligibleCourses = $this->availableCourses->filter(fn($item) => !$item['is_eligible']);

        // Get current enrollments
        $this->currentEnrollments = Enrollment::where('student_id', $student->student_id)
            ->where('semester_id', $this->currentSemester->semester_id)
            ->where('status', '!=', 'dropped')
            ->with(['course', 'semester'])
            ->get();
    }

    public function registerCourses(): void
    {
        $remainingSlots = $this->maxCourses - $this->currentEnrollments->count();
        $availableCoursesCount = $this->eligibleCourses->count();
        $requiredMinimum = min($remainingSlots, $availableCoursesCount);

        if (empty($this->selectedCourses)) {
            Notification::make()
                ->warning()
                ->title(__('filament.course_registration.no_courses_selected'))
                ->body(__('filament.course_registration.no_courses_selected_description'))
                ->send();

            return;
        }

        // Check if student selected minimum required courses
        if (count($this->selectedCourses) < $requiredMinimum) {
            Notification::make()
                ->danger()
                ->title(__('filament.course_registration.minimum_courses_required'))
                ->body(__('filament.course_registration.minimum_courses_required_description', [
                    'required' => $requiredMinimum,
                    'selected' => count($this->selectedCourses),
                ]))
                ->send();

            return;
        }

        // Check if adding these courses would exceed the limit
        $totalCourses = $this->currentEnrollments->count() + count($this->selectedCourses);
        if ($totalCourses > $this->maxCourses) {
            Notification::make()
                ->danger()
                ->title(__('filament.course_registration.course_limit_exceeded'))
                ->body(__('filament.course_registration.course_limit_exceeded_description', [
                    'max' => $this->maxCourses,
                    'total' => $totalCourses,
                ]))
                ->send();

            return;
        }

        $service = app(CourseRegistrationService::class);
        $result = $service->registerStudentForCourses(
            auth()->user(),
            $this->selectedCourses,
            $this->currentSemester
        );

        if ($result['success']) {
            Notification::make()
                ->success()
                ->title(__('filament.course_registration.registration_successful'))
                ->body($result['message'])
                ->send();

            $this->selectedCourses = [];
            $this->mount();
        } else {
            $message = $result['message'];

            if (isset($result['validation']['ineligible_courses'])) {
                $message .= "\n\n" . __('Ineligible courses') . ":\n";
                foreach ($result['validation']['ineligible_courses'] as $ineligible) {
                    $courseName = $ineligible['course']->course_name ?? 'Unknown';
                    $reasons = implode(', ', $ineligible['reasons']);
                    $message .= "- {$courseName}: {$reasons}\n";
                }
            }

            Notification::make()
                ->danger()
                ->title(__('filament.course_registration.registration_failed'))
                ->body($message)
                ->send();
        }
    }

    public function dropCourse(int $enrollmentId): void
    {
        $service = app(CourseRegistrationService::class);
        $result = $service->dropCourse(
            auth()->user(),
            $enrollmentId,
            $this->currentSemester
        );

        if ($result['success']) {
            Notification::make()
                ->success()
                ->title(__('filament.course_registration.course_dropped'))
                ->body($result['message'])
                ->send();

            $this->mount();
        } else {
            Notification::make()
                ->danger()
                ->title(__('filament.course_registration.drop_failed'))
                ->body($result['message'])
                ->send();
        }
    }

    public function getHeading(): string
    {
        if (!$this->currentSemester) {
            return __('filament.course_registration.no_active_semester');
        }

        return __('filament.course_registration.heading', [
            'semester' => $this->currentSemester->semester_name,
            'year' => $this->currentSemester->year,
        ]);
    }

    public function getMinimumRequiredCoursesProperty(): int
    {
        $remainingSlots = $this->maxCourses - $this->currentEnrollments->count();
        $availableCoursesCount = $this->eligibleCourses->count();

        return min($remainingSlots, $availableCoursesCount);
    }
}
