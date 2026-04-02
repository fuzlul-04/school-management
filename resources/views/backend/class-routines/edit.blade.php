@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Class Routine</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('class-routines.update', $classRoutine) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $classRoutine->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Section (Optional)</label>
                    <select name="section_id" id="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ $classRoutine->section_id == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $classRoutine->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Teacher</label>
                <select name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ $classRoutine->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }} ({{ $teacher->designation ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Day</label>
                    <select name="day" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @foreach($days as $day)
                        <option value="{{ $day }}" {{ $classRoutine->day == $day ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $classRoutine->academic_year_id == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="time" name="start_time" value="{{ $classRoutine->start_time }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="time" name="end_time" value="{{ $classRoutine->end_time }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" {{ $classRoutine->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $classRoutine->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('class-routines.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('class_id').addEventListener('change', function() {
    const classId = this.value;
    const sectionSelect = document.getElementById('section_id');
    sectionSelect.innerHTML = '<option value="">Select Section</option>';
    
    if (classId) {
        fetch(`/class-routines/sections/${classId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
            });
    }
});
</script>
@endsection