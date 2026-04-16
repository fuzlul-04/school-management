<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\PracticeResult;

class PerformanceController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $liveExamResults = ExamResult::where('student_id', $student->id)
            ->with('exam')
            ->orderBy('submitted_at', 'desc')
            ->get();

        $practiceResults = PracticeResult::where('student_id', $student->id)
            ->orderBy('taken_at', 'desc')
            ->get();

        $subjectMarks = [];
        foreach ($liveExamResults as $result) {
            if ($result->exam) {
                $subject = $result->exam->subject;
                if (! isset($subjectMarks[$subject])) {
                    $subjectMarks[$subject] = [];
                }
                $subjectMarks[$subject][] = [
                    'score' => $result->score,
                    'total' => $result->exam->total_marks,
                    'date' => $result->submitted_at,
                ];
            }
        }

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Subject Average',
                    'data' => [],
                    'backgroundColor' => '#3b82f6',
                ],
            ],
        ];

        foreach ($subjectMarks as $subject => $marks) {
            $chartData['labels'][] = $subject;
            $avg = array_sum(array_column($marks, 'score')) / count($marks);
            $chartData['datasets'][0]['data'][] = round($avg, 1);
        }

        $totalExams = $liveExamResults->count();
        $passedExams = $liveExamResults->filter(fn ($r) => $r->score >= ($r->exam->total_marks ?? 0) * 0.4)->count();

        $achievements = [];

        if ($totalExams > 0 && ($passedExams / $totalExams) >= 0.8) {
            $achievements[] = 'Consistent Performer';
        }

        $topRank = $liveExamResults->min('rank');
        if ($topRank && $topRank <= 5) {
            $achievements[] = 'Top 5 Achiever';
        }

        $totalPractice = $practiceResults->count();
        if ($totalPractice >= 10) {
            $achievements[] = 'Practice Champion';
        }

        return view('student.performance', compact(
            'liveExamResults',
            'practiceResults',
            'chartData',
            'achievements'
        ));
    }
}
