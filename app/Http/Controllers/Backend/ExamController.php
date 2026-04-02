<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Exam;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct(
        private ResultService $resultService
    ) {}

    public function index(Request $request): View
    {
        $query = Exam::with(['academicYear']);

        if ($request->has('academic_year_id') && $request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_published') && $request->is_published) {
            $query->where('is_published', $request->is_published);
        }

        $exams = $query->latest()->paginate(15)->appends($request->query());
        $academicYears = AcademicYear::all();

        return view('backend.exams.index', compact('exams', 'academicYears'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::all();
        $examTypes = ['monthly' => 'Monthly', 'ct' => 'Class Test', 'terminal' => 'Terminal', 'final' => 'Final', 'annual' => 'Annual'];

        return view('backend.exams.create', compact('academicYears', 'examTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'type' => 'required|in:monthly,ct,terminal,final,annual',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'remarks' => 'nullable|string',
        ]);

        Exam::create($validated);

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam): View
    {
        $exam->load(['academicYear', 'marks.subject', 'marks.student']);
        
        $totalStudents = \App\Models\Mark::where('exam_id', $exam->id)->distinct('student_id')->count('student_id');
        $totalSubjects = \App\Models\Mark::where('exam_id', $exam->id)->distinct('subject_id')->count('subject_id');

        return view('backend.exams.show', compact('exam', 'totalStudents', 'totalSubjects'));
    }

    public function edit(Exam $exam): View
    {
        $academicYears = AcademicYear::all();
        $examTypes = ['monthly' => 'Monthly', 'ct' => 'Class Test', 'terminal' => 'Terminal', 'final' => 'Final', 'annual' => 'Annual'];

        return view('backend.exams.edit', compact('exam', 'academicYears', 'examTypes'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'type' => 'required|in:monthly,ct,terminal,final,annual',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'remarks' => 'nullable|string',
        ]);

        $exam->update($validated);

        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
    }

    public function publish(Request $request, Exam $exam)
    {
        $request->validate([
            'is_published' => 'required|in:yes,no',
        ]);

        $exam->update(['is_published' => $request->is_published]);

        $message = $request->is_published === 'yes' ? 'Exam results published.' : 'Exam results unpublished.';
        return back()->with('success', $message);
    }

    public function marksEntry(Request $request, Exam $exam): View
    {
        $classrooms = Classe::with('academicYear')->get();
        
        $selectedClassId = $request->class_id;
        $selectedSectionId = $request->section_id;
        $selectedSubjectId = $request->subject_id;

        $students = collect();
        $subjects = collect();
        $existingMarks = collect();

        if ($selectedClassId) {
            $subjects = $this->resultService->getClassSubjects($selectedClassId);
            $students = $this->resultService->getClassStudents($selectedClassId, $selectedSectionId);
            
            // Debug: Log what's being queried
            \Log::info('MarksEntry Debug', [
                'selectedClassId' => $selectedClassId,
                'subjects_count' => $subjects->count(),
                'students_count' => $students->count(),
                'student_class_ids' => $students->pluck('class_id')->toArray(),
            ]);

            if ($selectedSubjectId && $selectedClassId) {
                $existingMarks = \App\Models\Mark::where('exam_id', $exam->id)
                    ->where('subject_id', $selectedSubjectId)
                    ->whereIn('student_id', $students->pluck('id'))
                    ->get()
                    ->keyBy('student_id');
            }
        }

        $sections = $selectedClassId 
            ? Section::where('class_id', $selectedClassId)->get()
            : collect();

        return view('backend.exams.marks-entry', compact(
            'exam', 'classrooms', 'students', 'subjects', 
            'existingMarks', 'selectedClassId', 'selectedSectionId', 'selectedSubjectId', 'sections'
        ));
    }

    public function saveMarks(Request $request, Exam $exam)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',
        ]);

        $this->resultService->saveMarks($request->marks, $exam->id, $request->class_id);

        return back()->with('success', 'Marks saved successfully.');
    }

    public function getSubjects(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $subjects = $this->resultService->getClassSubjects($request->class_id);
        
        return response()->json(['subjects' => $subjects]);
    }

    public function getStudents(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $students = $this->resultService->getClassStudents($request->class_id, $request->section_id);
        
        return response()->json(['students' => $students]);
    }
}
