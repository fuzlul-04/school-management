@extends('layouts.student')

@section('header-title', 'Solve Sheets')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4 flex-wrap">
            <select name="subject" class="px-4 py-2 border rounded">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                @endforeach
            </select>
            <select name="exam_type" class="px-4 py-2 border rounded">
                <option value="">All Exam Types</option>
                @foreach($examTypes as $type)
                <option value="{{ $type }}" {{ request('exam_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
            <select name="year" class="px-4 py-2 border rounded">
                <option value="">All Years</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            @if($sheets->count() > 0)
            <div class="grid gap-4 md:grid-cols-3">
                @foreach($sheets as $sheet)
                <div class="border rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $sheet->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $sheet->subject }} - {{ $sheet->exam_type }} - {{ $sheet->year }}</p>
                    </div>
                    <a href="{{ route('student.solve-sheets.download', $sheet->id) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                        Download
                    </a>
                </div>
                @endforeach
            </div>
            {{ $sheets->links() }}
            @else
            <p class="text-gray-500 text-center">No solve sheets available for your class.</p>
            @endif
        </div>
    </div>
</div>
@endsection
