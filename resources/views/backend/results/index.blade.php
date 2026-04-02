@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Results</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <form method="POST" action="{{ route('results.generate') }}" class="flex flex-wrap gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Generate Results</label>
                <select name="exam_id" class="border rounded px-3 py-2 min-w-[250px]" required>
                    <option value="">Select Exam</option>
                    @foreach(\App\Models\Exam::with('academicYear')->get() as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academicYear?->year }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Generate Results
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('results.index') }}" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Exam</label>
                <select name="exam_id" class="border rounded px-3 py-2 min-w-[200px]" onchange="this.form.submit()">
                    <option value="">All Exams</option>
                    @foreach($exams as $exam)
                    <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                        {{ $exam->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Grade</label>
                <select name="grade" class="border rounded px-3 py-2 min-w-[100px]" onchange="this.form.submit()">
                    <option value="">All Grades</option>
                    <option value="A+" {{ request('grade') == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A" {{ request('grade') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="A-" {{ request('grade') == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B" {{ request('grade') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ request('grade') == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ request('grade') == 'D' ? 'selected' : '' }}>D</option>
                    <option value="F" {{ request('grade') == 'F' ? 'selected' : '' }}>F</option>
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Marks</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">GPA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($results as $result)
                <tr>
                    <td class="px-6 py-4">
                        @if($result->rank == 1)
                        <span class="text-yellow-500 font-bold">🥇 {{ $result->rank }}</span>
                        @elseif($result->rank == 2)
                        <span class="text-gray-400 font-bold">🥈 {{ $result->rank }}</span>
                        @elseif($result->rank == 3)
                        <span class="text-orange-400 font-bold">🥉 {{ $result->rank }}</span>
                        @else
                        {{ $result->rank }}
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $result->student?->student_id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $result->student?->first_name }} {{ $result->student?->last_name }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $result->exam?->name }}</td>
                    <td class="px-6 py-4">{{ number_format($result->total_mark, 2) }}</td>
                    <td class="px-6 py-4 font-medium">{{ number_format($result->gpa, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($result->grade == 'A+') bg-green-100 text-green-800
                            @elseif(in_array($result->grade, ['A', 'A-'])) bg-blue-100 text-blue-800
                            @elseif($result->grade == 'B') bg-yellow-100 text-yellow-800
                            @elseif($result->grade == 'C') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $result->grade }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('results.show', $result) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        <a href="{{ route('results.export-pdf', $result) }}" class="text-green-600 hover:text-green-900">PDF</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No results found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $results->links() }}
    </div>
</div>
@endsection
