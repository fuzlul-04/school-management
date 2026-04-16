@extends('layouts.student')

@section('header-title', 'Course Content')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    @foreach($grouped as $subject => $contents)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $subject }}</h2>
                <p class="text-sm text-gray-500">
                    {{ $subjectProgress[$subject]['completed'] }}/{{ $subjectProgress[$subject]['total'] }} chapters completed
                </p>
            </div>
            <div class="w-32 bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $subjectProgress[$subject]['percentage'] }}%"></div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($contents as $content)
                <div class="border rounded-lg p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleComplete({{ $content->id }})" class="w-6 h-6 rounded-full border-2 flex items-center justify-center 
                            @if(isset($progress[$content->id]) && $progress[$content->id]->is_completed) border-green-500 bg-green-500 text-white @else border-gray-300 @endif">
                            @if(isset($progress[$content->id]) && $progress[$content->id]->is_completed)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            @endif
                        </button>
                        <div>
                            <p class="font-medium text-gray-800">Chapter {{ $content->chapter_number }}: {{ $content->chapter_title }}</p>
                            @if($content->pdf_path)
                            <a href="{{ Storage::url($content->pdf_path) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">View PDF</a>
                            @endif
                            @if($content->video_url)
                            <a href="{{ $content->video_url }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 ml-2">Watch Video</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    @if($grouped->count() === 0)
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-center">No course content available for your class.</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleComplete(contentId) {
    fetch(`/student/course-content/${contentId}/complete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
