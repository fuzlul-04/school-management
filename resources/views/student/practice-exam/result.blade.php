@extends('layouts.student')

@section('header-title', 'Practice Result')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Practice Result</h2>
        </div>
        <div class="p-6">
            <div class="grid gap-4 md:grid-cols-3 mb-6">
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <p class="text-3xl font-bold text-indigo-600">{{ $result->score }}/{{ $result->total }}</p>
                    <p class="text-sm text-gray-500">Score</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-3xl font-bold text-green-600">{{ $result->accuracy }}%</p>
                    <p class="text-sm text-gray-500">Accuracy</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-3xl font-bold text-blue-600">{{ $result->subject }}</p>
                    <p class="text-sm text-gray-500">Subject</p>
                </div>
            </div>

            <h3 class="font-semibold text-gray-800 mb-4">Review Answers</h3>
            @foreach($questions as $index => $question)
            @php
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->correct_option;
            @endphp
            <div class="border rounded-lg p-4 mb-4 @if($isCorrect) border-green-200 bg-green-50 @else border-red-200 bg-red-50 @endif">
                <p class="font-medium text-gray-800 mb-2">Q{{ $index + 1 }}. {{ $question->question_text }}</p>
                <div class="space-y-1 text-sm">
                    @foreach(['a', 'b', 'c', 'd'] as $option)
                    <div class="flex items-center gap-2 @if($option === $question->correct_option) text-green-600 font-medium @endif @if($option === $userAnswer && !$isCorrect) text-red-600 @endif">
                        <span>{{ strtoupper($option) }}. {{ $question->{'option_'.$option} }}</span>
                        @if($option === $question->correct_option)
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        @endif
                        @if($option === $userAnswer && !$isCorrect)
                        <span class="text-red-500">(Your answer)</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @if($question->explanation)
                <div class="mt-2 p-2 bg-white rounded text-sm text-gray-600">
                    <strong>Explanation:</strong> {{ $question->explanation }}
                </div>
                @endif
            </div>
            @endforeach

            <a href="{{ route('student.practice-exam.index') }}" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Practice More</a>
        </div>
    </div>
</div>
@endsection
