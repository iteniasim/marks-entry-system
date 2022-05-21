<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $exams = Exam::when($request->has('search'),
            function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'exams' => $exams,
                'search' => $request->search,
            ]);
        }

        return inertia('Exams/Index', [
            'exams' => $exams,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Exams/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreExamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamRequest $request)
    {
        if ($request->is_final) {
            Exam::whereNotNull('id')->update([
                'is_final' => 0,
            ]);
        }

        Exam::create([
            'name' => $request->name,
            'final_evaluation_percentage' => $request->finalEvaluationPercentage,
            'is_final' => $request->has('isFinal') ? $request->isFinal : 0,
        ]);
        return to_route('exams.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        return inertia('Exams/Show', [
            'exam' => $exam,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        return inertia('Exams/Edit', [
            'exam' => $exam,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExamRequest  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamRequest $request, Exam $exam)
    {
        if ($request->is_final) {
            Exam::whereNot('id', $exam->id)->update([
                'is_final' => 0,
            ]);
        }

        $exam->update([
            'name' => $request->name,
            'final_evaluation_percentage' => $request->finalEvaluationPercentage,
            'is_final' => $request->has('isFinal') ? $request->isFinal : 0,
        ]);
        return to_route('exams.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return to_route('exams.index');
    }
}
