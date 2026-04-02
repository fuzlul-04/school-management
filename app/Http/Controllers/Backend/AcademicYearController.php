<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::latest()->paginate(10);
        return view('backend.academic_years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('backend.academic_years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_year' => 'required|integer|min:2000|max:2100',
            'end_year' => 'required|integer|min:2000|max:2100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        AcademicYear::create($request->all());

        return redirect()->route('academic-years.index')->with('success', 'Academic Year created successfully.');
    }

    public function show(AcademicYear $academicYear)
    {
        return view('backend.academic_years.show', compact('academicYear'));
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('backend.academic_years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_year' => 'required|integer|min:2000|max:2100',
            'end_year' => 'required|integer|min:2000|max:2100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $academicYear->update($request->all());

        return redirect()->route('academic-years.index')->with('success', 'Academic Year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();
        return redirect()->route('academic-years.index')->with('success', 'Academic Year deleted successfully.');
    }

    public function setActive(AcademicYear $academicYear)
    {
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);
        return redirect()->route('academic-years.index')->with('success', 'Academic Year set as active.');
    }
}
