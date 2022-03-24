<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjects = Subject::when($request->has('search'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        })
            ->when($request->has('grade'), function ($query) use ($request) {
                return $query->where('grade_id', $request->grade);
            })
            ->with(['grade' => function ($query) {
                $query->orderBY('name');
            }])
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'subjects' => $subjects,
                'search' => $request->search,
                'grade' => $request->grade,
            ]);
        }

        return inertia('Subjects/Index', [
            'subjects' => $subjects,
            'grades' => Grade::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grades = Grade::all();
        return inertia('Subjects/Create', compact('grades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->validated());
        return to_route('subjects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        return inertia('Subjects/Show', [
            'subject' => $subject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $grades = Grade::all();
        return inertia('Subjects/Edit', [
            'subject' => $subject,
            'grades' => $grades,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubjectRequest  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        return to_route('subjects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return to_route('subjects.index');
    }
}
