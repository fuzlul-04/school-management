@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Class Timetable</h1>
        <a href="{{ route('class-routines.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            Back to Routines
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Select Class</label>
                <select name="class_id" id="timetable_class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a Class</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Section (Optional)</label>
                <select name="section_id" id="timetable_section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Sections</option>
                    @if($classId && $sections = \App\Models\Section::where('class_id', $classId)->get())
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ $sectionId == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">View</button>
        </form>
    </div>

    @if($classId)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period 1</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period 2</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period 3</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period 4</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period 5</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period 6</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($days as $day)
                    <tr>
                        <td class="px-4 py-4 font-medium text-gray-900 bg-gray-50">
                            {{ ucfirst($day) }}
                        </td>
                        @php
                            $dayRoutines = $routines->get($day, collect([]))->sortBy('start_time');
                            $periods = [];
                            foreach($dayRoutines as $routine) {
                                $period = match(true) {
                                    $routine->start_time < '10:00:00' => 1,
                                    $routine->start_time < '12:00:00' => 2,
                                    $routine->start_time < '14:00:00' => 3,
                                    $routine->start_time < '15:30:00' => 4,
                                    $routine->start_time < '17:00:00' => 5,
                                    default => 6,
                                };
                                $periods[$period] = $routine;
                            }
                        @endphp
                        @for($i = 1; $i <= 6; $i++)
                        <td class="px-4 py-4">
                            @if(isset($periods[$i]))
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $periods[$i]->subject->name }}</div>
                                    <div class="text-gray-500">{{ $periods[$i]->teacher->full_name }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($periods[$i]->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($periods[$i]->end_time)->format('H:i') }}
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
        Please select a class to view the timetable.
    </div>
    @endif
</div>

<script>
document.getElementById('timetable_class_id').addEventListener('change', function() {
    const classId = this.value;
    const sectionSelect = document.getElementById('timetable_section_id');
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