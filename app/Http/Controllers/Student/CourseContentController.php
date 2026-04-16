<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseContent;
use App\Models\StudentContentProgress;

class CourseContentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $contents = CourseContent::where('class_name', $student->class?->name)
            ->orderBy('subject')
            ->orderBy('chapter_number')
            ->get();

        $progress = StudentContentProgress::where('student_id', $student->id)
            ->get()
            ->keyBy('content_id');

        $grouped = $contents->groupBy('subject');
        $subjectProgress = [];

        foreach ($grouped as $subject => $subjectContents) {
            $totalChapters = $subjectContents->count();
            $completedChapters = $subjectContents->filter(function ($content) use ($progress) {
                return isset($progress[$content->id]) && $progress[$content->id]->is_completed;
            })->count();

            $subjectProgress[$subject] = [
                'total' => $totalChapters,
                'completed' => $completedChapters,
                'percentage' => $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0,
            ];
        }

        return view('student.course-content', compact('contents', 'progress', 'grouped', 'subjectProgress'));
    }

    public function markComplete($contentId)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return response()->json(['error' => 'Student profile not found'], 404);
        }

        $progress = StudentContentProgress::firstOrCreate(
            ['student_id' => $student->id, 'content_id' => $contentId],
            ['is_completed' => false]
        );

        $progress->update([
            'is_completed' => ! $progress->is_completed,
            'completed_at' => $progress->is_completed ? null : now(),
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $progress->is_completed,
        ]);
    }
}
