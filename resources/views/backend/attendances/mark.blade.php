@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Mark Attendance</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('attendances.store-mark') }}" method="POST" id="attendanceForm" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700">Section</label>
                    <select name="section_id" id="sectionSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="dateSelect" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="flex items-end">
                    <button type="button" id="loadStudents" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Load Students
                    </button>
                </div>
            </div>

            <div id="studentsSection" class="hidden">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-gray-700">Students</label>
                    <button type="button" id="markAllPresent" class="text-sm text-blue-600 hover:text-blue-800">Mark All Present</button>
                </div>
                <div class="space-y-2 max-h-96 overflow-y-auto border rounded p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-gray-500">
                                <th class="pb-2 w-12">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                                </th>
                                <th class="pb-2">Student</th>
                                <th class="pb-2">Section</th>
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
document.getElementById('loadStudents').addEventListener('click', function() {
    const classId = document.getElementById('classSelect').value;
    const sectionId = document.getElementById('sectionSelect').value;
    const date = document.getElementById('dateSelect').value;
    
    if (!classId) {
        alert('Please select a class');
        return;
    }

    const url = new URL('{{ route("attendances.students") }}');
    url.searchParams.append('class_id', classId);
    if (sectionId) url.searchParams.append('section_id', sectionId);
    if (date) url.searchParams.append('date', date);

    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('studentsList');
            tbody.innerHTML = data.map(student => `
                <tr class="border-t">
                    <td class="py-2">
                        <input type="checkbox" class="student-checkbox rounded border-gray-300" value="${student.id}">
                    </td>
                    <td class="py-2">
                        <input type="hidden" name="attendances[${student.id}][student_id]" value="${student.id}">
                        ${student.first_name} ${student.last_name}
                    </td>
                    <td class="py-2 text-sm text-gray-500">${student.section?.name || '-'}</td>
                    <td class="py-2">
                        <select name="attendances[${student.id}][status]" class="status-select rounded-md border-gray-300 text-sm" data-student-id="${student.id}">
                            <option value="present" ${student.attendance_status === 'present' ? 'selected' : ''}>Present</option>
                            <option value="absent" ${student.attendance_status === 'absent' ? 'selected' : ''}>Absent</option>
                            <option value="late" ${student.attendance_status === 'late' ? 'selected' : ''}>Late</option>
                            <option value="excused" ${student.attendance_status === 'excused' ? 'selected' : ''}>Excused</option>
                        </select>
                    </td>
                </tr>
            `).join('');
            document.getElementById('studentsSection').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load students. Please try again.');
        });
});

document.getElementById('markAllPresent').addEventListener('click', function() {
    document.querySelectorAll('.status-select').forEach(select => {
        select.value = 'present';
    });
});

document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

document.getElementById('classSelect').addEventListener('change', function() {
    const classId = this.value;
    const sectionSelect = document.getElementById('sectionSelect');
    
    if (!classId) {
        sectionSelect.innerHTML = '<option value="">All Sections</option>';
        return;
    }
    
    fetch('/classrooms/' + classId + '/sections-list', {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
        .then(res => res.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
            data.forEach(section => {
                sectionSelect.innerHTML += `<option value="${section.id}">${section.name}</option>`;
            });
        })
        .catch(() => {
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
        });
});
</script>
@endsection
