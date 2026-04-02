<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherClass;
use Illuminate\Http\Request;

class TeacherClassController extends Controller
{
    public function index()
    {
        $teacherClasses = TeacherClass::with(['teacher', 'class', 'section', 'subject', 'academicYear'])
            ->latest()
            ->paginate(15);
        return view('backend.teacher-classes.index', compact('teacherClasses'));
    }

    public function create()
    {
        $teachers = Teacher::where('status', 'active')->get();
        $classes = Classe::with('academicYear')->where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();
        return view('backend.teacher-classes.create', compact('teachers', 'classes', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $exists = TeacherClass::where('teacher_id', $request->teacher_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This teacher is already assigned to this class/section/subject combination.');
        }

        TeacherClass::create($request->all());

        return redirect()->route('teacher-classes.index')->with('success', 'Teacher assigned to class successfully.');
    }

    public function show(TeacherClass $teacherClass)
    {
        $teacherClass->load(['teacher.user', 'class', 'section', 'subject', 'academicYear']);
        return view('backend.teacher-classes.show', compact('teacherClass'));
    }

    public function edit(TeacherClass $teacherClass)
    {
        $teachers = Teacher::where('status', 'active')->get();
        $classes = Classe::with('academicYear')->where('status', 'active')->get();
        $sections = Section::where('class_id', $teacherClass->class_id)->get();
        $subjects = Subject::where('status', 'active')->get();
        $academicYears = AcademicYear::all();
        return view('backend.teacher-classes.edit', compact('teacherClass', 'teachers', 'classes', 'sections', 'subjects', 'academicYears'));
    }

    public function update(Request $request, TeacherClass $teacherClass)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $exists = TeacherClass::where('teacher_id', $request->teacher_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('subject_id', $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('id', '!=', $teacherClass->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This teacher is already assigned to this class/section/subject combination.');
        }

        $teacherClass->update($request->all());

        return redirect()->route('teacher-classes.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(TeacherClass $teacherClass)
    {
        $teacherClass->delete();
        return redirect()->route('teacher-classes.index')->with('success', 'Assignment removed successfully.');
    }

    public function getSections($classId)
    {
        $sections = Section::where('class_id', $classId)->where('status', 'active')->get();
        return response()->json($sections);
    }

    public function getSubjects($classId)
    {
        $class = Classe::with('subjects.subject')->find($classId);
        $subjects = $class ? $class->subjects->pluck('subject') : collect([]);
        return response()->json($subjects);
    }
}