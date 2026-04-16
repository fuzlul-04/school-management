@extends('layouts.student')

@section('header-title', 'Exam Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">{{ $result->exam?->subject }} - Answer Review</h2>
            <a href="{{ route('student.past-exams.index') }}" class="text-indigo-600 hover:text-indigo-800">Back</a>
        </div>
        <div class="p-6">
            <div class="grid gap-4 md:grid-cols-3 mb-6">
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <p class="text-2xl font-bold text-indigo-600">{{ $result->score }}</p>
                    <p class="text-sm text-gray-500">Score</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">#{{ $result->rank }}</p>
                    <p class="text-sm text-gray-500">Rank</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600">{{ round(($result->correct_answers / $result->total_questions) * 100) }}%</p>
                    <p class="text-sm text-gray-500">Accuracy</p>
                </div>
            </div>

            @foreach($result->exam->questions as $index => $question)
            <div class="border rounded-lg p-4 mb-4">
                <p class="font-medium text-gray-800 mb-2">Q{{ $index + 1 }}. {{ $question->question_text }}</p>
                @foreach(['a', 'b', 'c', 'd'] as $option)
                <div class="flex items-center gap-2 mb-1 @if($option === $question->correct_option) text-green-600 font-medium @endif">
                    <span class="w-6">{{ strtoupper($option) }}.</span>
                    <span>{{ $question->{'option_'.$option} }}</span>
                    @if($option === $question->correct_option)
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    @endif
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
