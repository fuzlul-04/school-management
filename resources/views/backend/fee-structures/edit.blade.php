@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Fee Structure</h1>
        <a href="{{ route('fee-structures.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('fee-structures.update', $feeStructure) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fee Type *</label>
                    <select name="fee_type_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach($feeTypes as $type)
                            <option value="{{ $type->id }}" {{ $feeStructure->fee_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Classes (Global)</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $feeStructure->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year *</label>
                    <select name="academic_year_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $feeStructure->academic_year_id == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount *</label>
                    <input type="number" name="amount" step="0.01" min="0" value="{{ $feeStructure->amount }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mandatory *</label>
                    <select name="is_mandatory" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="yes" {{ $feeStructure->is_mandatory === 'yes' ? 'selected' : '' }}>Yes - Mandatory</option>
                        <option value="no" {{ $feeStructure->is_mandatory === 'no' ? 'selected' : '' }}>No - Optional</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Fee Structure
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
