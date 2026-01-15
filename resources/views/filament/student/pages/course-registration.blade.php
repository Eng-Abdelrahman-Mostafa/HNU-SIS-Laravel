<x-filament-panels::page>
    @if (!$this->currentSemester)
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-amber-100 to-orange-200 dark:from-amber-900 dark:to-orange-900 mb-4 shadow-lg">
                <x-heroicon-o-exclamation-triangle class="w-7 h-7 text-amber-600 dark:text-amber-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('filament.course_registration.no_active_semester') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                {{ __('filament.course_registration.no_active_semester_description') }}
            </p>
        </div>
    @elseif (!$this->isRegistrationOpen)
        <div class="mb-6">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-400 to-red-600 flex items-center justify-center">
                            <x-heroicon-o-lock-closed class="w-5 h-5 text-white" />
                        </div>
                        <span>{{ __('filament.course_registration.registration_closed') }}</span>
                    </div>
                </x-slot>
                <x-slot name="description">
                    {{ __('filament.course_registration.registration_closed_description', [
                        'semester' => $this->currentSemester->semester_name,
                        'year' => $this->currentSemester->year,
                    ]) }}
                </x-slot>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-4 py-3">
                        <x-heroicon-o-calendar class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                        <span class="font-semibold text-blue-700 dark:text-blue-300">
                            {{ __('filament.course_registration.registration_opens') }}:
                        </span>
                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                            {{ $this->currentSemester->student_registeration_start_at?->format('F j, Y \a\t g:i A') ?? __('filament.course_registration.not_set') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-sm bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 border border-rose-200 dark:border-rose-800 rounded-lg px-4 py-3">
                        <x-heroicon-o-calendar class="w-4 h-4 text-rose-600 dark:text-rose-400 flex-shrink-0" />
                        <span class="font-semibold text-rose-700 dark:text-rose-300">
                            {{ __('filament.course_registration.registration_closes') }}:
                        </span>
                        <span class="text-rose-600 dark:text-rose-400 font-medium">
                            {{ $this->currentSemester->student_registeration_end_at?->format('F j, Y \a\t g:i A') ?? __('filament.course_registration.not_set') }}
                        </span>
                    </div>
                </div>
            </x-filament::section>
        </div>
    @else
        {{-- Registration Information Stats --}}
        <div class="mb-6">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-600 flex items-center justify-center">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-white" />
                        </div>
                        <span>{{ __('filament.course_registration.registration_info') }}</span>
                    </div>
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center shadow-md">
                                <x-heroicon-o-chart-bar class="w-4 h-4 text-white" />
                            </div>
                            <div class="text-xs font-semibold text-blue-700 dark:text-blue-300">
                                {{ __('filament.course_registration.your_cgpa') }}
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                            {{ number_format(auth()->user()->cgpa ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border-2 border-emerald-200 dark:border-emerald-800 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center shadow-md">
                                <x-heroicon-o-academic-cap class="w-4 h-4 text-white" />
                            </div>
                            <div class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                                {{ __('filament.course_registration.max_courses_allowed') }}
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">
                            {{ $this->maxCourses }}
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 border-2 border-violet-200 dark:border-violet-800 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center shadow-md">
                                <x-heroicon-o-book-open class="w-4 h-4 text-white" />
                            </div>
                            <div class="text-xs font-semibold text-violet-700 dark:text-violet-300">
                                {{ __('filament.course_registration.currently_enrolled') }}
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-violet-900 dark:text-violet-100">
                            {{ $this->currentEnrollments->count() }}
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border-2 border-amber-200 dark:border-amber-800 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-400 to-orange-600 flex items-center justify-center shadow-md">
                                <x-heroicon-o-sparkles class="w-4 h-4 text-white" />
                            </div>
                            <div class="text-xs font-semibold text-amber-700 dark:text-amber-300">
                                {{ __('filament.course_registration.remaining_slots') }}
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-amber-900 dark:text-amber-100">
                            {{ max(0, $this->maxCourses - $this->currentEnrollments->count()) }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2 text-sm bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 border border-rose-200 dark:border-rose-800 rounded-lg px-4 py-3">
                        <x-heroicon-o-clock class="w-4 h-4 text-rose-600 dark:text-rose-400 flex-shrink-0" />
                        <span class="font-semibold text-rose-700 dark:text-rose-300">
                            {{ __('filament.course_registration.registration_ends') }}:
                        </span>
                        <span class="text-rose-600 dark:text-rose-400 font-medium">
                            {{ $this->currentSemester->student_registeration_end_at?->format('F j, Y \a\t g:i A') }}
                        </span>
                    </div>
                </div>
            </x-filament::section>
        </div>

        {{-- Available Courses Section --}}
        @if ($this->eligibleCourses->isNotEmpty())
            <div class="mb-6">
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                                <x-heroicon-o-academic-cap class="w-5 h-5 text-white" />
                            </div>
                            <span>{{ __('filament.course_registration.available_courses') }}</span>
                        </div>
                    </x-slot>
                    <x-slot name="description">
                        <div class="space-y-2">
                            <p>{{ __('filament.course_registration.available_courses_description', ['max' => $this->maxCourses]) }}</p>
                            @if ($this->minimumRequiredCourses > 0)
                                <div class="flex items-center gap-2 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" />
                                    <span class="text-sm font-semibold text-amber-700 dark:text-amber-300">
                                        {{ __('filament.course_registration.must_select_minimum', ['required' => $this->minimumRequiredCourses]) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </x-slot>

                    <form wire:submit="registerCourses">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            @foreach ($this->eligibleCourses as $item)
                                @php
                                    $course = $item['course'];
                                    $sections = collect($item['sections']);
                                @endphp

                                <div class="relative group">
                                    <label class="relative flex cursor-pointer">
                                        <input
                                            type="checkbox"
                                            wire:model.live="selectedCourses"
                                            value="{{ $course->course_id }}"
                                            id="course-{{ $course->course_id }}"
                                            class="peer sr-only"
                                        >
                                        <div class="flex-1 border-2 border-gray-200 dark:border-gray-700 rounded-xl p-4 transition-all bg-white dark:bg-gray-800
                                                    peer-checked:border-emerald-500 peer-checked:bg-gradient-to-br peer-checked:from-emerald-50 peer-checked:to-teal-50
                                                    dark:peer-checked:from-emerald-900/20 dark:peer-checked:to-teal-900/20 dark:peer-checked:border-emerald-400
                                                    hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-md">

                                            {{-- Header --}}
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <div class="w-6 h-6 rounded-md bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                                            <x-heroicon-o-book-open class="w-3.5 h-3.5 text-white" />
                                                        </div>
                                                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                                                            {{ $course->course_code }}
                                                        </h3>
                                                    </div>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                                                        {{ $course->course_name }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Course Details --}}
                                            <div class="space-y-2 text-xs">
                                                <div class="flex items-center gap-1.5 px-2 py-1 bg-purple-100 dark:bg-purple-900/30 rounded-md w-fit">
                                                    <x-heroicon-o-clock class="w-3 h-3 text-purple-600 dark:text-purple-400" />
                                                    <span class="text-purple-700 dark:text-purple-300 font-semibold">
                                                        {{ $course->credit_hours }} {{ __('filament.course_registration.credit_hours_short') }}
                                                    </span>
                                                </div>

                                                @if ($sections->isNotEmpty())
                                                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                                        <div class="flex items-center gap-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                                            <x-heroicon-o-user-group class="w-3 h-3 text-indigo-500" />
                                                            {{ __('filament.course_registration.sections') }}:
                                                        </div>
                                                        <div class="space-y-1">
                                                            @foreach ($sections as $section)
                                                                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded px-2 py-1">
                                                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $section['section_number'] }}</span>
                                                                    <span class="text-gray-400">•</span>
                                                                    <span class="truncate">{{ $section['instructor_name'] }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="absolute right-4 top-4 w-5 h-5 rounded border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center
                                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-500 dark:peer-checked:border-emerald-400
                                                    dark:peer-checked:bg-emerald-400 transition-all">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Register Button with Live Validation --}}
                        @if ($this->currentEnrollments->count() < $this->maxCourses)
                            <div class="space-y-3">
                                {{-- Selection Counter --}}
                                <div class="flex items-center justify-between p-4 border-2 rounded-lg
                                    {{ count($selectedCourses) >= $this->minimumRequiredCourses
                                        ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20'
                                        : 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20' }}">
                                    <div class="flex items-center gap-2">
                                        @if (count($selectedCourses) >= $this->minimumRequiredCourses)
                                            <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                                            <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">
                                                {{ __('filament.course_registration.selected_count', [
                                                    'count' => count($selectedCourses),
                                                    'required' => $this->minimumRequiredCourses
                                                ]) }}
                                            </span>
                                        @else
                                            <x-heroicon-o-exclamation-circle class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                                            <span class="text-sm font-semibold text-amber-700 dark:text-amber-300">
                                                {{ __('filament.course_registration.selected_count', [
                                                    'count' => count($selectedCourses),
                                                    'required' => $this->minimumRequiredCourses
                                                ]) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-2xl font-bold
                                        {{ count($selectedCourses) >= $this->minimumRequiredCourses
                                            ? 'text-emerald-700 dark:text-emerald-300'
                                            : 'text-amber-700 dark:text-amber-300' }}">
                                        {{ count($selectedCourses) }}/{{ $this->minimumRequiredCourses }}
                                    </div>
                                </div>

                                {{-- Register Button --}}
                                <div class="flex justify-end">
                                    <x-filament::button
                                        type="submit"
                                        color="success"
                                        icon="heroicon-o-check-circle"
                                        size="lg"
                                        class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700"
                                    >
                                        {{ __('filament.course_registration.register_courses') }}
                                    </x-filament::button>
                                </div>
                            </div>
                        @endif
                    </form>
                </x-filament::section>
            </div>
        @endif

        {{-- Unavailable Courses Section --}}
        @if ($this->ineligibleCourses->isNotEmpty())
            <div class="mb-6">
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-rose-400 to-red-600 flex items-center justify-center">
                                <x-heroicon-o-lock-closed class="w-5 h-5 text-white" />
                            </div>
                            <span>{{ __('filament.course_registration.unavailable_courses') }}</span>
                        </div>
                    </x-slot>
                    <x-slot name="description">
                        {{ __('filament.course_registration.unavailable_courses_description') }}
                    </x-slot>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($this->ineligibleCourses as $item)
                            @php
                                $course = $item['course'];
                                $reasons = $item['blocking_reasons'];
                            @endphp

                            <div class="border-2 border-rose-200 dark:border-rose-800 rounded-xl p-4 bg-gradient-to-br from-rose-50 to-red-50 dark:from-rose-900/10 dark:to-red-900/10 opacity-90">
                                {{-- Header --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <div class="w-6 h-6 rounded-md bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center flex-shrink-0">
                                                <x-heroicon-o-book-open class="w-3.5 h-3.5 text-white" />
                                            </div>
                                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $course->course_code }}
                                            </h3>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                                            {{ $course->course_name }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 ml-2">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-rose-400 to-red-600 flex items-center justify-center">
                                            <x-heroicon-o-lock-closed class="w-3.5 h-3.5 text-white" />
                                        </div>
                                    </div>
                                </div>

                                {{-- Course Details --}}
                                <div class="mb-3">
                                    <div class="flex items-center gap-1.5 px-2 py-1 bg-orange-100 dark:bg-orange-900/30 rounded-md w-fit">
                                        <x-heroicon-o-clock class="w-3 h-3 text-orange-600 dark:text-orange-400" />
                                        <span class="text-orange-700 dark:text-orange-300 font-semibold text-xs">
                                            {{ $course->credit_hours }} {{ __('filament.course_registration.credit_hours_short') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Blocking Reasons --}}
                                <div class="mt-3 pt-3 border-t border-rose-200 dark:border-rose-800">
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-rose-700 dark:text-rose-400 mb-2">
                                        <x-heroicon-o-exclamation-triangle class="w-3 h-3" />
                                        {{ __('filament.course_registration.blocking_reasons') }}:
                                    </div>
                                    <ul class="space-y-1.5">
                                        @foreach ($reasons as $reason)
                                            <li class="flex items-start gap-1.5 text-xs text-gray-700 dark:text-gray-300 bg-white/50 dark:bg-gray-800/50 rounded px-2 py-1">
                                                <x-heroicon-o-x-circle class="w-3 h-3 text-rose-500 dark:text-rose-400 flex-shrink-0 mt-0.5" />
                                                <span>{{ $reason }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            </div>
        @endif
    @endif

    {{-- My Registered Courses Section --}}
    @if ($this->currentEnrollments->isNotEmpty())
        <div class="mb-6">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-400 to-cyan-600 flex items-center justify-center">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-white" />
                        </div>
                        <span>{{ __('filament.course_registration.my_registered_courses') }}</span>
                    </div>
                </x-slot>
                <x-slot name="description">
                    {{ __('filament.course_registration.my_registered_courses_description') }}
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($this->currentEnrollments as $enrollment)
                        <div class="border-2 border-teal-200 dark:border-teal-800 rounded-xl p-4 bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/10 dark:to-cyan-900/10">
                            {{-- Header --}}
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-6 h-6 rounded-md bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center flex-shrink-0">
                                            <x-heroicon-o-book-open class="w-3.5 h-3.5 text-white" />
                                        </div>
                                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $enrollment->course->course_code }}
                                        </h3>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {{ $enrollment->course->course_name }}
                                    </p>
                                </div>
                                <div class="flex flex-col gap-1.5 items-end flex-shrink-0 ml-2">
                                    @if ($enrollment->is_retake)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-semibold rounded-md bg-gradient-to-r from-amber-400 to-orange-500 text-white shadow-sm">
                                            <x-heroicon-o-arrow-path class="w-2.5 h-2.5" />
                                            {{ __('filament.course_registration.retake') }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-semibold rounded-md shadow-sm
                                        {{ $enrollment->status === 'enrolled' ? 'bg-gradient-to-r from-emerald-400 to-teal-500 text-white' :
                                           ($enrollment->status === 'pending' ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white' :
                                           'bg-gradient-to-r from-gray-400 to-gray-500 text-white') }}">
                                        @if($enrollment->status === 'enrolled')
                                            <x-heroicon-o-check-badge class="w-2.5 h-2.5" />
                                        @elseif($enrollment->status === 'pending')
                                            <x-heroicon-o-clock class="w-2.5 h-2.5" />
                                        @endif
                                        {{ __('filament.course_registration.' . $enrollment->status) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Course Details --}}
                            <div class="space-y-1.5 mb-3">
                                <div class="flex items-center gap-1.5 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 rounded-md w-fit">
                                    <x-heroicon-o-clock class="w-3 h-3 text-blue-600 dark:text-blue-400" />
                                    <span class="text-blue-700 dark:text-blue-300 font-semibold text-xs">
                                        {{ $enrollment->course->credit_hours }} {{ __('filament.course_registration.credit_hours_short') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 px-2 py-1 bg-violet-100 dark:bg-violet-900/30 rounded-md w-fit">
                                    <x-heroicon-o-calendar class="w-3 h-3 text-violet-600 dark:text-violet-400" />
                                    <span class="text-violet-700 dark:text-violet-300 font-semibold text-xs">
                                        {{ $enrollment->enrollment_date?->format('M j, Y') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Drop Button --}}
                            @if ($this->isRegistrationOpen)
                                <div class="pt-3 border-t border-teal-200 dark:border-teal-800">
                                    <x-filament::button
                                        wire:click="dropCourse({{ $enrollment->enrollment_id }})"
                                        wire:confirm="{{ __('filament.course_registration.confirm_drop') }}"
                                        color="danger"
                                        size="xs"
                                        outlined
                                        icon="heroicon-o-trash"
                                        class="w-full"
                                    >
                                        {{ __('filament.course_registration.drop_course') }}
                                    </x-filament::button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        </div>
    @elseif($this->isRegistrationOpen)
        <div class="mb-6">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-400 to-cyan-600 flex items-center justify-center">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-white" />
                        </div>
                        <span>{{ __('filament.course_registration.my_registered_courses') }}</span>
                    </div>
                </x-slot>

                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 mb-4">
                        <x-heroicon-o-academic-cap class="w-7 h-7 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('filament.course_registration.no_registered_courses') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __('filament.course_registration.no_registered_courses_description') }}
                    </p>
                </div>
            </x-filament::section>
        </div>
    @endif
</x-filament-panels::page>
