<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\RecordedClass;
use App\Models\StudentBookmark;
use Illuminate\Http\Request;

class PastClassController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $query = RecordedClass::where('class_name', $student->class?->name);

        if ($request->subject) {
            $query->where('subject', $request->subject);
        }

        if ($request->chapter) {
            $query->where('chapter', $request->chapter);
        }

        if ($request->search) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        $videos = $query->orderBy('recorded_at', 'desc')->paginate(12);

        $bookmarks = StudentBookmark::where('student_id', $student->id)
            ->where('recordable_type', RecordedClass::class)
            ->pluck('recordable_id')
            ->toArray();

        $subjects = RecordedClass::where('class_name', $student->class?->name)
            ->distinct()
            ->pluck('subject');

        $chapters = RecordedClass::where('class_name', $student->class?->name)
            ->whereNotNull('chapter')
            ->distinct()
            ->pluck('chapter');

        return view('student.past-classes', compact('videos', 'bookmarks', 'subjects', 'chapters'));
    }

    public function toggleBookmark($id)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return response()->json(['error' => 'Student profile not found'], 404);
        }

        $bookmark = StudentBookmark::where('student_id', $student->id)
            ->where('recordable_type', RecordedClass::class)
            ->where('recordable_id', $id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
        } else {
            StudentBookmark::create([
                'student_id' => $student->id,
                'recordable_type' => RecordedClass::class,
                'recordable_id' => $id,
            ]);
            $bookmarked = true;
        }

        return response()->json(['success' => true, 'bookmarked' => $bookmarked]);
    }
}
