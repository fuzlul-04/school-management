<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function index()
    {
        $teacherSubjects = TeacherSubject::with(['teacher', 'subject', 'academicYear'])
            ->latest()
            ->paginate(15);
        return view('backend.teacher-subjects.index', compact('teacherSubjects'));
    }

    public function create()
    {
        $teachers = Teacher::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();
        return view('backend.teacher-subjects.create', compact('teachers', 'subjects', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $exists = TeacherSubject::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This teacher is already assigned to this subject for the selected academic year.');
        }

        TeacherSubject::create($request->all());

        return redirect()->route('teacher-subjects.index')->with('success', 'Subject assigned to teacher successfully.');
    }

    public function show(TeacherSubject $teacherSubject)
    {
        $teacherSubject->load(['teacher.user', 'subject', 'academicYear']);
        return view('backend.teacher-subjects.show', compact('teacherSubject'));
    }

    public function edit(TeacherSubject $teacherSubject)
    {
        $teachers = Teacher::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        $academicYears = AcademicYear::all();
        return view('backend.teacher-subjects.edit', compact('teacherSubject', 'teachers', 'subjects', 'academicYears'));
    }

    public function update(Request $request, TeacherSubject $teacherSubject)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $exists = TeacherSubject::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('id', '!=', $teacherSubject->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This teacher is already assigned to this subject for the selected academic year.');
        }

        $teacherSubject->update($request->all());

        return redirect()->route('teacher-subjects.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(TeacherSubject $teacherSubject)
    {
        $teacherSubject->delete();
        return redirect()->route('teacher-subjects.index')->with('success', 'Assignment removed successfully.');
    }
}