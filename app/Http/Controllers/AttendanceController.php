<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSummary;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendanceSummary(Request $request)
    {
        $attendanceSummary = AttendanceSummary::where('student_id', $request->studentId)
            ->where('grade_id', $request->gradeId)
            ->where('exam_id', $request->examId)
            ->when($request->has('year'), function ($query) use ($request) {
                return $query->where('year', $request->year);
            })
            ->get();

        return response()->json([
            'attendanceSummary' => $attendanceSummary,
        ]);
    }
}
