@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Create Exam</h1>
        <a href="{{ route('exams.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Exams
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('exams.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <select name="academic_year_id" class="w-full border rounded px-3 py-2 text-gray-900 bg-white" required>
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->start_year }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Exam Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" placeholder="e.g., First Terminal Exam" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Exam Type</label>
                    <select name="type" class="w-full border rounded px-3 py-2" required>
                        @foreach($examTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="3" class="w-full border rounded px-3 py-2" placeholder="Optional remarks..."></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Exam</button>
                <a href="{{ route('exams.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
