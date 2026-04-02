<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\ClassSubject;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['teacherSubjects.teacher', 'classes'])->latest()->paginate(10);
        return view('backend.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $teachers = Teacher::where('status', 'active')->get();
        return view('backend.subjects.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bangla_name' => 'nullable|string|max:255',
            'code' => 'required|string|unique:subjects,code|max:20',
            'type' => 'required|in:theory,practical,both',
            'full_mark' => 'nullable|numeric|min:0',
            'pass_mark' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        Subject::create([
            'name' => $request->name,
            'bangla_name' => $request->bangla_name,
            'code' => $request->code,
            'type' => $request->type,
            'full_mark' => $request->full_mark ?? 100,
            'pass_mark' => $request->pass_mark ?? 33,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['classes.academicYear', 'teacherSubjects.teacher']);
        return view('backend.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $teachers = Teacher::where('status', 'active')->get();
        return view('backend.subjects.edit', compact('subject', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bangla_name' => 'nullable|string|max:255',
            'code' => 'required|string|unique:subjects,code,' . $subject->id . '|max:20',
            'type' => 'required|in:theory,practical,both',
            'full_mark' => 'nullable|numeric|min:0',
            'pass_mark' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }

    public function assignClass(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'credit_hour' => 'nullable|integer|min:1',
        ]);

        ClassSubject::updateOrCreate(
            [
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'academic_year_id' => $request->academic_year_id,
            ],
            ['credit_hour' => $request->credit_hour ?? 1]
        );

        return back()->with('success', 'Subject assigned to class successfully.');
    }

    public function removeClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        ClassSubject::where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->delete();

        return back()->with('success', 'Subject removed from class.');
    }
}
