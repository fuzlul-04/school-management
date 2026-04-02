<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function index()
    {
        $feeTypes = FeeType::orderByDesc('id')->paginate(15);
        return view('backend.fee-types.index', compact('feeTypes'));
    }

    public function create()
    {
        return view('backend.fee-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:191',
            'type' => 'required|in:tuition,admission,exam,library,transport,food,uniform,other',
            'description' => 'nullable|string',
            'is_recurring' => 'required|in:yes,no',
            'status' => 'required|in:active,inactive',
        ]);

        FeeType::create($request->all());

        return redirect()->route('fee-types.index')->with('success', 'Fee type created successfully.');
    }

    public function show(FeeType $feeType)
    {
        return view('backend.fee-types.show', compact('feeType'));
    }

    public function edit(FeeType $feeType)
    {
        return view('backend.fee-types.edit', compact('feeType'));
    }

    public function update(Request $request, FeeType $feeType)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:191',
            'type' => 'required|in:tuition,admission,exam,library,transport,food,uniform,other',
            'description' => 'nullable|string',
            'is_recurring' => 'required|in:yes,no',
            'status' => 'required|in:active,inactive',
        ]);

        $feeType->update($request->all());

        return redirect()->route('fee-types.index')->with('success', 'Fee type updated successfully.');
    }

    public function destroy(FeeType $feeType)
    {
        $feeType->delete();
        return redirect()->route('fee-types.index')->with('success', 'Fee type deleted successfully.');
    }
}
