@extends('layouts.student')

@section('header-title', 'Practice Exam')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Start Practice Exam</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('student.practice-exam.start') }}" method="POST">
                @csrf
                <div class="grid gap-4 md:grid-cols-3">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Chapter (Optional)</label>
                        <select name="chapter" class="w-full px-4 py-2 border rounded">
                            <option value="">All Chapters</option>
                            @foreach($chapters as $chapter)
                            <option value="{{ $chapter }}">{{ $chapter }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Number of Questions</label>
                        <select name="questions" required class="w-full px-4 py-2 border rounded">
                            <option value="10">10 Questions</option>
                            <option value="20">20 Questions</option>
                            <option value="30">30 Questions</option>
                            <option value="50">50 Questions</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Start Practice</button>
            </form>
        </div>
    </div>
</div>
@endsection
