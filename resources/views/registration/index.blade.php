@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Course Registration</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Welcome, <span class="font-medium">{{ $student->full_name_arabic }}</span> ({{ $student->student_id }})
                </p>
            </div>
            @if($currentSemester)
            <div class="mt-4 md:mt-0 text-left md:text-right">
                <p class="text-sm text-gray-600">
                    Registering for: <span class="font-medium text-indigo-600">{{ $currentSemester->semester_name }}</span>
                </p>
                <p class="text-sm text-gray-600">
                    CGPA: <span class="font-medium">{{ number_format($student->cgpa, 2) }}</span>
                </p>
                <p class="text-sm text-gray-600">
                    Earned Hours: <span class="font-medium">{{ $student->earned_credit_hours }}</span>
                </p>
            </div>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(!$currentSemester)
        <div class="p-6 bg-white rounded-lg shadow-md text-center">
            <h2 class="text-xl font-semibold text-gray-800">Registration Closed</h2>
            <p class="mt-4 text-gray-500">Course registration is not open at this time. Please check back later.</p>
        </div>
    @else
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Your Registered Courses ({{ $registeredCourses->count() }}) - Total Hours: {{ $totalRegisteredCredits }}</h2>
            @if($registeredCourses->isEmpty())
                <p class="mt-4 text-gray-500">You are not registered for any courses this semester.</p>
            @else
                <ul class="divide-y divide-gray-200 mt-4">
                    @foreach($registeredCourses as $enrollment)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <span class="text-sm font-medium text-indigo-600">{{ $enrollment->course->course_code }}</span>
                                <span class="ml-4 text-gray-800">{{ $enrollment->course->course_name }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $enrollment->course->credit_hours }} CH</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Available Courses for Registration</h2>

            @if($availableCourses->isEmpty())
                <p class="mt-4 text-gray-500">There are no new courses available for you to register at this time.</p>
            @else
                <form action="{{ route('registration.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="space-y-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Register</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($availableCourses as $course)
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="course_ids[]" value="{{ $course->course_id }}"
                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $course->course_code }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $course->course_name }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $course->credit_hours }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <p class="text-sm text-gray-600">
                            You have selected <strong id="selected_hours">0</strong> new credit hours.
                            You are already registered for <strong>{{ $totalRegisteredCredits }}</strong> hours.
                        </p>
                        <button type="submit"
                                class="mt-4 px-6 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Registration
                        </button>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const checkboxes = document.querySelectorAll('input[name="course_ids[]"]');
                        const selectedHoursEl = document.getElementById('selected_hours');

                        function updateSelectedHours() {
                            let selectedHours = 0;
                            checkboxes.forEach(cb => {
                                if (cb.checked) {
                                    const credits = parseInt(cb.closest('tr').cells[3].textContent);
                                    selectedHours += credits;
                                }
                            });
                            selectedHoursEl.textContent = selectedHours;
                        }

                        checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedHours));
                        updateSelectedHours();
                    });
                </script>
            @endif
        </div>
    @endif
</div>
@endsection
