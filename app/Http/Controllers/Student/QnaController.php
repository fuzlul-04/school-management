<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QnaQuestion;
use Illuminate\Http\Request;

class QnaController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $questions = QnaQuestion::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.qna.index', compact('questions'));
    }

    public function create()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $subjects = ['Bangla', 'English', 'Mathematics', 'Science', 'Social Science', 'Religion'];

        return view('student.qna.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $request->validate([
            'subject' => 'required',
            'question_text' => 'required|min:10',
        ]);

        QnaQuestion::create([
            'student_id' => $student->id,
            'subject' => $request->subject,
            'question_text' => $request->question_text,
            'status' => 'pending',
        ]);

        return redirect()->route('student.qna.index')->with('success', 'Question submitted successfully');
    }

    public function show($id)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $question = QnaQuestion::where('student_id', $student->id)
            ->findOrFail($id);

        return view('student.qna.show', compact('question'));
    }
}
