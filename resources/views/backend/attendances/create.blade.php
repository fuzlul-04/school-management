@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Mark Attendance</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('attendances.bulk') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="classSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Class</option>
                        @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <select name="subject_id" id="subjectSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" data-class="{{ $subject->class_id }}">{{ $subject->name }} ({{ $subject->classroom->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div id="studentsSection" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Students</label>
                <div class="space-y-2 max-h-96 overflow-y-auto border rounded p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-gray-500">
                                <th class="pb-2">Student</th>
                                <th class="pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody id="studentsList">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Save Attendance</button>
                <a href="{{ route('attendances.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('classSelect').addEventListener('change', function() {
    const classId = this.value;
    const subjectSelect = document.getElementById('subjectSelect');
    
    Array.from(subjectSelect.options).forEach(option => {
        if (option.value === '') return;
        const subjectClass = option.getAttribute('data-class');
        option.style.display = subjectClass == classId ? '' : 'none';
    });
    subjectSelect.value = '';
});

document.getElementById('subjectSelect').addEventListener('change', function() {
    const classId = document.getElementById('classSelect').value;
    const subjectId = this.value;
    
    if (classId && subjectId) {
        fetch(`/attendances/get-students?class_id=${classId}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('studentsList');
                tbody.innerHTML = data.map(student => `
                    <tr class="border-t">
                        <td class="py-2">${student.first_name} ${student.last_name}</td>
                        <td class="py-2">
                            <input type="hidden" name="attendances[${student.id}][student_id]" value="${student.id}">
                            <select name="attendances[${student.id}][status]" class="rounded-md border-gray-300 text-sm">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                    </tr>
                `).join('');
                document.getElementById('studentsSection').classList.remove('hidden');
            });
    } else {
        document.getElementById('studentsSection').classList.add('hidden');
    }
});
</script>
@endsection
