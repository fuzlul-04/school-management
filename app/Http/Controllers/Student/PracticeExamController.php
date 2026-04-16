<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PracticeQuestion;
use App\Models\PracticeResult;
use Illuminate\Http\Request;

class PracticeExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $subjects = PracticeQuestion::where('class_name', $student->class?->name)
            ->distinct()
            ->pluck('subject');

        $chapters = PracticeQuestion::where('class_name', $student->class?->name)
            ->whereNotNull('chapter')
            ->distinct()
            ->pluck('chapter');

        return view('student.practice-exam.index', compact('subjects', 'chapters'));
    }

    public function start(Request $request)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $request->validate([
            'subject' => 'required',
            'questions' => 'required|integer|in:10,20,30,50',
        ]);

        $query = PracticeQuestion::where('class_name', $student->class?->name)
            ->where('subject', $request->subject);

        if ($request->chapter) {
            $query->where('chapter', $request->chapter);
        }

        $questions = $query->inRandomOrder()
            ->limit($request->questions)
            ->get();

        if ($questions->count() < $request->questions) {
            return back()->with('error', 'Not enough questions available for the selected criteria');
        }

        $request->session()->put('practice_exam', [
            'subject' => $request->subject,
            'chapter' => $request->chapter,
            'question_ids' => $questions->pluck('id')->toArray(),
        ]);

        return view('student.practice-exam.take', compact('questions'));
    }

    public function submit(Request $request)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $examData = $request->session()->get('practice_exam');

        if (! $examData) {
            return redirect()->route('student.practice-exam.index')->with('error', 'No exam session found');
        }

        $questions = PracticeQuestion::whereIn('id', $examData['question_ids'])->get();
        $answers = $request->input('answers', []);

        $score = 0;
        $total = $questions->count();

        foreach ($questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_option) {
                $score++;
            }
        }

        $accuracy = $total > 0 ? round(($score / $total) * 100) : 0;

        $result = PracticeResult::create([
            'student_id' => $student->id,
            'subject' => $examData['subject'],
            'chapter' => $examData['chapter'] ?? null,
            'score' => $score,
            'total' => $total,
            'accuracy' => $accuracy,
            'taken_at' => now(),
        ]);

        $request->session()->forget('practice_exam');

        return view('student.practice-exam.result', compact('result', 'questions', 'answers'));
    }
}
