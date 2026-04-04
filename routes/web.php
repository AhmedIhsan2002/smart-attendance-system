<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FaceRecognitionController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect after login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role == 'student') {
            return redirect()->route('student.dashboard');
        } elseif ($user->role == 'instructor') {
            return redirect()->route('instructor.dashboard');
        } elseif ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    })->name('dashboard');
});

// Profile Routes
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance-history', [StudentController::class, 'attendanceHistory'])->name('attendance-history');
    Route::get('/course/{courseId}/attendance', [StudentController::class, 'courseAttendance'])->name('course-attendance');
});

// Instructor Routes
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [InstructorController::class, 'courses'])->name('courses');
    Route::get('/course/{id}', [InstructorController::class, 'showCourse'])->name('course.show');
    Route::get('/course/{courseId}/start-session', [InstructorController::class, 'startSession'])->name('start-session');
    Route::get('/course/{courseId}/sessions', [InstructorController::class, 'sessions'])->name('course.sessions');
    Route::get('/course/{courseId}/live-attendance', [InstructorController::class, 'liveAttendance'])->name('live-attendance');
    Route::get('/session/{sessionId}', [InstructorController::class, 'showSession'])->name('session.show');
    Route::post('/session/{sessionId}/end', [InstructorController::class, 'endSession'])->name('session.end');
    Route::post('/session/{sessionId}/manual-attendance', [InstructorController::class, 'manualAttendance'])->name('session.manual-attendance');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
     // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

     // Colleges Management
    Route::get('/colleges', [AdminController::class, 'colleges'])->name('colleges');
    Route::get('/colleges/create', [AdminController::class, 'createCollege'])->name('colleges.create');
    Route::post('/colleges', [AdminController::class, 'storeCollege'])->name('colleges.store');
    Route::get('/colleges/{id}/edit', [AdminController::class, 'editCollege'])->name('colleges.edit');
    Route::put('/colleges/{id}', [AdminController::class, 'updateCollege'])->name('colleges.update');
    Route::delete('/colleges/{id}', [AdminController::class, 'deleteCollege'])->name('colleges.delete');

    // Departments Management
    Route::get('/departments', [AdminController::class, 'departments'])->name('departments');
    Route::get('/departments/create', [AdminController::class, 'createDepartment'])->name('departments.create');
    Route::post('/departments', [AdminController::class, 'storeDepartment'])->name('departments.store');
    Route::get('/departments/{id}/edit', [AdminController::class, 'editDepartment'])->name('departments.edit');
    Route::put('/departments/{id}', [AdminController::class, 'updateDepartment'])->name('departments.update');
    Route::delete('/departments/{id}', [AdminController::class, 'deleteDepartment'])->name('departments.delete');
     // Courses Management
    Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{id}/edit', [AdminController::class, 'editCourse'])->name('courses.edit');
    Route::put('/courses/{id}', [AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminController::class, 'deleteCourse'])->name('courses.delete');
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

// Attendance Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/attendance/qr/{qrCode}', [AttendanceController::class, 'verifyQr'])->name('attendance.qr');
    Route::post('/attendance/qr/input', [AttendanceController::class, 'manualQrInput'])->name('attendance.qr.input');
    Route::post('/attendance/manual', [AttendanceController::class, 'manualAttendance'])->name('attendance.manual');
});

// Reports Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/reports/course/{courseId}/statistics', [ReportController::class, 'courseStatistics'])->name('reports.course-statistics');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/face/enroll', [FaceRecognitionController::class, 'enroll'])->name('face.enroll');
    Route::post('/face/save', [FaceRecognitionController::class, 'saveDescriptor'])->name('face.save');
    Route::delete('/face/delete', [FaceRecognitionController::class, 'delete'])->name('face.delete');
});

Route::post('/face/check', [FaceRecognitionController::class, 'checkFace'])->name('face.check');
Route::get('/face/verify', [FaceRecognitionController::class, 'verify'])->name('face.verify');

Route::post('/face/check', [FaceRecognitionController::class, 'checkFace'])->name('face.check');
Route::get('/face/verify', [FaceRecognitionController::class, 'verify'])->name('face.verify');
// Include Breeze routes
require __DIR__.'/auth.php';
