<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\ClassRoutine;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassRoutine::with(['class', 'section', 'subject', 'teacher', 'academicYear']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->day) {
            $query->byDay($request->day);
        }

        $routines = $query->latest()->paginate(20);
        $classes = Classe::where('status', 'active')->get();
        $sections = $request->class_id ? Section::where('class_id', $request->class_id)->get() : collect([]);

        return view('backend.class-routines.index', compact('routines', 'classes', 'sections'));
    }

    public function create()
    {
        $classes = Classe::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        $teachers = Teacher::where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();
        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        return view('backend.class-routines.create', compact('classes', 'subjects', 'teachers', 'academicYears', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status' => 'nullable|in:active,inactive',
        ]);

        $exists = ClassRoutine::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->exists();

        if ($exists) {
            return back()->with('error', 'A class is already scheduled at this time slot.');
        }

        ClassRoutine::create($request->all());

        return redirect()->route('class-routines.index')->with('success', 'Class routine created successfully.');
    }

    public function show(ClassRoutine $classRoutine)
    {
        $classRoutine->load(['class', 'section', 'subject', 'teacher', 'academicYear']);
        return view('backend.class-routines.show', compact('classRoutine'));
    }

    public function edit(ClassRoutine $classRoutine)
    {
        $classes = Classe::where('status', 'active')->get();
        $sections = Section::where('class_id', $classRoutine->class_id)->get();
        $subjects = Subject::where('status', 'active')->get();
        $teachers = Teacher::where('status', 'active')->get();
        $academicYears = AcademicYear::all();
        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        return view('backend.class-routines.edit', compact('classRoutine', 'classes', 'sections', 'subjects', 'teachers', 'academicYears', 'days'));
    }

    public function update(Request $request, ClassRoutine $classRoutine)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status' => 'nullable|in:active,inactive',
        ]);

        $exists = ClassRoutine::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->where('id', '!=', $classRoutine->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'A class is already scheduled at this time slot.');
        }

        $classRoutine->update($request->all());

        return redirect()->route('class-routines.index')->with('success', 'Class routine updated successfully.');
    }

    public function destroy(ClassRoutine $classRoutine)
    {
        $classRoutine->delete();
        return redirect()->route('class-routines.index')->with('success', 'Class routine deleted successfully.');
    }

    public function timetable(Request $request)
    {
        $classes = Classe::where('status', 'active')->get();
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $routines = collect([]);
        if ($classId) {
            $query = ClassRoutine::with(['subject', 'teacher', 'section'])
                ->where('class_id', $classId)
                ->where('status', 'active');

            if ($sectionId) {
                $query->where('section_id', $sectionId);
            }

            $routines = $query->get()->groupBy('day');
        }

        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        return view('backend.class-routines.timetable', compact('classes', 'routines', 'classId', 'sectionId', 'days'));
    }

    public function getSections($classId)
    {
        $sections = Section::where('class_id', $classId)->where('status', 'active')->get();
        return response()->json($sections);
    }
}