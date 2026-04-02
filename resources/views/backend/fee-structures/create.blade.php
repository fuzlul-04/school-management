@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Add Fee Structure</h1>
        <a href="{{ route('fee-structures.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('fee-structures.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fee Type *</label>
                    <select name="fee_type_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Fee Type</option>
                        @foreach($feeTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('fee_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Classes (Global)</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Leave empty to apply to all classes</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year *</label>
                    <select name="academic_year_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount *</label>
                    <input type="number" name="amount" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mandatory *</label>
                    <select name="is_mandatory" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="yes">Yes - Mandatory</option>
                        <option value="no">No - Optional</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create Fee Structure
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
