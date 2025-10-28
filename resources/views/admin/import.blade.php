@php // resources/views/admin/import.blade.php @endphp
@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-900">Admin Data Management</h1>
        <p class="mt-1 text-sm text-gray-600">
            Export registration data, manage curriculum, or import student data.
        </p>
    </div>

    <!-- Export Card -->
    {{-- ... (Export card remains unchanged) ... --}}
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-gray-900">Export Registration Data</h2>
        <form action="{{ route('admin.import.export') }}" method="POST" class="mt-4">
            @csrf
            <div class="flex items-end space-x-4">
                <div class="flex-grow">
                    <label for="semester_id" class="block text-sm font-medium text-gray-700">Select Semester</label>
                    <select id="semester_id" name="semester_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @if($semesters->isEmpty())
                            <option disabled>No semesters found. Please import data first.</option>
                        @else
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->semester_id }}">{{ $semester->semester_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="submit"
                        class="px-6 py-2 h-10 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        @if($semesters->isEmpty()) disabled @endif>
                    Download CSV
                </button>
            </div>
            @error('semester_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <!-- Session Messages -->
    {{-- ... (All session messages remain unchanged) ... --}}
     @if (session('success-curriculum'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
            {{ session('success-curriculum') }}
        </div>
    @endif
    @if (session('error-curriculum'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-sm">
            {{ session('error-curriculum') }}
            @if(session('logs-curriculum'))
                <pre class="mt-2 text-xs text-red-800 bg-red-50 p-2 rounded overflow-auto max-h-48">{{ implode("\n", session('logs-curriculum')) }}</pre>
            @endif
        </div>
    @endif
    @if (session('success-students'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
            {{ session('success-students') }}
        </div>
    @endif
    @if (session('error-students'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-sm">
            {{ session('error-students') }}
            @if(session('logs-students'))
                <pre class="mt-2 text-xs text-red-800 bg-red-50 p-2 rounded overflow-auto max-h-48">{{ implode("\n", session('logs-students')) }}</pre>
            @endif
        </div>
    @endif
    @if ($errors->any() && !$errors->has('semester_id'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-sm">
            <strong>Please fix the following form errors:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    @if($error != $errors->first('semester_id'))
                        <li>{{ $error }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif


    <!-- Form 1: Curriculum Management -->
    {{-- ... (Curriculum Management card remains unchanged) ... --}}
     <div class="p-6 bg-white rounded-lg shadow-md space-y-6">
        <h2 class="text-xl font-bold text-gray-900">1. Curriculum Management (Manual Entry)</h2>
        <p class="text-sm text-gray-600">First time setup? Click here to seed the basic Departments and Levels:</p>
        <form action="{{ route('admin.curriculum.seed-static') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Seed Departments & Levels
            </button>
        </form>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-4 border-t">
            <!-- Add Department -->
            <form action="{{ route('admin.curriculum.store-department') }}" method="POST" class="space-y-3">
                @csrf
                <h3 class="text-lg font-medium text-gray-800">Add Department</h3>
                <div>
                    <label for="department_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                    <input type="text" name="department_name" id="department_name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                <div>
                    <label for="department_code" class="block text-sm font-medium text-gray-700">Code (e.g., DS)</label>
                    <input type="text" name="department_code" id="department_code" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                <div>
                    <label for="department_prefix" class="block text-sm font-medium text-gray-700">Prefix (e.g., 931)</label>
                    <input type="text" name="department_prefix" id="department_prefix" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700">Add Department</button>
            </form>

            <!-- Add Level -->
            <form action="{{ route('admin.curriculum.store-level') }}" method="POST" class="space-y-3">
                @csrf
                <h3 class="text-lg font-medium text-gray-800">Add Academic Level</h3>
                <div>
                    <label for="level_name" class="block text-sm font-medium text-gray-700">Level Name (e.g., Level 1)</label>
                    <input type="text" name="level_name" id="level_name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                <div>
                    <label for="level_number" class="block text-sm font-medium text-gray-700">Level Number (e.g., 1)</label>
                    <input type="number" name="level_number" id="level_number" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="min_credit_hours" class="block text-sm font-medium text-gray-700">Min Hours</label>
                        <input type="number" name="min_credit_hours" id="min_credit_hours" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="max_credit_hours" class="block text-sm font-medium text-gray-700">Max Hours</label>
                        <input type="number" name="max_credit_hours" id="max_credit_hours" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700">Add Level</button>
            </form>

            <!-- Add Course -->
            <form action="{{ route('admin.curriculum.store-course') }}" method="POST" class="space-y-3">
                @csrf
                <h3 class="text-lg font-medium text-gray-800">Add New Course</h3>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
                        <input type="text" name="course_code" id="course_code" placeholder="e.g., COM 101" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="credit_hours" class="block text-sm font-medium text-gray-700">Credit Hours</label>
                        <input type="number" name="credit_hours" id="credit_hours" value="3" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                    <input type="text" name="course_name" id="course_name" placeholder="e.g., Introduction to Computers" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Dept.</label>
                        <select name="department_id" id="department_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                            <option value="">Select...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="level_id" class="block text-sm font-medium text-gray-700">Level</label>
                        <select name="level_id" id="level_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                            <option value="">Select...</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->level_id }}">{{ $level->level_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="course_type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="course_type" id="course_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                            <option value="M">Mandatory (M)</option>
                            <option value="E">Elective (E)</option>
                            <option value="G">Graduation (G)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="prerequisites" class="block text-sm font-medium text-gray-700">Prerequisites (Hold Ctrl/Cmd to select multiple)</label>
                    <select name="prerequisites[]" id="prerequisites" multiple class="mt-1 block w-full h-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        @foreach($courses as $course)
                            <option value="{{ $course->course_id }}">{{ $course->course_code }} - {{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700">Add Course</button>
            </form>
        </div>
    </div>

    <!-- Log Output for Curriculum -->
    @if (session('logs-curriculum') && !session('error-curriculum'))
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-900">Curriculum Log</h3>
            <pre class="mt-2 text-xs text-gray-700 bg-gray-50 p-4 rounded-md overflow-auto max-h-96">
                @foreach(session('logs-curriculum') as $log)
                    @if(str_starts_with($log, 'SUCCESS:'))
                        <span class="text-green-600">{{ $log }}</span>
                    @elseif(str_starts_with($log, 'WARN:'))
                        <span class="text-yellow-600">{{ $log }}</span>
                    @elseif(str_starts_with($log, 'ERROR:'))
                        <span class="text-red-600">{{ $log }}</span>
                    @else
                        <span class="text-gray-600">{{ $log }}</span>
                    @endif
                    <br>
                @endforeach
            </pre>
        </div>
    @endif

    <!-- Form 2: Student & Grade Upload -->
    <form action="{{ route('admin.import.upload-students') }}" method="POST" enctype="multipart/form-data" class="p-6 bg-white rounded-lg shadow-md space-y-6">
        @csrf
        <h2 class="text-xl font-bold text-gray-900">2. Student & Grade Import (XLSX/XLS Upload)</h2>
        <p class="text-sm text-gray-600">Upload the 6 student and grade Excel files. Run this after setting up the curriculum, or whenever new student data is available.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Student CGPA -->
            <fieldset class="space-y-4 p-4 border rounded-md">
                <legend class="text-lg font-medium text-gray-800">Student CGPA (as .xlsx/.xls)</legend>
                {{-- MODIFIED: accept attribute --}}
                <div>
                    <label for="students_ds" class="block text-sm font-medium text-gray-700">Students (Data Science)</label>
                    <input type="file" name="students_ds" id="students_ds" accept=".xlsx,.xls" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                </div>
                <div>
                    <label for="students_mse" class="block text-sm font-medium text-gray-700">Students (MSE)</label>
                    <input type="file" name="students_mse" id="students_mse" accept=".xlsx,.xls" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                </div>
                <div>
                    <label for="students_rse" class="block text-sm font-medium text-gray-700">Students (Robotics)</label>
                    <input type="file" name="students_rse" id="students_rse" accept=".xlsx,.xls" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                </div>
            </fieldset>

            <!-- Subject Grades -->
            <fieldset class="space-y-4 p-4 border rounded-md">
                <legend class="text-lg font-medium text-gray-800">Subject Grades (as .xlsx/.xls)</legend>
                 {{-- MODIFIED: accept attribute --}}
                <div>
                    <label for="grades_ds" class="block text-sm font-medium text-gray-700">Grades (Data Science)</label>
                    <input type="file" name="grades_ds" id="grades_ds" accept=".xlsx,.xls" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                </div>
                <div>
                    <label for="grades_mse" class="block text-sm font-medium text-gray-700">Grades (MSE)</label>
                    <input type="file" name="grades_mse" id="grades_mse" accept=".xlsx,.xls" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                </div>
                <div>
                    <label for="grades_rse" class="block text-sm font-medium text-gray-700">Grades (Robotics)</label>
                    <input type="file" name="grades_rse" id="grades_rse" accept=".xlsx,.xls" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                </div>
            </fieldset>
        </div>

        <div class="border-t pt-6 flex justify-between items-center">
            <div class="flex items-center">
                <input id="fresh_students" name="fresh_students" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="fresh_students" class="ml-2 block text-sm font-medium text-red-600">
                    Clear all existing Students/Grades before importing
                </label>
            </div>
            <button type="submit"
                    class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Upload Student Data
            </button>
        </div>
    </form>

    <!-- Log Output for Students -->
    @if (session('logs-students') && !session('error-students'))
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-900">Student & Grade Import Log</h3>
            <pre class="mt-2 text-xs text-gray-700 bg-gray-50 p-4 rounded-md overflow-auto max-h-96">
                @foreach(session('logs-students') as $log)
                    @if(str_starts_with($log, 'SUCCESS:'))
                        <span class="text-green-600">{{ $log }}</span>
                    @elseif(str_starts_with($log, 'WARN:'))
                        <span class="text-yellow-600">{{ $log }}</span>
                    @elseif(str_starts_with($log, 'ERROR:'))
                        <span class="text-red-600">{{ $log }}</span>
                    @else
                        <span class="text-gray-600">{{ $log }}</span>
                    @endif
                    <br>
                @endforeach
            </pre>
        </div>
    @endif
</div>
@endsection
