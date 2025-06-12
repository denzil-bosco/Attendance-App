<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Jobs\RecordDailyAttendanceJob;

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

Route::get('/test-job', function () {
    dispatch(new RecordDailyAttendanceJob());
    return 'Job dispatched!';
});

Route::apiResource('teacher', TeacherController::class);
Route::apiResource('classroom', ClassroomController::class);
Route::apiResource('student', StudentController::class);

Route::get('/attendance/today', [AttendanceController::class, 'get']);
