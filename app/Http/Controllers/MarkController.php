<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::whereHas('marks')
            ->get();

        return inertia('Marks/Index', [
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exams = Exam::all();
        $grades = Grade::all();
        return inertia('Marks/Create', compact('grades', 'exams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMarkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarkRequest $request)
    {
        foreach ($request->obtained_marks as $subjectId => $obtainedMark) {
            $subject = Subject::whereId($subjectId)->firstOrFail();
            Mark::updateOrCreate([
                'student_id' => $request->student_id,
                'subject_id' => $subject->id,
                'exam_id' => $request->exam_id,
                'grade_id' => $request->grade_id,
            ],
                [
                    'full_marks' => $subject->full_marks,
                    'pass_marks' => $subject->pass_marks,
                    'obtained_marks' => $obtainedMark,
                ]);
        }
        return back()->with('success', 'Marks Saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function show(Mark $mark)
    {
        return inertia('Mark/Show', [
            'mark' => $mark,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function edit(Mark $mark)
    {
        $students = Student::all();
        $subjects = Subject::all();
        $exams = Exam::all();
        $grades = Grade::all();

        return inertia('Marks/Edit', compact('mark', 'students', 'subjects', 'exams', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMarkRequest  $request
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarkRequest $request, Mark $mark)
    {
        $subject = Subject::whereId($request->subject_id)->first();
        $mark->update([
            ...$request->validated(),
            'full_marks' => $subject->full_marks,
            'pass_marks' => $subject->pass_marks,
        ]);
        return to_route('marks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mark $mark)
    {
        $mark->delete();
        return to_route('marks.index');
    }
}
