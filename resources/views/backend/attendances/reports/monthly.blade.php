@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Monthly Attendance Report</h1>
        <div class="flex gap-2">
            <a href="{{ route('attendances.mark') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Mark Attendance
            </a>
            <a href="{{ route('attendances.reports.absentees') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                Absentee List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Year</label>
                <select name="year" class="mt-1 rounded-md border-gray-300">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Month</label>
                <select name="month" class="mt-1 rounded-md border-gray-300">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ Carbon\Carbon::createFromDate($year, $m, 1)->format('F') }}</option>
                    @endfor
                </select>
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
                <a href="{{ route('attendances.reports.export', ['type' => 'monthly', 'year' => $year, 'month' => $month, 'class_id' => $classId]) }}" target="_blank" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Export PDF</a>
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
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Present</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Absent</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Excused</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Days</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Attendance %</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($students as $student)
                <tr>
                    <td class="px-6 py-4">{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td class="px-6 py-4">{{ $student->class->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $student->section->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">{{ $student->present_days }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="{{ $student->absent_days > 0 ? 'text-red-600 font-medium' : '' }}">{{ $student->absent_days }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">{{ $student->excused_days }}</td>
                    <td class="px-6 py-4 text-center">{{ $student->total_days }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                        $percentage = $student->attendance_percentage;
                        $colorClass = $percentage >= 90 ? 'text-green-600' : ($percentage >= 75 ? 'text-yellow-600' : 'text-red-600');
                        @endphp
                        <span class="{{ $colorClass }} font-medium">{{ $percentage }}%</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No data available for the selected filters</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
