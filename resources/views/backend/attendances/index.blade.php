@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Attendance</h1>
        <div class="flex gap-2">
            <a href="{{ route('attendances.mark') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Mark Attendance
            </a>
            <a href="{{ route('attendances.reports.monthly') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Monthly Report
            </a>
            <a href="{{ route('attendances.reports.absentees') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                Absentee List
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="mt-1 rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <select name="class_id" class="mt-1 rounded-md border-gray-300">
                    <option value="">All Classes</option>
                    @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ request('class_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Section</label>
                <select name="section_id" class="mt-1 rounded-md border-gray-300">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                <tr>
                    <td class="px-6 py-4">{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}</td>
                    <td class="px-6 py-4">{{ $attendance->class->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $attendance->date->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @switch($attendance->status)
                        @case('present')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Present</span>
                        @break
                        @case('absent')
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Absent</span>
                        @break
                        @case('late')
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Late</span>
                        @break
                        @case('excused')
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Excused</span>
                        @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('attendances.edit', $attendance) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No attendance records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
</div>
@endsection
