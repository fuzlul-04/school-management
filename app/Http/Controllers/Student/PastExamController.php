<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;

class PastExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $results = ExamResult::where('student_id', $student->id)
            ->with('exam')
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('student.past-exams.index', compact('results'));
    }

    public function show($resultId)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $result = ExamResult::where('student_id', $student->id)
            ->with('exam.questions')
            ->findOrFail($resultId);

        return view('student.past-exams.show', compact('result'));
    }
}
