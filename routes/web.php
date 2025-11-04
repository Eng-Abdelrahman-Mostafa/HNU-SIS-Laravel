<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as StudentLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\CurriculumController; // <-- ADD THIS

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- STUDENT Authentication Routes ---
Route::middleware('guest:student')->group(function () {
    Route::get('/', [StudentLoginController::class, 'showLoginForm'])->name('login');
    Route::get('login', [StudentLoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [StudentLoginController::class, 'login'])->name('login.post');
});
Route::post('logout', [StudentLoginController::class, 'logout'])->name('logout');


// --- STUDENT Authenticated Routes ---
Route::middleware(['auth:student'])->group(function () {
    Route::get('/registration', [RegistrationController::class, 'index'])->name('registration.index');
    Route::post('/registration', [RegistrationController::class, 'store'])->name('registration.store');
});


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

    // Curriculum Management Routes
    Route::post('/curriculum/seed-static', [ImportController::class, 'seedStaticData'])->name('curriculum.seed-static');
    Route::post('/curriculum/department', [CurriculumController::class, 'storeDepartment'])->name('curriculum.store-department');
    Route::post('/curriculum/level', [CurriculumController::class, 'storeLevel'])->name('curriculum.store-level');
    Route::post('/curriculum/course', [CurriculumController::class, 'storeCourse'])->name('curriculum.store-course');
});
