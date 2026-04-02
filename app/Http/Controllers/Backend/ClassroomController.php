<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Section;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classe::with(['academicYear', 'sections'])->latest()->paginate(10);
        return view('backend.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        $academicYears = AcademicYear::where('status', 'active')->get();
        return view('backend.classrooms.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'numeric_value' => 'nullable|integer',
            'section' => 'nullable|string|max:50',
            'room_number' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        $class = Classe::create($request->all());

        if ($request->has('sections') && is_array($request->sections)) {
            foreach ($request->sections as $sectionName) {
                if (!empty(trim($sectionName))) {
                    $class->sections()->create([
                        'name' => trim($sectionName),
                        'capacity' => $request->capacity ?? 40,
                        'room_number' => $request->room_number,
                        'status' => 'active'
                    ]);
                }
            }
        }

        return redirect()->route('classrooms.index')->with('success', 'Class created successfully.');
    }

    public function show(Classe $classroom)
    {
        $classroom->load(['students', 'subjects.subject', 'subjects.teacher', 'sections']);
        return view('backend.classrooms.show', compact('classroom'));
    }

    public function edit(Classe $classroom)
    {
        $academicYears = AcademicYear::all();
        return view('backend.classrooms.edit', compact('classroom', 'academicYears'));
    }

    public function update(Request $request, Classe $classroom)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'numeric_value' => 'nullable|integer',
            'section' => 'nullable|string|max:50',
            'room_number' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        $classroom->update($request->all());

        return redirect()->route('classrooms.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(Classe $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')->with('success', 'Class deleted successfully.');
    }

    public function createSection(Classe $classroom)
    {
        return view('backend.classrooms.sections.create', compact('classroom'));
    }

    public function storeSection(Request $request, Classe $classroom)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'room_number' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        $classroom->sections()->create($request->all());

        return redirect()->route('classrooms.show', $classroom)->with('success', 'Section created successfully.');
    }

    public function editSection(Classe $classroom, Section $section)
    {
        return view('backend.classrooms.sections.edit', compact('classroom', 'section'));
    }

    public function updateSection(Request $request, Classe $classroom, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'room_number' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        $section->update($request->all());

        return redirect()->route('classrooms.show', $classroom)->with('success', 'Section updated successfully.');
    }

    public function destroySection(Classe $classroom, Section $section)
    {
        $section->delete();
        return redirect()->route('classrooms.show', $classroom)->with('success', 'Section deleted successfully.');
    }

    public function getSections(Request $request)
    {
        $classroom = Classe::find($request->classroom);
        
        if (!$classroom) {
            return response()->json([]);
        }
        
        $sections = $classroom->sections()->where('status', 'active')->get(['id', 'name']);
        
        return response()->json($sections);
    }
}
