<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    public function upgradeStudentsOfGradeForm(Request $request)
    {
        $grades = Grade::all();
        return inertia('Students/UpgradeStudents', compact('grades'));
    }

    public function upgradeStudentsOfGrade(Request $request)
    {
        $request->validate([
            'fromGradeId' => 'required|exists:grades,id',
            'toGradeId' => 'required|exists:grades,id',
            'studentIdsToUpgrade' => 'array',
            'studentIdsToUpgrade.*' => 'exists:students,id',
        ]);
        Student::whereIn('id', $request->studentIdsToUpgrade)
            ->where('grade_id', $request->fromGradeId)
            ->update([
                'grade_id' => $request->toGradeId,
            ]);
        return redirect(route('students.index'))->with('success', 'Students Grade Changed');
    }
}
