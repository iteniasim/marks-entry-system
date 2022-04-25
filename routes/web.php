<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UpgradeController;
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
        ]);
    })->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::get('student/{student}/marksheets', [StudentController::class, 'studentMarksheets'])->name('students.studentMarksheets');

    Route::get('/grade/{grade}/students', [GradeController::class, 'gradeData'])
        ->name('gradeData');

    Route::resource('exams', ExamController::class);

    Route::resource('grades', GradeController::class);

    Route::resource('subjects', SubjectController::class);

    Route::resource('marks', MarkController::class)->except('show');
    Route::get('/marks/{student}/{grade}/{exam}', [MarkController::class, 'marksOfStudentGradeExam'])
        ->name('student.grade.exam.marks');

    Route::get('upgrade/grade', [UpgradeController::class, 'upgradeStudentsOfGradeForm'])->name('form.upgradeStudentsOfGrade');
    Route::post('upgrade/grade', [UpgradeController::class, 'upgradeStudentsOfGrade'])->name('upgradeStudentsOfGrade');
});

require __DIR__ . '/auth.php';
