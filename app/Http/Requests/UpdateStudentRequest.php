<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
            'name' => 'required|unique:students,name,' . $this->student->id,
            'roll_no' => [
                'required',
                Rule::unique('students', 'roll_no')->where(function ($query) {
                    return $query->where('grade_id', $this->grade_id);
                })->ignore($this->grade_id),
            ],
            'grade_id' => 'required|exists:grades,id',
        ];
    }
}
