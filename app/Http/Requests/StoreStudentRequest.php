<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:191',
            'date_of_birth' => 'required|date',
            'birth_certificate_number' => 'nullable|string|max:30',
            'gender' => 'required|in:male,female,other',
            'religion' => 'nullable|string|max:50',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+8801[0-9]{9}|01[0-9]{9})$/',
                Rule::unique('students', 'phone'),
            ],
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'admission_date' => 'required|date',
            'previous_school' => 'nullable|string|max:191',
            'ssc_roll' => 'nullable|string|max:30',
            'ssc_registration' => 'nullable|string|max:30',
            'ssc_passing_year' => 'nullable|integer|min:2000|max:2030',
            'ssc_gpa' => 'nullable|numeric|min:0|max:5.00',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone must be in Bangladesh format: +8801XXXXXXXXX or 01XXXXXXXXX',
            'phone.unique' => 'This phone number is already registered',
        ];
    }

    public function attributes(): array
    {
        return [
            'academic_year_id' => 'academic year',
            'class_id' => 'class',
            'section_id' => 'section',
            'date_of_birth' => 'date of birth',
            'birth_certificate_number' => 'birth certificate number',
            'blood_group' => 'blood group',
            'present_address' => 'present address',
            'permanent_address' => 'permanent address',
            'profile_image' => 'photo',
            'admission_date' => 'admission date',
            'previous_school' => 'previous school',
            'ssc_passing_year' => 'SSC passing year',
            'ssc_gpa' => 'SSC GPA',
        ];
    }
}
