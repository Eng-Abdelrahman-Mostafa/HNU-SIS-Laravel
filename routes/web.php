<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as StudentLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\CurriculumController; // <-- ADD THIS

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- STUDENT Authentication Routes ---
Route::get('/', fn () => redirect()->route('filament.student.auth.login'))->name('login');
Route::get('login', fn () => redirect()->route('filament.student.auth.login'))->name('login.form');
Route::post('login', [StudentLoginController::class, 'login'])->name('login.post');
Route::post('logout', [StudentLoginController::class, 'logout'])->name('logout');


// --- STUDENT Authenticated Routes ---


// --- ADMIN Authentication Routes ---
// COMMENTED OUT: Filament handles its own authentication
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::middleware('guest:admin')->group(function () {
//         Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login.form');
//         Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
//     });
//     Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
// });


// --- ADMIN Authenticated Routes ---
// NOTE: These are custom admin routes separate from Filament
// They use 'web' guard (same as Filament) and will be accessible within Filament panel
Route::prefix('admin')->name('admin.')->middleware(['auth:web'])->group(function () {

    // Import/Export Page
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('/export-registrations', [ImportController::class, 'exportRegistrations'])->name('import.export');

    // Student CSV Upload
    Route::post('/import/students', [ImportController::class, 'uploadStudentData'])->name('import.upload-students');

    // Export Downloads
    Route::get('/exports/{filename}', function ($filename) {
        $filePath = storage_path('app/public/exports/' . $filename);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    })->name('export.download')->where('filename', '.*\.xlsx');

    // Curriculum Management Routes
    Route::post('/curriculum/seed-static', [ImportController::class, 'seedStaticData'])->name('curriculum.seed-static');
    Route::post('/curriculum/department', [CurriculumController::class, 'storeDepartment'])->name('curriculum.store-department');
    Route::post('/curriculum/level', [CurriculumController::class, 'storeLevel'])->name('curriculum.store-level');
    Route::post('/curriculum/course', [CurriculumController::class, 'storeCourse'])->name('curriculum.store-course');
});
