<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;

class StoreMarkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $subjects = Subject::whereIn('id', $this->request->all()['subject_ids'])->get();
        $obtainedMarks = $this->request->all()['obtained_marks'];

        $obtainedMarksRules = [];
        foreach ($obtainedMarks as $subjectId => $obtainedMark) {
            $obtainedMarksRules['obtained_marks.' . $subjectId] = 'required|integer|min:0|max:' . $subjects->where('id', $subjectId)->first()->full_marks;
        }

        return [
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'grade_id' => 'required|exists:grades,id',
            'obtained_marks' => 'array',
            ...$obtainedMarksRules,
            'absent_days',
            'present_days',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $obtainedMarks = $this->request->all()['obtained_marks'];

        $obtainedMarksAttributes = [];
        foreach ($obtainedMarks as $subjectId => $obtainedMark) {
            $obtainedMarksAttributes['obtained_marks.' . $subjectId] = 'obtained marks';
        }

        return [
            ...$obtainedMarksAttributes,
        ];
    }
}
