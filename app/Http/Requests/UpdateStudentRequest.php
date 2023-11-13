<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'gender' => ['required', 'string'],
            'status' => ['required', 'string'],
            'dob' => ['required', 'date'],
            'placeOfBirth' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'region' => ['nullable', 'string'],
            'nationalIdentification' => ['required', 'integer'],
            'country' => ['required', 'string'],
            'matriculationNumber' => ['required', 'string'],
            'level' => ['required', 'integer'],
            'year' => ['required', 'string'],
            'program' => ['required', 'string'],
            'certificateObtained' => ['required', 'string'],
            'yearObtained' => ['required', 'string'],
            'guardianFirstName' => ['required', 'string'],
            'guardianLastName' => ['required', 'string'],
            'guardianEmail' => ['required', 'string'],
            'guardianAddress' => ['required', 'string'],
            'guardianPhone' => ['required', 'string'],
        ];
    }
}
