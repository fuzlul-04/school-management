<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\SolveSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolveSheetController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $query = SolveSheet::where('class_name', $student->class?->name);

        if ($request->subject) {
            $query->where('subject', $request->subject);
        }

        if ($request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $sheets = $query->orderBy('year', 'desc')->paginate(12);

        $subjects = SolveSheet::where('class_name', $student->class?->name)
            ->distinct()
            ->pluck('subject');

        $examTypes = SolveSheet::where('class_name', $student->class?->name)
            ->distinct()
            ->pluck('exam_type');

        $years = SolveSheet::where('class_name', $student->class?->name)
            ->distinct()
            ->pluck('year')
            ->sortDesc();

        return view('student.solve-sheet', compact('sheets', 'subjects', 'examTypes', 'years'));
    }

    public function download($id)
    {
        $sheet = SolveSheet::findOrFail($id);

        if (! Storage::exists($sheet->file_path)) {
            return back()->with('error', 'File not found');
        }

        return Storage::download($sheet->file_path, $sheet->title.'.pdf');
    }
}
