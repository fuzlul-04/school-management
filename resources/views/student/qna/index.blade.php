@extends('layouts.student')

@section('header-title', 'Q&A')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">My Questions</h2>
        <a href="{{ route('student.qna.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Ask Question</a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            @if($questions->count() > 0)
            <div class="space-y-4">
                @foreach($questions as $question)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs rounded-full @if($question->status === 'answered') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($question->status) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $question->created_at->format('d M, Y') }}</span>
                    </div>
                    <h3 class="font-medium text-gray-800">{{ $question->subject }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($question->question_text, 150) }}</p>
                    @if($question->status === 'answered')
                    <div class="mt-3 p-3 bg-green-50 rounded">
                        <p class="text-sm text-gray-700">{{ $question->answer_text }}</p>
                    </div>
                    @endif
                    <a href="{{ route('student.qna.show', $question->id) }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block">View Details</a>
                </div>
                @endforeach
            </div>
            {{ $questions->links() }}
            @else
            <p class="text-gray-500 text-center">No questions asked yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
