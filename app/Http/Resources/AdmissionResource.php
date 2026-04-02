<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'bangla_name' => $this->bangla_name,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'gender' => $this->gender,
            'religion' => $this->religion,
            'phone' => $this->phone,
            'email' => $this->email,
            'present_address' => $this->present_address,
            'permanent_address' => $this->permanent_address,
            'profile_image' => $this->profile_image ? asset('storage/' . $this->profile_image) : null,
            'father_name' => $this->father_name,
            'father_phone' => $this->father_phone,
            'father_nid' => $this->father_nid,
            'mother_name' => $this->mother_name,
            'mother_phone' => $this->mother_phone,
            'mother_nid' => $this->mother_nid,
            'previous_school' => $this->previous_school,
            'ssc_roll' => $this->ssc_roll,
            'ssc_passing_year' => $this->ssc_passing_year,
            'ssc_gpa' => $this->ssc_gpa,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'interview_date' => $this->interview_date?->format('Y-m-d'),
            'merit_position' => $this->merit_position,
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
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
