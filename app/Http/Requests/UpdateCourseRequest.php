<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'facultyId' => ['required', 'integer'],
            'userId' => ['required', 'string'],      //userId here is for the lecturer
            'semesterId' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'courseCode' => ['required', 'string'],
            'level' => ['required', 'integer'],
            'status' => ['required', 'string'],
            'creditValue' => ['required', 'integer'],
        ];
    }
}
