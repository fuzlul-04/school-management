@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Result Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('results.export-pdf', $result) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Download PDF
            </a>
            <a href="{{ route('results.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Results
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Student ID</div>
            <div class="text-lg font-medium">{{ $result->student?->student_id }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Student Name</div>
            <div class="text-lg font-medium">{{ $result->student?->first_name }} {{ $result->student?->last_name }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Class</div>
            <div class="text-lg font-medium">{{ $result->student?->class?->name }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Section</div>
            <div class="text-lg font-medium">{{ $result->student?->section?->name ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Exam</div>
            <div class="text-lg font-medium">{{ $result->exam?->name }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Total Marks</div>
            <div class="text-lg font-medium">{{ number_format($result->total_mark, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">GPA</div>
            <div class="text-lg font-medium">{{ number_format($result->gpa, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Grade</div>
            <div class="text-lg font-medium">
                <span class="px-2 py-1 text-xs rounded-full 
                    @if($result->grade == 'A+') bg-green-100 text-green-800
                    @elseif(in_array($result->grade, ['A', 'A-'])) bg-blue-100 text-blue-800
                    @elseif($result->grade == 'B') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ $result->grade }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Rank</div>
        <div class="text-xl font-bold">
            @if($result->rank == 1)
            🥇 1st
            @elseif($result->rank == 2)
            🥈 2nd
            @elseif($result->rank == 3)
            🥉 3rd
            @else
            {{ $result->rank }}{{ match($result->rank % 10) {1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th'} }}
            @endif
            out of {{ \App\Models\Result::where('exam_id', $result->exam_id)->count() }} students
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Subject-wise Marks</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Written</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">MCQ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Practical</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($marks as $mark)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $mark->subject?->name }}</div>
                        @if($mark->subject?->bangla_name)
                        <div class="text-gray-500 text-sm">{{ $mark->subject?->bangla_name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $mark->written_mark ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $mark->mcq_mark ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $mark->practical_mark ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $mark->ca_mark ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium">{{ number_format($mark->total_mark, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($mark->grade == 'A+') bg-green-100 text-green-800
                            @elseif(in_array($mark->grade, ['A', 'A-'])) bg-blue-100 text-blue-800
                            @elseif($mark->grade == 'B') bg-yellow-100 text-yellow-800
                            @elseif($mark->grade == 'C') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $mark->grade }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No marks found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td class="px-6 py-3 font-medium">Total</td>
                    <td class="px-6 py-3">{{ $marks->sum('written_mark') }}</td>
                    <td class="px-6 py-3">{{ $marks->sum('mcq_mark') }}</td>
                    <td class="px-6 py-3">{{ $marks->sum('practical_mark') }}</td>
                    <td class="px-6 py-3">{{ $marks->sum('ca_mark') }}</td>
                    <td class="px-6 py-3 font-medium">{{ number_format($marks->sum('total_mark'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($result->remarks)
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Teacher's Remarks</div>
        <div class="text-lg">{{ $result->remarks }}</div>
    </div>
    @endif
</div>
@endsection
