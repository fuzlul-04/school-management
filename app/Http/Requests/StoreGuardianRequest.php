<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuardianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:191',
            'relation' => 'nullable|string|max:50',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+8801[0-9]{9}|01[0-9]{9})$/',
            ],
            'alternative_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:191',
            'nid_number' => 'nullable|string|max:30',
            'profession' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone must be in Bangladesh format: +8801XXXXXXXXX or 01XXXXXXXXX',
        ];
    }
}
