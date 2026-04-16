@extends('layouts.student')

@section('header-title', 'Question Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <span class="px-2 py-1 text-xs rounded-full @if($question->status === 'answered') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($question->status) }}
                </span>
                <h2 class="text-lg font-semibold text-gray-800 mt-2">{{ $question->subject }}</h2>
            </div>
            <a href="{{ route('student.qna.index') }}" class="text-indigo-600 hover:text-indigo-800">Back</a>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Question</h3>
                <p class="text-gray-800">{{ $question->question_text }}</p>
                <p class="text-sm text-gray-400 mt-2">Asked on {{ $question->created_at->format('d M, Y h:i A') }}</p>
            </div>

            @if($question->status === 'answered')
            <div class="border-t pt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Answer</h3>
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-gray-800">{{ $question->answer_text }}</p>
                    <p class="text-sm text-gray-500 mt-2">
                        Answered by {{ $question->answered_by }} on {{ $question->answered_at->format('d M, Y h:i A') }}
                    </p>
                </div>
            </div>
            @else
            <div class="border-t pt-6">
                <p class="text-gray-500">Waiting for teacher response...</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
