@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">{{ $exam->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('exams.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Exams
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Type</div>
            <div class="text-lg font-medium">{{ ucfirst($exam->type) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Academic Year</div>
            <div class="text-lg font-medium">{{ $exam->academicYear?->year }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Date Range</div>
            <div class="text-lg font-medium">{{ $exam->start_date->format('d M Y') }} - {{ $exam->end_date->format('d M Y') }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Status</div>
            <div class="text-lg font-medium">
                @if($exam->is_published === 'yes')
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                @else
                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Unpublished</span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Exam Details</h2>
            <div class="flex gap-2">
                <a href="{{ route('exams.marks-entry', $exam) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Enter Marks
                </a>
                <form action="{{ route('exams.publish', $exam) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="is_published" value="{{ $exam->is_published === 'yes' ? 'no' : 'yes' }}">
                    <button type="submit" class="bg-{{ $exam->is_published === 'yes' ? 'red' : 'green' }}-600 text-white px-4 py-2 rounded hover:bg-{{ $exam->is_published === 'yes' ? 'red' : 'green' }}-700">
                        {{ $exam->is_published === 'yes' ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
                <a href="{{ route('exams.edit', $exam) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>

        @if($exam->remarks)
        <div class="mb-4">
            <div class="text-sm text-gray-500">Remarks</div>
            <div>{{ $exam->remarks }}</div>
        </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-gray-500">Total Students</div>
                <div class="text-xl font-bold">{{ $totalStudents }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Total Subjects</div>
                <div class="text-xl font-bold">{{ $totalSubjects }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Entered Marks</h2>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Students Entered</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Average Marks</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($exam->marks->groupBy('subject_id') as $subjectId => $marks)
                <tr>
                    <td class="px-6 py-4">{{ $marks->first()->subject?->name }}</td>
                    <td class="px-6 py-4">{{ $marks->count() }}</td>
                    <td class="px-6 py-4">{{ round($marks->avg('total_mark'), 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No marks entered yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
