<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('guru.calendar');
    })->name('dashboard');

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('teachers.store');
        Route::delete('/teachers/{user}', [AdminController::class, 'deleteTeacher'])->name('teachers.destroy');
        Route::post('/schools', [AdminController::class, 'storeSchool'])->name('schools.store');
        Route::delete('/schools/{school}', [AdminController::class, 'deleteSchool'])->name('schools.destroy');
        Route::get('/recap', [AdminController::class, 'recap'])->name('recap');
        Route::get('/export-excel', [AdminController::class, 'exportExcel'])->name('export');
    });

    Route::get('/calendar', [AttendanceController::class, 'calendar'])->name('guru.calendar');
    Route::get('/attendance/{date}', [AttendanceController::class, 'showByDate'])->name('attendance.date');
    Route::post('/attendance/{date}', [AttendanceController::class, 'storeByDate'])->name('attendance.store');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
});
