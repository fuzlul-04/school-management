<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Guardian;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudentController extends Controller
{
    public function __construct(
        private StudentService $studentService
    ) {}

    public function index(Request $request): \Illuminate\View\View
    {
        $query = Student::with(['academicYear', 'class', 'section']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(10)->appends($request->query());
        $classrooms = Classe::with('academicYear')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();

        return view('backend.students.index', compact('students', 'classrooms', 'academicYears'));
    }

    public function create(): \Illuminate\View\View
    {
        $academicYears = AcademicYear::where('status', 'active')->get();
        $classrooms = Classe::with('academicYear')->get();
        return view('backend.students.create', compact('academicYears', 'classrooms'));
    }

    public function store(StoreStudentRequest $request): \Illuminate\Http\RedirectResponse
    {
        $student = $this->studentService->create($request->validated());
        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(Student $student): \Illuminate\View\View
    {
        $student->load(['academicYear', 'class', 'section', 'guardians', 'user']);
        return view('backend.students.show', compact('student'));
    }

    public function edit(Student $student): \Illuminate\View\View
    {
        $academicYears = AcademicYear::all();
        $classrooms = Classe::with('academicYear')->get();
        $student->load('guardians');
        return view('backend.students.edit', compact('student', 'academicYears', 'classrooms'));
    }

    public function update(UpdateStudentRequest $request, Student $student): \Illuminate\Http\RedirectResponse
    {
        $this->studentService->update($student, $request->validated());
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): \Illuminate\Http\RedirectResponse
    {
        $this->studentService->delete($student);
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function uploadImage(Request $request, Student $student): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $this->studentService->uploadImage($request->file('profile_image'));
        $student->update(['profile_image' => $path]);

        return response()->json([
            'success' => true,
            'image_url' => asset('storage/' . $path),
        ]);
    }

    public function assignGuardian(Request $request, Student $student): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'guardian_id' => 'required|exists:guardians,id',
            'is_primary' => 'nullable|boolean',
        ]);

        $guardian = Guardian::findOrFail($request->guardian_id);
        $this->studentService->assignGuardian($student, $guardian, $request->boolean('is_primary'));

        return back()->with('success', 'Guardian assigned successfully.');
    }

    public function removeGuardian(Student $student, Guardian $guardian): \Illuminate\Http\RedirectResponse
    {
        $this->studentService->removeGuardian($student, $guardian);
        return back()->with('success', 'Guardian removed successfully.');
    }

    public function changeClass(Request $request, Student $student): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $this->studentService->changeClass(
            $student,
            $request->class_id,
            $request->section_id
        );

        return back()->with('success', 'Class changed successfully.');
    }

    public function apiIndex(Request $request): AnonymousResourceCollection
    {
        $students = Student::with(['academicYear', 'class', 'section', 'guardians'])
            ->latest()
            ->paginate($request->per_page ?? 15);

        return StudentResource::collection($students);
    }

    public function apiShow(Student $student): StudentResource
    {
        $student->load(['academicYear', 'class', 'section', 'guardians', 'user']);
        return new StudentResource($student);
    }
}
