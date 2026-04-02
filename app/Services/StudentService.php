<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            $studentId = $this->generateStudentId();

            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $this->generateEmail($data['first_name'], $data['last_name']),
                'password' => bcrypt('password'),
                'role_id' => $this->getStudentRoleId(),
            ]);

            $studentData = [
                'user_id' => $user->id,
                'academic_year_id' => $data['academic_year_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'] ?? null,
                'student_id' => $studentId,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'bangla_name' => $data['bangla_name'] ?? null,
                'date_of_birth' => $data['date_of_birth'],
                'birth_certificate_number' => $data['birth_certificate_number'] ?? null,
                'gender' => $data['gender'],
                'religion' => $data['religion'] ?? null,
                'blood_group' => $data['blood_group'] ?? null,
                'phone' => $data['phone'] ?? null,
                'present_address' => $data['present_address'] ?? null,
                'permanent_address' => $data['permanent_address'] ?? null,
                'admission_date' => $data['admission_date'],
                'previous_school' => $data['previous_school'] ?? null,
                'ssc_roll' => $data['ssc_roll'] ?? null,
                'ssc_registration' => $data['ssc_registration'] ?? null,
                'ssc_passing_year' => $data['ssc_passing_year'] ?? null,
                'ssc_gpa' => $data['ssc_gpa'] ?? null,
                'status' => $data['status'] ?? 'active',
            ];

            if (isset($data['profile_image']) && $data['profile_image'] instanceof UploadedFile) {
                $studentData['profile_image'] = $this->uploadImage($data['profile_image']);
            }

            $student = Student::create($studentData);

            return $student;
        });
    }

    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            $studentData = [
                'academic_year_id' => $data['academic_year_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'] ?? null,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'bangla_name' => $data['bangla_name'] ?? null,
                'date_of_birth' => $data['date_of_birth'],
                'birth_certificate_number' => $data['birth_certificate_number'] ?? null,
                'gender' => $data['gender'],
                'religion' => $data['religion'] ?? null,
                'blood_group' => $data['blood_group'] ?? null,
                'phone' => $data['phone'] ?? null,
                'present_address' => $data['present_address'] ?? null,
                'permanent_address' => $data['permanent_address'] ?? null,
                'admission_date' => $data['admission_date'],
                'previous_school' => $data['previous_school'] ?? null,
                'ssc_roll' => $data['ssc_roll'] ?? null,
                'ssc_registration' => $data['ssc_registration'] ?? null,
                'ssc_passing_year' => $data['ssc_passing_year'] ?? null,
                'ssc_gpa' => $data['ssc_gpa'] ?? null,
                'status' => $data['status'] ?? 'active',
            ];

            if (isset($data['profile_image']) && $data['profile_image'] instanceof UploadedFile) {
                $this->deleteImage($student->profile_image);
                $studentData['profile_image'] = $this->uploadImage($data['profile_image']);
            }

            $student->update($studentData);

            if ($student->user) {
                $student->user->update([
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                ]);
            }

            return $student;
        });
    }

    public function delete(Student $student): void
    {
        DB::transaction(function () use ($student) {
            $this->deleteImage($student->profile_image);

            if ($student->user) {
                $student->user->delete();
            }

            $student->delete();
        });
    }

    public function uploadImage(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('students', $filename, 'public');
        return $path;
    }

    public function deleteImage(?string $path): void
    {
        if ($path && \Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }
    }

    public function assignGuardian(Student $student, Guardian $guardian, bool $isPrimary = false): void
    {
        $student->guardians()->syncWithoutDetaching([
            $guardian->id => ['is_primary' => $isPrimary]
        ]);
    }

    public function removeGuardian(Student $student, Guardian $guardian): void
    {
        $student->guardians()->detach($guardian->id);
    }

    public function changeClass(Student $student, int $classId, ?int $sectionId = null): Student
    {
        $student->update([
            'class_id' => $classId,
            'section_id' => $sectionId,
        ]);

        return $student->fresh();
    }

    public function changeAcademicYear(Student $student, int $academicYearId): Student
    {
        $student->update([
            'academic_year_id' => $academicYearId,
        ]);

        return $student->fresh();
    }

    private function generateStudentId(): string
    {
        $year = date('Y');
        $lastStudent = Student::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastStudent ? (int) substr($lastStudent->student_id, -4) + 1 : 1;
        return 'STU-' . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function generateEmail(string $firstName, string $lastName): string
    {
        $baseEmail = strtolower($firstName . '.' . $lastName . '@student.school');
        $email = $baseEmail;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = strtolower($firstName . '.' . $lastName . $counter . '@student.school');
            $counter++;
        }

        return $email;
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
