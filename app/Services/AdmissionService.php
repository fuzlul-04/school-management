<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Admission;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdmissionService
{
    public function lookupByPhone(string $phone): ?Student
    {
        $normalizedPhone = $this->normalizePhone($phone);

        return Student::where(function ($query) use ($normalizedPhone) {
            $query->where('phone', $normalizedPhone)
                ->orWhere('phone', 'like', '%' . ltrim($normalizedPhone, '+') . '%');
        })->first();
    }

    public function searchByPhone(string $phone): array
    {
        $normalizedPhone = $this->normalizePhone($phone);
        
        $student = Student::where(function ($query) use ($normalizedPhone) {
            $query->where('phone', $normalizedPhone)
                ->orWhereRaw("REPLACE(REPLACE(phone, '+', ''), ' ', '') = ?", [ltrim($normalizedPhone, '+')]);
        })->with(['guardians', 'class', 'section', 'academicYear'])->first();

        return [
            'found' => $student !== null,
            'student' => $student,
            'phone' => $normalizedPhone,
        ];
    }

    public function register(array $data): Admission
    {
        return DB::transaction(function () use ($data) {
            $applicationId = $this->generateApplicationId();

            $admissionData = [
                'application_id' => $applicationId,
                'academic_year_id' => $data['academic_year_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'] ?? null,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'bangla_name' => $data['bangla_name'] ?? null,
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'religion' => $data['religion'] ?? null,
                'phone' => $this->normalizePhone($data['phone']),
                'email' => $data['email'] ?? null,
                'present_address' => $data['present_address'] ?? null,
                'permanent_address' => $data['permanent_address'] ?? null,
                'father_name' => $data['father_name'],
                'father_phone' => $data['father_phone'] ?? null,
                'father_nid' => $data['father_nid'] ?? null,
                'mother_name' => $data['mother_name'],
                'mother_phone' => $data['mother_phone'] ?? null,
                'mother_nid' => $data['mother_nid'] ?? null,
                'previous_school' => $data['previous_school'] ?? null,
                'ssc_roll' => $data['ssc_roll'] ?? null,
                'ssc_passing_year' => $data['ssc_passing_year'] ?? null,
                'ssc_gpa' => $data['ssc_gpa'] ?? null,
                'status' => 'pending',
            ];

            if (isset($data['profile_image']) && $data['profile_image'] instanceof UploadedFile) {
                $admissionData['profile_image'] = $this->uploadImage($data['profile_image']);
            }

            return Admission::create($admissionData);
        });
    }

    public function convertToStudent(Admission $admission, array $classData = []): Student
    {
        return DB::transaction(function () use ($admission, $classData) {
            $studentService = app(StudentService::class);
            $studentId = $studentService->generateStudentId();

            $user = User::create([
                'name' => $admission->first_name . ' ' . $admission->last_name,
                'email' => $studentService->generateEmail($admission->first_name, $admission->last_name),
                'password' => bcrypt('password'),
                'role_id' => $this->getStudentRoleId(),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'academic_year_id' => $classData['academic_year_id'] ?? $admission->academic_year_id,
                'class_id' => $classData['class_id'] ?? $admission->class_id,
                'section_id' => $classData['section_id'] ?? $admission->section_id,
                'student_id' => $studentId,
                'first_name' => $admission->first_name,
                'last_name' => $admission->last_name,
                'bangla_name' => $admission->bangla_name,
                'date_of_birth' => $admission->date_of_birth,
                'gender' => $admission->gender,
                'religion' => $admission->religion,
                'phone' => $admission->phone,
                'email' => $admission->email,
                'present_address' => $admission->present_address,
                'permanent_address' => $admission->permanent_address,
                'profile_image' => $admission->profile_image,
                'admission_date' => now()->toDateString(),
                'previous_school' => $admission->previous_school,
                'ssc_roll' => $admission->ssc_roll,
                'ssc_passing_year' => $admission->ssc_passing_year,
                'ssc_gpa' => $admission->ssc_gpa,
                'status' => 'active',
            ]);

            $this->createGuardiansFromAdmission($student, $admission);

            $admission->update([
                'status' => 'admitted',
            ]);

            return $student;
        });
    }

    public function createGuardiansFromAdmission(Student $student, Admission $admission): void
    {
        if ($admission->father_name) {
            $father = $this->createGuardian([
                'first_name' => $admission->father_name,
                'last_name' => '',
                'relation' => 'father',
                'phone' => $admission->father_phone,
                'nid_number' => $admission->father_nid,
                'is_primary' => true,
            ]);
            $student->guardians()->attach($father->id, ['is_primary' => true]);
        }

        if ($admission->mother_name) {
            $mother = $this->createGuardian([
                'first_name' => $admission->mother_name,
                'last_name' => '',
                'relation' => 'mother',
                'phone' => $admission->mother_phone,
                'nid_number' => $admission->mother_nid,
                'is_primary' => false,
            ]);
            $student->guardians()->attach($mother->id, ['is_primary' => false]);
        }
    }

    private function createGuardian(array $data): Guardian
    {
        return Guardian::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? '',
            'relation' => $data['relation'],
            'phone' => $data['phone'] ?? null,
            'nid_number' => $data['nid_number'] ?? null,
            'status' => 'active',
        ]);
    }

    public function enrollExistingStudent(Student $student, array $classData): Student
    {
        $student->update([
            'academic_year_id' => $classData['academic_year_id'],
            'class_id' => $classData['class_id'],
            'section_id' => $classData['section_id'] ?? null,
        ]);

        return $student->fresh();
    }

    public function uploadImage(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('admissions', $filename, 'public');
        return $path;
    }

    private function generateApplicationId(): string
    {
        $year = date('Y');
        $lastAdmission = Admission::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastAdmission ? (int) substr($lastAdmission->application_id, -4) + 1 : 1;
        return 'APP-' . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '880')) {
            return '+' . $phone;
        }

        if (str_starts_with($phone, '01')) {
            return '+' . $phone;
        }

        if (str_starts_with($phone, '1')) {
            return '+88' . $phone;
        }

        return $phone;
    }

    private function getStudentRoleId(): ?int
    {
        $role = \App\Models\Role::where('slug', 'student')->first();
        return $role?->id;
    }
}
