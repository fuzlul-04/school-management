@extends('layouts.student')

@section('header-title', 'Live Exam Results')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Exam Results</h2>
        </div>
        <div class="p-6">
            @if($results->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Exam</th>
                        <th class="pb-3">Score</th>
                        <th class="pb-3">Correct</th>
                        <th class="pb-3">Rank</th>
                        <th class="pb-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($results as $result)
                    <tr>
                        <td class="py-3 font-medium">{{ $result->exam?->subject ?? 'N/A' }}</td>
                        <td class="py-3">{{ $result->score }}/{{ $result->exam?->total_marks }}</td>
                        <td class="py-3">{{ $result->correct_answers }}/{{ $result->total_questions }}</td>
                        <td class="py-3">#{{ $result->rank }}</td>
                        <td class="py-3">{{ $result->submitted_at->format('d M, Y h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500 text-center">No exam results yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
