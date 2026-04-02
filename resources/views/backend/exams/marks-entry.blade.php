@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <!-- DEBUG: Check what's in variables -->
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        <h3 class="font-bold">Debug Info</h3>
        <p>Classrooms count: {{ $classrooms->count() }}</p>
        <p>Subjects count: {{ $subjects->count() }}</p>
        <p>Students count: {{ $students->count() }}</p>
        <p>Selected Class ID: {{ $selectedClassId }}</p>
        <p>Selected Subject ID: {{ $selectedSubjectId }}</p>
        @if($selectedClassId && $students->isEmpty())
        <p class="text-red-600 font-bold">⚠️ STUDENTS EMPTY AFTER CLASS SELECTED!</p>
        @endif
    </div>

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Marks Entry - {{ $exam->name }}</h1>
        <a href="{{ route('exams.show', $exam) }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Exam
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('exams.marks-entry', $exam) }}" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                <select name="class_id" class="border rounded px-3 py-2 min-w-[200px] text-gray-900 bg-white" onchange="this.form.submit()">
                    <option value="">Select Class</option>
                    @forelse($classrooms as $class)
                    <option value="{{ $class->id }}" class="text-gray-900" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} (ID: {{ $class->id }})
                    </option>
                    @empty
                    <option value="">No classes available</option>
                    @endforelse
                </select>
            </div>

            @if($selectedClassId && $sections->count() > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                <select name="section_id" class="border rounded px-3 py-2 min-w-[150px] text-gray-900 bg-white" onchange="this.form.submit()">
                    <option value="">All Sections</option>
                    @forelse($sections as $section)
                    <option value="{{ $section->id }}" class="text-gray-900" {{ $selectedSectionId == $section->id ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>
                    @empty
                    <option value="">No sections</option>
                    @endforelse
                </select>
            </div>
            @endif

            @if($selectedClassId && $subjects->count() > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <select name="subject_id" class="border rounded px-3 py-2 min-w-[200px] text-gray-900 bg-white" onchange="this.form.submit()">
                    <option value="">Select Subject</option>
                    @forelse($subjects as $subject)
                    <option value="{{ $subject->id }}" class="text-gray-900" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }} ({{ $subject->bangla_name }})
                    </option>
                    @empty
                    <option value="">No subjects</option>
                    @endforelse
                </select>
            </div>
            @elseif($selectedClassId && $subjects->count() == 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <p class="text-red-500">No subjects assigned to this class!</p>
            </div>
            @endif
        </form>
    </div>

    @if($selectedClassId && $selectedSubjectId && $students->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form method="POST" action="{{ route('exams.save-marks', $exam) }}" id="marksForm">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h2 class="text-lg font-semibold">Enter Marks</h2>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Marks
                </button>
            </div>

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">Roll</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Written</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">MCQ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Practical</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">CA</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($students as $student)
                    @php
                    $existingMark = $existingMarks->get($student->id);
                    @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $student->student_id }}</td>
                        <td class="px-4 py-3">{{ $student->student_id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $student->first_name }} {{ $student->last_name }}</div>
                            @if($student->bangla_name)
                            <div class="text-gray-500 text-sm">{{ $student->bangla_name }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" step="0.01" min="0" name="marks[{{ $loop->index }}][written_mark]" 
                                value="{{ $existingMark?->written_mark }}" 
                                class="w-20 border rounded px-2 py-1 text-center mark-input" data-type="written">
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" step="0.01" min="0" name="marks[{{ $loop->index }}][mcq_mark]" 
                                value="{{ $existingMark?->mcq_mark }}" 
                                class="w-20 border rounded px-2 py-1 text-center mark-input" data-type="mcq">
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" step="0.01" min="0" name="marks[{{ $loop->index }}][practical_mark]" 
                                value="{{ $existingMark?->practical_mark }}" 
                                class="w-20 border rounded px-2 py-1 text-center mark-input" data-type="practical">
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" step="0.01" min="0" name="marks[{{ $loop->index }}][ca_mark]" 
                                value="{{ $existingMark?->ca_mark }}" 
                                class="w-20 border rounded px-2 py-1 text-center mark-input" data-type="ca">
                        </td>
                        <td class="px-4 py-2 font-medium total-cell">
                            {{ number_format(($existingMark?->total_mark ?? 0), 2) }}
                        </td>
                        <input type="hidden" name="marks[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                        <input type="hidden" name="marks[{{ $loop->index }}][subject_id]" value="{{ $selectedSubjectId }}">
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
    @elseif($selectedClassId && !$selectedSubjectId)
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        Please select a subject to enter marks.
    </div>
    @elseif($selectedClassId && $students->count() == 0)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        No students found in this class.
    </div>
    @else
    <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded">
        Please select a class and subject to enter marks.
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.querySelectorAll('.mark-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const written = parseFloat(row.querySelector('[data-type="written"]').value) || 0;
            const mcq = parseFloat(row.querySelector('[data-type="mcq"]').value) || 0;
            const practical = parseFloat(row.querySelector('[data-type="practical"]').value) || 0;
            const ca = parseFloat(row.querySelector('[data-type="ca"]').value) || 0;
            const total = written + mcq + practical + ca;
            row.querySelector('.total-cell').textContent = total.toFixed(2);
        });
    });
</script>
@endpush
@endsection
