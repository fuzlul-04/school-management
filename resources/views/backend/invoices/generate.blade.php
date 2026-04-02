@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Generate Invoices</h1>
        <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('invoices.generate-preview') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class *</label>
                    <select name="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year *</label>
                    <select name="academic_year_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(30)->toDateString()) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <p class="text-sm text-gray-500">
                This will generate invoices for all active students in the selected class based on the fee structure.
                Students with existing invoices will be skipped.
            </p>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Preview Invoices
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
