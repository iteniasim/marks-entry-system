<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $grade = Grade::whereNotNull('id');
        if ($request->withoutPagination) {
            $grade = $grade->get();
        } else {
            $grade = $grade->paginate(10);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'grades' => $grade,
            ]);
        }

        return inertia('Grades/Index', [
            'grades' => $grade,
        ]);
    }

    public function gradeData(Grade $grade)
    {
        // user is expected to only send only one of onlySubjects or onlySubjects as query param, not both at the same time, no param is fine
        return response()->json([
            'students' => request()->onlySubjects ? null : Student::where('grade_id', $grade->id)->with('marks')->get(),
            'subjects' => request()->onlyStudents ? null : Subject::where('grade_id', $grade->id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Grades/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGradeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGradeRequest $request)
    {
        Grade::create($request->validated());
        return to_route('grades.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        return inertia('Grades/Show', [
            'grade' => $grade,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function edit(Grade $grade)
    {
        return inertia('Grades/Edit', [
            'grade' => $grade,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGradeRequest  $request
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        $grade->update($request->validated());
        return to_route('grades.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return to_route('grades.index');
    }
}
