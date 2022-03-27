<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $students = Student::when($request->has('search'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        })
            ->when($request->has('grade'), function ($query) use ($request) {
                return $query->where('grade_id', $request->grade);
            })
            ->with('grade')
            ->orderBy('grade_id')
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'students' => $students,
                'search' => $request->search,
                'grade' => $request->grade,
            ]);
        }

        return inertia('Students/Index', [
            'grades' => Grade::all(),
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
        return inertia('Students/Create', [
            'grades' => Grade::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        Student::create($request->validated());
        return to_route('students.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return inertia('Students/Edit', [
            'student' => $student,
            'grades' => Grade::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        return to_route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return to_route('students.index');
    }

    public function studentMarksheets(Student $student)
    {
        $student->load(['grade', 'marks', 'marks.grade', 'marks.subject', 'marks.exam']);
        return inertia('Students/StudentMarkSheets', [
            'student' => $student,
        ]);
    }
}
