<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CAController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExaminerController;
use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\GPAController;
use App\Http\Controllers\HodController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\StudentCodeController;
use App\Http\Controllers\StudentCodeMarkController;
use App\Http\Controllers\SupportStaffController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//PUBLIC ROUTES
Route::post('/login', [AuthController::class, 'login']);

//PRIVATE ROUTES
Route::group(['middleware' => ['auth:sanctum']], function () {

    //AUTH ROUTES
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::get('/logout', 'logout')->name('logout');
        Route::post('/change-password', 'updatePassword')->name('updatePassword');
    });

    Route::controller(UserController::class, )->prefix('users')->group(function () {
        Route::get('/', 'index')->name('index');
    });
    Route::controller(AdminController::class, )->prefix('faculties')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(DepartmentController::class)->prefix('departments')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(CourseController::class)->prefix('courses')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(DeanController::class)->prefix('deans')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(HodController::class)->prefix('hods')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(CoordinatorController::class)->prefix('coordinators')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(LecturerController::class)->prefix('lecturers')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(ExaminerController::class)->prefix('examiners')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(SupportStaffController::class)->prefix('support-staffs')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(StudentController::class)->prefix('students')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(StudentCourseController::class)->prefix('student-courses')->group(function () {
        Route::get('/{id}', 'index')->name('index');
        Route::post('/{id}', 'store')->name('store');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(CAController::class)->prefix('ca-marks')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
        Route::post('/bulk-upload', 'bulkUpload')->name('bulkUpload');
    });

    Route::controller(StudentCodeController::class)->prefix('student-codes')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(StudentCodeMarkController::class)->prefix('student-code-marks')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
        Route::post('/bulk-upload', 'bulkUpload')->name('bulkUpload');
    });

    Route::controller(ResultController::class)->prefix('results')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::controller(GPAController::class)->prefix('gpa')->group(function () {
        Route::post('/', 'index')->name('index');
        Route::get('/cumulative', 'cumulativeGpa')->name('cumulative');
    });

    Route::controller(StatisticsController::class)->prefix('stats')->group(function () {
        Route::get('/', 'index')->name('index');
    });
    Route::controller(FrequencyController::class)->prefix('freq')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/course-summary', 'courseSummary')->name('courseSummary');
    });
});
