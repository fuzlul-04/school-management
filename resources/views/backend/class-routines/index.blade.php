@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Class Routines</h1>
        <div class="flex gap-2">
            <a href="{{ route('class-routines.timetable') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                View Timetable
            </a>
            <a href="{{ route('class-routines.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Add Routine
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <select name="class_id" id="filter_class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Section</label>
                <select name="section_id" id="filter_section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Day</label>
                <select name="day" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Days</option>
                    <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}>Saturday</option>
                    <option value="sunday" {{ request('day') == 'sunday' ? 'selected' : '' }}>Sunday</option>
                    <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>Monday</option>
                    <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>Friday</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($routines as $routine)
                <tr>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($routine->day) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($routine->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($routine->end_time)->format('H:i') }}
                    </td>
                    <td class="px-6 py-4">{{ $routine->class->name }}</td>
                    <td class="px-6 py-4">{{ $routine->section->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $routine->subject->name }}</td>
                    <td class="px-6 py-4">{{ $routine->teacher->full_name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $routine->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($routine->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('class-routines.edit', $routine) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="{{ route('class-routines.destroy', $routine) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No routines found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $routines->links() }}
    </div>
</div>

<script>
document.getElementById('filter_class_id').addEventListener('change', function() {
    const classId = this.value;
    const sectionSelect = document.getElementById('filter_section_id');
    sectionSelect.innerHTML = '<option value="">All Sections</option>';
    
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