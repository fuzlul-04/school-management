@extends('layouts.student')

@section('header-title', 'Take Exam')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $exam->subject }} - Exam</h2>
                <p class="text-sm text-gray-500">Total Questions: {{ $exam->questions->count() }} | Time: {{ $exam->duration_minutes }} minutes</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Time Remaining</p>
                <p id="timer" class="text-2xl font-bold text-red-600">{{ gmdate('i:s', $timeRemaining) }}</p>
            </div>
        </div>
        <form id="examForm" action="{{ route('student.live-exams.submit', $exam->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                @foreach($exam->questions as $index => $question)
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
            <div class="p-6 border-t">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Submit Exam</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let timeRemaining = {{ $timeRemaining }};
const timerElement = document.getElementById('timer');
const form = document.getElementById('examForm');

const interval = setInterval(() => {
    timeRemaining--;
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    if (timeRemaining <= 0) {
        clearInterval(interval);
        form.submit();
    }
}, 1000);

let warningShown = false;
window.addEventListener('blur', () => {
    if (!warningShown) {
        alert('Please do not switch tabs during the exam!');
        warningShown = true;
    }
});
</script>
@endpush
