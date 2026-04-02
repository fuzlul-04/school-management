<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\FeeStructure;
use App\Models\FeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeStructureController extends Controller
{
    public function index(Request $request)
    {
        $query = FeeStructure::with(['feeType', 'class', 'academicYear']);

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->fee_type_id) {
            $query->where('fee_type_id', $request->fee_type_id);
        }

        $feeStructures = $query->orderByDesc('id')->paginate(15);
        $academicYears = AcademicYear::all();
        $classes = Classe::where('status', 'active')->get();
        $feeTypes = FeeType::all();

        return view('backend.fee-structures.index', compact('feeStructures', 'academicYears', 'classes', 'feeTypes'));
    }

    public function create()
    {
        $academicYears = AcademicYear::where('status', 'active')->get();
        $classes = Classe::where('status', 'active')->get();
        $feeTypes = FeeType::all();
        return view('backend.fee-structures.create', compact('academicYears', 'classes', 'feeTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fee_type_id' => 'required|exists:fee_types,id',
            'class_id' => 'nullable|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'amount' => 'required|numeric|min:0',
            'is_mandatory' => 'required|in:yes,no',
        ]);

        $exists = FeeStructure::where('fee_type_id', $request->fee_type_id)
            ->where('class_id', $request->class_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Fee structure already exists for this combination.')->withInput();
        }

        FeeStructure::create($request->all());

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure created successfully.');
    }

    public function show(FeeStructure $feeStructure)
    {
        $feeStructure->load(['feeType', 'class', 'academicYear']);
        return view('backend.fee-structures.show', compact('feeStructure'));
    }

    public function edit(FeeStructure $feeStructure)
    {
        $academicYears = AcademicYear::all();
        $classes = Classe::where('status', 'active')->get();
        $feeTypes = FeeType::all();
        return view('backend.fee-structures.edit', compact('feeStructure', 'academicYears', 'classes', 'feeTypes'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $request->validate([
            'fee_type_id' => 'required|exists:fee_types,id',
            'class_id' => 'nullable|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'amount' => 'required|numeric|min:0',
            'is_mandatory' => 'required|in:yes,no',
        ]);

        $exists = FeeStructure::where('fee_type_id', $request->fee_type_id)
            ->where('class_id', $request->class_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('id', '!=', $feeStructure->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Fee structure already exists for this combination.')->withInput();
        }

        $feeStructure->update($request->all());

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return redirect()->route('fee-structures.index')->with('success', 'Fee structure deleted successfully.');
    }

    public function getByClass(Request $request)
    {
        $feeStructures = FeeStructure::with('feeType')
            ->where('academic_year_id', $request->academic_year_id)
            ->where(function ($q) use ($request) {
                $q->where('class_id', $request->class_id)
                  ->orWhereNull('class_id');
            })
            ->where('is_mandatory', 'yes')
            ->get();

        return response()->json($feeStructures);
    }
}
