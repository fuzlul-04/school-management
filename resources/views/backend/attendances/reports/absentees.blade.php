@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Absentee List</h1>
        <div class="flex gap-2">
            <a href="{{ route('attendances.mark') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Mark Attendance
            </a>
            <a href="{{ route('attendances.reports.monthly') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Monthly Report
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <select name="class_id" class="mt-1 rounded-md border-gray-300">
                    <option value="">All Classes</option>
                    @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $classId == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Section</label>
                <select name="section_id" class="mt-1 rounded-md border-gray-300">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ $sectionId == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
                <a href="{{ route('attendances.reports.export', array_merge(request()->query(), ['type' => 'absentees'])) }}" target="_blank" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Export PDF</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Absent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Absent Dates</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($groupedAbsentees as $item)
                <tr>
                    <td class="px-6 py-4">{{ $item->student->first_name }} {{ $item->student->last_name }}</td>
                    <td class="px-6 py-4">{{ $item->student->class->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $item->student->section->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">{{ $item->total_absent }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ implode(', ', $item->absent_dates) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No absentees found for the selected date range</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
