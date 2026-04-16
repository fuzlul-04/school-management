@extends('layouts.student')

@section('header-title', 'Exam Result')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">{{ $exam->subject }} - Exam Result</h2>
        </div>
        <div class="p-6">
            <div class="grid gap-6 md:grid-cols-4">
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <p class="text-3xl font-bold text-indigo-600">{{ $result->score }}</p>
                    <p class="text-sm text-gray-500">Score</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-3xl font-bold text-green-600">{{ $result->correct_answers }}</p>
                    <p class="text-sm text-gray-500">Correct</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-3xl font-bold text-red-600">{{ $result->total_questions - $result->correct_answers }}</p>
                    <p class="text-sm text-gray-500">Wrong</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-3xl font-bold text-blue-600">#{{ $result->rank }}</p>
                    <p class="text-sm text-gray-500">Rank</p>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="{{ route('student.live-exams.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to Exams</a>
                <a href="{{ route('student.dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
