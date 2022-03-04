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
        return inertia('Marks/Index', [
            'marks' => Mark::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        $exams = Exam::all();
        $grades = Grade::all();
        return inertia('Marks/Create', compact('students', 'subjects', 'exams', 'grades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMarkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarkRequest $request)
    {
        Mark::create($request->validated());
        return to_route('marks.index');
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
        return inertia('Marks/Edit', [
            'mark' => $mark,
        ]);
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
        $mark->update($request->validated());
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
