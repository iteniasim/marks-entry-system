<?php

namespace App\Http\Requests;

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
        return [
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'grade_id' => 'required|exists:grades,id',
            'obtained_marks' => 'array',
            'obtained_marks.*' => 'required|integer|min:0|max:100',
        ];
    }
}
