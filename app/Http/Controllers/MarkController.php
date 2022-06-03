<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Models\AttendanceSummary;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\MarkGrading;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;

class MarkController extends Controller
{
    public function index()
    {
        $students = Student::whereHas('marks')
            ->with('grade')
            ->paginate(10);

        return inertia('Marks/Index', [
            'students' => $students,
        ]);
    }

    public function create()
    {
        $exams = Exam::all();
        $grades = Grade::all();
        return inertia('Marks/Create', compact('grades', 'exams'));
    }

    public function store(StoreMarkRequest $request)
    {
        foreach ($request->obtained_marks as $subjectId => $obtainedMark) {
            $subject = Subject::whereId($subjectId)->firstOrFail();
            Mark::updateOrCreate([
                'student_id' => $request->student_id,
                'subject_id' => $subject->id,
                'exam_id' => $request->exam_id,
                'grade_id' => $request->grade_id,
                'year' => Carbon::now()->format('Y'),
            ],
                [
                    'full_marks' => $subject->full_marks,
                    'pass_marks' => $subject->pass_marks,
                    'obtained_marks' => $obtainedMark,
                ]);
        }

        $this->saveAttendance($request);

        return back()->with('success', 'Marks Saved');
    }

    public function edit(Mark $mark)
    {
        $students = Student::all();
        $subjects = Subject::all();
        $exams = Exam::all();
        $grades = Grade::all();

        return inertia('Marks/Edit', compact('mark', 'students', 'subjects', 'exams', 'grades'));
    }

    public function update(UpdateMarkRequest $request, Mark $mark)
    {
        $subject = Subject::whereId($request->subject_id)->first();

        $mark->update([
            ...$request->validated(),
            'full_marks' => $subject->full_marks,
            'pass_marks' => $subject->pass_marks,
        ]);

        $this->saveAttendance($request);

        return to_route('marks.index');
    }

    public function destroy(Mark $mark)
    {
        $mark->delete();
        return to_route('marks.index');
    }

    public function marksOfStudentGradeExam(Student $student, Grade $grade, Exam $exam)
    {
        $exams = Exam::all();
        $marks = Mark::where('student_id', $student->id)
            ->where('grade_id', $grade->id)
            ->where('exam_id', $exam->id)
            ->with(['subject'])
            ->get();

        $otherExamMarks = $exam->is_final ? Mark::where('student_id', $student->id)
            ->where('grade_id', $grade->id)
            ->whereNot('exam_id', $exam->id)
            ->with(['subject'])
            ->get()
            ->groupBy('exam_id') : [];

        $gpaDetails = MarkGrading::all();

        return inertia('Marks/Show', compact('student', 'grade', 'exam', 'marks', 'exams', 'gpaDetails', 'otherExamMarks'));
    }

    public function studentMarksForYearExamGrade($year, Exam $exam, Grade $grade)
    {
        $marks = Mark::where('grade_id', $grade->id)
            ->where('exam_id', $exam->id)
            ->where('year', $year)
            ->with(['subject', 'student'])
            ->get();

        $otherExamMarks = $exam->is_final ? Mark::where('grade_id', $grade->id)
            ->whereNot('exam_id', $exam->id)
            ->where('year', $year)
            ->with(['subject'])
            ->get() : [];

        $gpaDetails = MarkGrading::all();

        return response()->json([
            'marks' => $marks,
            'otherExamMarks' => $otherExamMarks,
            'gpaDetails' => $gpaDetails,
        ]);
    }

    public function saveAttendance($request)
    {
        return AttendanceSummary::updateOrCreate([
            'student_id' => $request->student_id,
            'grade_id' => $request->grade_id,
            'exam_id' => $request->exam_id,
            'year' => Carbon::now()->format('Y'),
        ],
            [
                'absent_days' => $request->absent_days ?? 0,
                'present_days' => $request->present_days ?? 0,
            ]);
    }
}
