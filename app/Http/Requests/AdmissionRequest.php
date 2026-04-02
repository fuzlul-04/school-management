<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdmissionRequest extends FormRequest
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
            'gender' => 'required|in:male,female,other',
            'religion' => 'nullable|string|max:50',
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+8801[0-9]{9}|01[0-9]{9})$/',
            ],
            'email' => 'nullable|email|max:191',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'father_name' => 'required|string|max:100',
            'father_phone' => 'nullable|string|max:20',
            'father_nid' => 'nullable|string|max:30',
            'mother_name' => 'required|string|max:100',
            'mother_phone' => 'nullable|string|max:20',
            'mother_nid' => 'nullable|string|max:30',
            'previous_school' => 'nullable|string|max:191',
            'ssc_roll' => 'nullable|string|max:30',
            'ssc_passing_year' => 'nullable|integer|min:2000|max:2030',
            'ssc_gpa' => 'nullable|numeric|min:0|max:5.00',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required for admission',
            'phone.regex' => 'Phone must be in Bangladesh format: +8801XXXXXXXXX or 01XXXXXXXXX',
            'father_name.required' => 'Father name is required',
            'mother_name.required' => 'Mother name is required',
        ];
    }

    public function attributes(): array
    {
        return [
            'academic_year_id' => 'academic year',
            'class_id' => 'class',
            'section_id' => 'section',
            'date_of_birth' => 'date of birth',
            'present_address' => 'present address',
            'permanent_address' => 'permanent address',
            'profile_image' => 'photo',
            'father_name' => 'father name',
            'father_phone' => 'father phone',
            'father_nid' => 'father NID',
            'mother_name' => 'mother name',
            'mother_phone' => 'mother phone',
            'mother_nid' => 'mother NID',
            'previous_school' => 'previous school',
            'ssc_passing_year' => 'SSC passing year',
            'ssc_gpa' => 'SSC GPA',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $phone = $this->normalizePhone($this->phone);
            $exists = \App\Models\Student::whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(phone, '+', ''), ' ', ''), '-', ''), '0', '0') = ?", [$phone])
                ->orWhereRaw("REPLACE(REPLACE(REPLACE(phone, '+', ''), ' ', ''), '-', '') = ?", [substr($phone, 1)])
                ->exists();

            if ($exists) {
                $validator->errors()->add('phone', 'A student with this phone number already exists');
            }
        });
    }

    public function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
