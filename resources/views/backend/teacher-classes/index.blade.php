@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Teacher Class Assignments</h1>
        <a href="{{ route('teacher-classes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Assign Teacher to Class
        </a>
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

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($teacherClasses as $tc)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $tc->teacher->full_name }}</div>
                        <div class="text-gray-500 text-sm">{{ $tc->teacher->designation ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $tc->class->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $tc->section->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $tc->subject->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $tc->academicYear->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('teacher-classes.edit', $tc) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="{{ route('teacher-classes.destroy', $tc) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No assignments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $teacherClasses->links() }}
    </div>
</div>
@endsection