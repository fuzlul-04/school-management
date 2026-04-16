@extends('layouts.student')

@section('header-title', 'Practice Exam')

@section('content')
<div class="space-y-6">
    <form id="practiceForm" action="{{ route('student.practice-exam.submit') }}" method="POST">
        @csrf
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Practice Exam - {{ $questions->first()->subject }}</h2>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Submit</button>
            </div>
            <div class="p-6 space-y-6">
                @foreach($questions as $index => $question)
                <div class="border rounded-lg p-4">
                    <p class="font-medium text-gray-800 mb-3">Q{{ $index + 1 }}. {{ $question->question_text }}</p>
                    <div class="space-y-2">
                        @foreach(['a', 'b', 'c', 'd'] as $option)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-gray-700">{{ strtoupper($option) }}. {{ $question->{'option_'.$option} }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </form>
</div>
@endsection
