<?php

namespace App\Services;

use App\Models\Mark;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResultService
{
    private const GRADE_CONFIG = [
        ['grade' => 'A+', 'min_mark' => 80, 'max_mark' => 100, 'grade_point' => 5.00],
        ['grade' => 'A', 'min_mark' => 70, 'max_mark' => 79, 'grade_point' => 4.00],
        ['grade' => 'A-', 'min_mark' => 60, 'max_mark' => 69, 'grade_point' => 3.50],
        ['grade' => 'B', 'min_mark' => 50, 'max_mark' => 59, 'grade_point' => 3.00],
        ['grade' => 'C', 'min_mark' => 40, 'max_mark' => 49, 'grade_point' => 2.00],
        ['grade' => 'D', 'min_mark' => 33, 'max_mark' => 39, 'grade_point' => 1.00],
        ['grade' => 'F', 'min_mark' => 0, 'max_mark' => 32, 'grade_point' => 0.00],
    ];

    public function getGrade(float $totalMark): string
    {
        foreach (self::GRADE_CONFIG as $config) {
            if ($totalMark >= $config['min_mark'] && $totalMark <= $config['max_mark']) {
                return $config['grade'];
            }
        }
        return 'F';
    }

    public function getGradePoint(float $totalMark): float
    {
        foreach (self::GRADE_CONFIG as $config) {
            if ($totalMark >= $config['min_mark'] && $totalMark <= $config['max_mark']) {
                return (float) $config['grade_point'];
            }
        }
        return 0.00;
    }

    public function getGradeDetails(float $totalMark): array
    {
        foreach (self::GRADE_CONFIG as $config) {
            if ($totalMark >= $config['min_mark'] && $totalMark <= $config['max_mark']) {
                return [
                    'grade' => $config['grade'],
                    'grade_point' => $config['grade_point'],
                ];
            }
        }
        return ['grade' => 'F', 'grade_point' => 0.00];
    }

    public function calculateGPA(Collection $marks): float
    {
        if ($marks->isEmpty()) {
            return 0.00;
        }

        $totalGradePoint = 0;
        $subjectCount = 0;

        foreach ($marks as $mark) {
            if ($mark->total_mark !== null) {
                $gradePoint = $this->getGradePoint((float) $mark->total_mark);
                $totalGradePoint += $gradePoint;
                $subjectCount++;
            }
        }

        if ($subjectCount === 0) {
            return 0.00;
        }

        return round($totalGradePoint / $subjectCount, 2);
    }

    public function calculateTotalMarks(Collection $marks): float
    {
        return (float) $marks->sum('total_mark');
    }

    public function getSubjectGrade(Mark $mark): array
    {
        $totalMark = (float) $mark->total_mark;
        return $this->getGradeDetails($totalMark);
    }

    public function generateResults(int $examId): Collection
    {
        $exam = \App\Models\Exam::findOrFail($examId);
        $marks = Mark::where('exam_id', $examId)
            ->with(['student', 'subject'])
            ->get()
            ->groupBy('student_id');

        $results = collect();

        DB::beginTransaction();
        try {
            Result::where('exam_id', $examId)->delete();

            foreach ($marks as $studentId => $studentMarks) {
                $gpa = $this->calculateGPA($studentMarks);
                $totalMarks = $this->calculateTotalMarks($studentMarks);
                $grade = $this->getGradeFromGPA($gpa);

                $student = Student::find($studentId);
                $academicYear = $student->academic_year_id;

                $result = Result::create([
                    'student_id' => $studentId,
                    'exam_id' => $examId,
                    'academic_year_id' => $academicYear,
                    'total_mark' => $totalMarks,
                    'gpa' => $gpa,
                    'grade' => $grade,
                    'remarks' => $this->getRemarks($gpa),
                ]);

                $results->push($result);
            }

            $this->calculateRanks($examId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    public function calculateRanks(int $examId): void
    {
        $results = Result::where('exam_id', $examId)
            ->orderBy('total_mark', 'desc')
            ->get();

        $rank = 1;
        $previousMark = null;
        $sameRank = 0;

        foreach ($results as $result) {
            if ($previousMark !== null && $result->total_mark == $previousMark) {
                $sameRank++;
            } else {
                $rank = $rank + $sameRank;
                $sameRank = 0;
            }

            $result->update(['rank' => $rank]);
            $previousMark = $result->total_mark;
        }
    }

    public function getStudentResult(int $studentId, int $examId): ?Result
    {
        return Result::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->first();
    }

    public function getExamResults(int $examId): Collection
    {
        return Result::where('exam_id', $examId)
            ->with(['student', 'student.class', 'student.section'])
            ->orderBy('rank')
            ->get();
    }

    public function getStudentMarks(int $studentId, int $examId): Collection
    {
        return Mark::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->with(['subject', 'subject.classes'])
            ->get();
    }

    public function saveMarks(array $marksData, int $examId, int $classId): void
    {
        $academicYear = \App\Models\AcademicYear::where('is_current', 1)->first();

        foreach ($marksData as $data) {
            $totalMark = ($data['written_mark'] ?? 0) 
                + ($data['mcq_mark'] ?? 0) 
                + ($data['practical_mark'] ?? 0) 
                + ($data['ca_mark'] ?? 0);

            $gradeDetails = $this->getGradeDetails($totalMark);

            Mark::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'exam_id' => $examId,
                    'subject_id' => $data['subject_id'],
                ],
                [
                    'class_id' => $classId,
                    'academic_year_id' => $academicYear?->id,
                    'teacher_id' => auth()->user()->teacher?->id,
                    'written_mark' => $data['written_mark'] ?? null,
                    'mcq_mark' => $data['mcq_mark'] ?? null,
                    'practical_mark' => $data['practical_mark'] ?? null,
                    'ca_mark' => $data['ca_mark'] ?? null,
                    'total_mark' => $totalMark,
                    'grade' => $gradeDetails['grade'],
                    'remarks' => $data['remarks'] ?? null,
                ]
            );
        }
    }

    public function getClassSubjects(int $classId): Collection
    {
        return Subject::withoutGlobalScopes()->whereHas('classes', function ($query) use ($classId) {
                $query->where('classes.id', $classId);
            })
            ->get();
    }

    public function getClassStudents(int $classId, ?int $sectionId = null): Collection
    {
        // Use direct query to avoid global scopes
        $students = \DB::table('students')
            ->where('class_id', $classId)
            ->orderBy('student_id')
            ->get();
            
        return collect($students);
    }

    private function getGradeFromGPA(float $gpa): string
    {
        if ($gpa >= 5.00) return 'A+';
        if ($gpa >= 4.00) return 'A';
        if ($gpa >= 3.50) return 'A-';
        if ($gpa >= 3.00) return 'B';
        if ($gpa >= 2.00) return 'C';
        if ($gpa >= 1.00) return 'D';
        return 'F';
    }

    private function getRemarks(float $gpa): string
    {
        return match (true) {
            $gpa >= 4.5 => 'Excellent',
            $gpa >= 3.5 => 'Very Good',
            $gpa >= 2.5 => 'Good',
            $gpa >= 1.5 => 'Satisfactory',
            default => 'Needs Improvement',
        };
    }
}
