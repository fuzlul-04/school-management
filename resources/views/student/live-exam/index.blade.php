@extends('layouts.student')

@section('header-title', 'Live Exams')

@section('content')
<div class="space-y-6">
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-blue-800">{{ session('info') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Available Exams</h2>
        </div>
        <div class="p-6">
            @if($exams->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Subject</th>
                        <th class="pb-3">Class</th>
                        <th class="pb-3">Start Time</th>
                        <th class="pb-3">Duration</th>
                        <th class="pb-3">Marks</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($exams as $exam)
                    <tr>
                        <td class="py-3 font-medium">{{ $exam->subject }}</td>
                        <td class="py-3">{{ $exam->class_name }}</td>
                        <td class="py-3">{{ $exam->start_time->format('d M, h:i A') }}</td>
                        <td class="py-3">{{ $exam->duration_minutes }} min</td>
                        <td class="py-3">{{ $exam->total_marks }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($exam->status === 'live') bg-green-100 text-green-800
                                @elseif($exam->status === 'published') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($exam->status) }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if($exam->status === 'live')
                            <a href="{{ route('student.live-exams.take', $exam->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Take Exam</a>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500 text-center">No exams available for your class.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Exam Results</h2>
        </div>
        <div class="p-6">
            <a href="{{ route('student.live-exams.results') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View All Results →</a>
        </div>
    </div>
</div>
@endsection
