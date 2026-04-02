<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdmissionRequest;
use App\Http\Resources\AdmissionResource;
use App\Models\AcademicYear;
use App\Models\Admission;
use App\Models\Classe;
use App\Models\Section;
use App\Models\Student;
use App\Services\AdmissionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function __construct(
        private AdmissionService $admissionService
    ) {}

    public function index(Request $request): View
    {
        $query = Admission::with(['academicYear', 'class']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('application_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admissions = $query->latest()->paginate(10)->appends($request->query());
        $classrooms = Classe::all();
        $academicYears = AcademicYear::where('status', 'active')->get();

        return view('backend.admissions.index', compact('admissions', 'classrooms', 'academicYears'));
    }

    public function search(Request $request): View
    {
        $request->validate([
            'phone' => 'required|string|min:10',
        ]);

        $result = $this->admissionService->searchByPhone($request->phone);

        $academicYears = AcademicYear::where('status', 'active')->get();
        $classrooms = Classe::all();
        $sections = Section::all();

        if ($result['found']) {
            return view('backend.admissions.show-info', [
                'student' => $result['student'],
                'phone' => $result['phone'],
                'academicYears' => $academicYears,
                'classrooms' => $classrooms,
                'sections' => $sections,
            ]);
        }

        return view('backend.admissions.create', [
            'phone' => $result['phone'],
            'academicYears' => $academicYears,
            'classrooms' => $classrooms,
            'sections' => $sections,
        ]);
    }

    public function create(Request $request): View
    {
        $academicYears = AcademicYear::where('status', 'active')->get();
        $classrooms = Classe::all();
        $sections = Section::all();

        return view('backend.admissions.create', [
            'phone' => $request->phone ?? null,
            'academicYears' => $academicYears,
            'classrooms' => $classrooms,
            'sections' => $sections,
        ]);
    }

    public function store(AdmissionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $admission = $this->admissionService->register($request->validated());
        return redirect()->route('admissions.index')->with('success', 'Admission application submitted.');
    }

    public function show(Admission $admission): View
    {
        $admission->load(['academicYear', 'class', 'section']);
        return view('backend.admissions.show', compact('admission'));
    }

    public function edit(Admission $admission): View
    {
        $academicYears = AcademicYear::all();
        $classrooms = Classe::all();
        $sections = Section::all();
        return view('backend.admissions.edit', compact('admission', 'academicYears', 'classrooms', 'sections'));
    }

    public function update(Request $request, Admission $admission): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,waiting_list,admitted',
            'remarks' => 'nullable|string',
            'interview_date' => 'nullable|date',
            'merit_position' => 'nullable|integer',
        ]);

        $admission->update($request->only(['status', 'remarks', 'interview_date', 'merit_position']));

        return redirect()->route('admissions.index')->with('success', 'Admission updated.');
    }

    public function destroy(Admission $admission): \Illuminate\Http\RedirectResponse
    {
        $admission->delete();
        return redirect()->route('admissions.index')->with('success', 'Admission deleted.');
    }

    public function showInfo(Student $student): View
    {
        $student->load(['guardians', 'class', 'section', 'academicYear']);
        $academicYears = AcademicYear::where('status', 'active')->get();
        $classrooms = Classe::all();
        $sections = Section::all();

        return view('backend.admissions.show-info', [
            'student' => $student,
            'phone' => $student->phone,
            'academicYears' => $academicYears,
            'classrooms' => $classrooms,
            'sections' => $sections,
        ]);
    }

    public function enroll(Request $request, Student $student): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $this->admissionService->enrollExistingStudent($student, $request->only([
            'academic_year_id', 'class_id', 'section_id'
        ]));

        return redirect()->route('students.show', $student)->with('success', 'Student enrolled successfully.');
    }

    public function convertToStudent(Admission $admission): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $student = $this->admissionService->convertToStudent($admission, request()->only([
            'class_id', 'section_id', 'academic_year_id'
        ]));

        return redirect()->route('students.show', $student)->with('success', 'Student created from admission.');
    }

    public function apiIndex(Request $request): AnonymousResourceCollection
    {
        $admissions = Admission::with(['academicYear', 'class'])
            ->latest()
            ->paginate($request->per_page ?? 15);

        return AdmissionResource::collection($admissions);
    }

    public function apiSearch(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['phone' => 'required|string']);

        $result = $this->admissionService->searchByPhone($request->phone);

        return response()->json($result);
    }
}
