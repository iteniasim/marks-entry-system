<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UpgradeController;
use App\Models\Exam;
use App\Models\Grade;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return redirect(route('login'));
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'grades' => Grade::all(),
            'exams' => Exam::all(),
        ]);
    })->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::get('student/{student}/marksheets', [StudentController::class, 'studentMarksheets'])->name('students.studentMarksheets');

    Route::resource('exams', ExamController::class);

    Route::resource('grades', GradeController::class);
    Route::get('/grade/{grade}/students', [GradeController::class, 'gradeData'])
        ->name('gradeData');

    Route::resource('subjects', SubjectController::class);

    Route::resource('marks', MarkController::class)->except('show');
    Route::get('/marks/filter/{student}/{grade}/{exam}', [MarkController::class, 'marksOfStudentGradeExam'])
        ->name('student.grade.exam.marks');
    Route::get('/marks/all/{year}/{exam}/{grade}', [MarkController::class, 'studentMarksForYearExamGrade'])
        ->name('year.exam.grade.marks');

    Route::get('upgrade/grade', [UpgradeController::class, 'upgradeStudentsOfGradeForm'])->name('form.upgradeStudentsOfGrade');
    Route::post('upgrade/grade', [UpgradeController::class, 'upgradeStudentsOfGrade'])->name('upgradeStudentsOfGrade');

    Route::get('/attendance/summary', [AttendanceController::class, 'attendanceSummary'])->name('attendance.summary');

    Route::get('/backup', [BackupController::class, 'backupDatabase'])->name('database.backup');
});

require __DIR__ . '/auth.php';
