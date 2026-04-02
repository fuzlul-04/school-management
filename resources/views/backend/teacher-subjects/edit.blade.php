@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Teacher Subject Assignment</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('teacher-subjects.update', $teacherSubject) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Teacher</label>
                <select name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ $teacherSubject->teacher_id == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->full_name }} ({{ $teacher->designation ?? 'No Designation' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $teacherSubject->subject_id == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }} ({{ $subject->code }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $teacherSubject->academic_year_id == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('teacher-subjects.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection