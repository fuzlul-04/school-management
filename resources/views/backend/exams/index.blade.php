@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Exams</h1>
        <a href="{{ route('exams.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Create Exam
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('exams.index') }}" class="flex flex-wrap gap-4">
            <select name="academic_year_id" class="border rounded px-3 py-2 text-gray-900 bg-white">
                <option value="">All Academic Years</option>
                @foreach($academicYears as $year)
                <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                    {{ $year->start_year }}
                </option>
                @endforeach
            </select>
            <select name="type" class="border rounded px-3 py-2">
                <option value="">All Types</option>
                <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="ct" {{ request('type') == 'ct' ? 'selected' : '' }}>Class Test</option>
                <option value="terminal" {{ request('type') == 'terminal' ? 'selected' : '' }}>Terminal</option>
                <option value="final" {{ request('type') == 'final' ? 'selected' : '' }}>Final</option>
                <option value="annual" {{ request('type') == 'annual' ? 'selected' : '' }}>Annual</option>
            </select>
            <select name="is_published" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                <option value="yes" {{ request('is_published') == 'yes' ? 'selected' : '' }}>Published</option>
                <option value="no" {{ request('is_published') == 'no' ? 'selected' : '' }}>Unpublished</option>
            </select>
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Academic Year</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Range</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($exams as $exam)
                <tr>
                    <td class="px-6 py-4 font-medium">{{ $exam->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($exam->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $exam->academicYear?->year }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $exam->start_date->format('d M Y') }} - {{ $exam->end_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($exam->is_published === 'yes')
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                        @else
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Unpublished</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('exams.show', $exam) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        <a href="{{ route('exams.edit', $exam) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <a href="{{ route('exams.marks-entry', $exam) }}" class="text-green-600 hover:text-green-900">Marks</a>
                        <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No exams found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $exams->links() }}
    </div>
</div>
@endsection
