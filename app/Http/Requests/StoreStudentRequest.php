<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'first_name' => 'required|unique:students,first_name',
            'last_name' => 'required|unique:students,last_name',
            'roll_no' => 'required|unique:students,roll_no',
            'grade_id' => 'required|exists:grades,id',
        ];
    }
}
