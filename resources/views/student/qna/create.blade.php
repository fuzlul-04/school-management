@extends('layouts.student')

@section('header-title', 'Ask Question')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Ask a Question</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('student.qna.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select name="subject" required class="w-full px-4 py-2 border rounded">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your Question</label>
                        <textarea name="question_text" required rows="5" class="w-full px-4 py-2 border rounded" placeholder="Write your question here..."></textarea>
                    </div>
                </div>
                <div class="mt-4 flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Submit Question</button>
                    <a href="{{ route('student.qna.index') }}" class="px-6 py-2 border rounded hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
