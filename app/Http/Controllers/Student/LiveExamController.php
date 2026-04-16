<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\LiveExam;
use Illuminate\Http\Request;

class LiveExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $exams = LiveExam::where('class_name', $student->class?->name)
            ->whereIn('status', ['upcoming', 'live', 'published'])
            ->orderBy('start_time', 'desc')
            ->get();

        return view('student.live-exam.index', compact('exams'));
    }

    public function show($examId)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $exam = LiveExam::with('questions')->findOrFail($examId);

        $existingResult = ExamResult::where('student_id', $student->id)
            ->where('exam_id', $examId)
            ->first();

        if ($existingResult) {
            return redirect()->route('student.live-exams.results')
                ->with('info', 'You have already taken this exam');
        }

        $now = now();
        $start = $exam->start_time;
        $end = $start->copy()->addMinutes($exam->duration_minutes);

        if ($now < $start || $now > $end) {
            return redirect()->route('student.live-exams.index')
                ->with('error', 'This exam is not available at this time');
        }

        $timeRemaining = $end->diffInSeconds($now);

        return view('student.live-exam.take', compact('exam', 'timeRemaining'));
    }

    public function submit(Request $request, $examId)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $exam = LiveExam::with('questions')->findOrFail($examId);
        $answers = $request->input('answers', []);

        $score = 0;
        $correctAnswers = 0;
        $totalQuestions = $exam->questions->count();

        foreach ($exam->questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_option) {
                $score += $exam->total_marks / $totalQuestions;
                $correctAnswers++;
            }
        }

        $score = round($score);

        $rank = ExamResult::where('exam_id', $examId)
            ->where('score', '>', $score)
            ->count() + 1;

        $result = ExamResult::create([
            'student_id' => $student->id,
            'exam_id' => $examId,
            'score' => $score,
            'rank' => $rank,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'submitted_at' => now(),
        ]);

        $exam->update(['status' => 'published']);

        return view('student.live-exam.result', compact('result', 'exam'));
    }

    public function results()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $results = ExamResult::where('student_id', $student->id)
            ->with('exam')
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('student.live-exam.results', compact('results'));
    }
}
