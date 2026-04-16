@extends('layouts.student')

@section('header-title', 'Past Exams')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Past Exams</h2>
        </div>
        <div class="p-6">
            @if($results->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Exam</th>
                        <th class="pb-3">Score</th>
                        <th class="pb-3">Rank</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($results as $result)
                    <tr>
                        <td class="py-3 font-medium">{{ $result->exam?->subject ?? 'N/A' }}</td>
                        <td class="py-3">{{ $result->score }}/{{ $result->exam?->total_marks }}</td>
                        <td class="py-3">#{{ $result->rank }}</td>
                        <td class="py-3">
                            @if($result->score >= ($result->exam->total_marks ?? 0) * 0.4)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Pass</span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Fail</span>
                            @endif
                        </td>
                        <td class="py-3">{{ $result->submitted_at->format('d M, Y') }}</td>
                        <td class="py-3">
                            <a href="{{ route('student.past-exams.show', $result->id) }}" class="text-indigo-600 hover:text-indigo-800">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500 text-center">No past exam results.</p>
            @endif
        </div>
    </div>
</div>
@endsection
