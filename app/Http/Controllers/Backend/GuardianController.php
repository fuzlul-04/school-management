<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuardianRequest;
use App\Http\Requests\UpdateGuardianRequest;
use App\Http\Resources\GuardianResource;
use App\Models\Guardian;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function __construct(
        private StudentService $studentService
    ) {}

    public function index(Request $request): View
    {
        $query = Guardian::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('nid_number', 'like', "%{$search}%");
            });
        }

        if ($request->has('relation') && $request->relation) {
            $query->where('relation', $request->relation);
        }

        $guardians = $query->latest()->paginate(10)->appends($request->query());

        return view('backend.guardians.index', compact('guardians'));
    }

    public function create(Request $request): View
    {
        $students = Student::active()->get();
        return view('backend.guardians.create', compact('students'));
    }

    public function store(StoreGuardianRequest $request): \Illuminate\Http\RedirectResponse
    {
        $guardian = Guardian::create($request->validated());

        if ($request->has('student_id')) {
            $student = Student::findOrFail($request->student_id);
            $this->studentService->assignGuardian(
                $student, 
                $guardian, 
                $request->boolean('is_primary', true)
            );
        }

        return redirect()->route('guardians.index')->with('success', 'Guardian created successfully.');
    }

    public function show(Guardian $guardian): View
    {
        $guardian->load('students');
        return view('backend.guardians.show', compact('guardian'));
    }

    public function edit(Guardian $guardian): View
    {
        $guardian->load('students');
        $students = Student::active()->get();
        return view('backend.guardians.edit', compact('guardian', 'students'));
    }

    public function update(UpdateGuardianRequest $request, Guardian $guardian): \Illuminate\Http\RedirectResponse
    {
        $guardian->update($request->validated());

        if ($request->has('student_id')) {
            $student = Student::findOrFail($request->student_id);
            $this->studentService->assignGuardian(
                $student,
                $guardian,
                $request->boolean('is_primary', true)
            );
        }

        return redirect()->route('guardians.index')->with('success', 'Guardian updated successfully.');
    }

    public function destroy(Guardian $guardian): \Illuminate\Http\RedirectResponse
    {
        $guardian->delete();
        return redirect()->route('guardians.index')->with('success', 'Guardian deleted successfully.');
    }

    public function apiIndex(Request $request): AnonymousResourceCollection
    {
        $guardians = Guardian::with('students')
            ->latest()
            ->paginate($request->per_page ?? 15);

        return GuardianResource::collection($guardians);
    }
}
