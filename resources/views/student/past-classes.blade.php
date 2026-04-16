@extends('layouts.student')

@section('header-title', 'Past Classes')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4 flex-wrap">
            <input type="text" name="search" placeholder="Search videos..." value="{{ request('search') }}" class="px-4 py-2 border rounded">
            <select name="subject" class="px-4 py-2 border rounded">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                @endforeach
            </select>
            <select name="chapter" class="px-4 py-2 border rounded">
                <option value="">All Chapters</option>
                @foreach($chapters as $chapter)
                <option value="{{ $chapter }}" {{ request('chapter') == $chapter ? 'selected' : '' }}>{{ $chapter }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Filter</button>
        </form>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        @foreach($videos as $video)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gray-200 h-40 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 mb-1">{{ $video->title }}</h3>
                <p class="text-sm text-gray-500">{{ $video->subject }} - {{ $video->chapter }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $video->recorded_at->format('d M Y') }}</p>
                <div class="flex items-center justify-between mt-3">
                    <button onclick="playVideo('{{ $video->video_url }}')" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Watch Now</button>
                    <button onclick="toggleBookmark({{ $video->id }})" class="text-gray-400 hover:text-red-500">
                        <svg class="w-6 h-6 {{ in_array($video->id, $bookmarks) ? 'text-red-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        {{ $videos->links() }}
    </div>

    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-4 max-w-3xl w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Video Player</h3>
                <button onclick="closeVideo()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div id="videoContainer"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function playVideo(url) {
    const container = document.getElementById('videoContainer');
    container.innerHTML = `<iframe src="${url}" class="w-full h-96" frameborder="0" allowfullscreen></iframe>`;
    document.getElementById('videoModal').classList.remove('hidden');
    document.getElementById('videoModal').classList.add('flex');
}

function closeVideo() {
    document.getElementById('videoModal').classList.add('hidden');
    document.getElementById('videoModal').classList.remove('flex');
    document.getElementById('videoContainer').innerHTML = '';
}

function toggleBookmark(id) {
    fetch(`/student/past-classes/${id}/bookmark`, {
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
