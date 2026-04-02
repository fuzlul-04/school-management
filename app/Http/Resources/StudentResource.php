<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'bangla_name' => $this->bangla_name,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'birth_certificate_number' => $this->birth_certificate_number,
            'gender' => $this->gender,
            'religion' => $this->religion,
            'blood_group' => $this->blood_group,
            'phone' => $this->phone,
            'email' => $this->user?->email,
            'present_address' => $this->present_address,
            'permanent_address' => $this->permanent_address,
            'profile_image' => $this->profile_image ? asset('storage/' . $this->profile_image) : null,
            'admission_date' => $this->admission_date?->format('Y-m-d'),
            'previous_school' => $this->previous_school,
            'ssc_roll' => $this->ssc_roll,
            'ssc_registration' => $this->ssc_registration,
            'ssc_passing_year' => $this->ssc_passing_year,
            'ssc_gpa' => $this->ssc_gpa,
            'status' => $this->status,
            'class' => [
                'id' => $this->class->id ?? null,
                'name' => $this->class->name ?? null,
            ],
            'section' => $this->section ? [
                'id' => $this->section->id,
                'name' => $this->section->name,
            ] : null,
            'academic_year' => $this->academicYear ? [
                'id' => $this->academicYear->id,
                'year' => $this->academicYear->year,
            ] : null,
            'guardians' => GuardianResource::collection($this->whenLoaded('guardians')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
