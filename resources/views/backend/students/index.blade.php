@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Students</h1>
        <a href="{{ route('students.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add Student
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID, phone..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="w-40">
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                </select>
            </div>
            <div class="w-40">
                <select name="class_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Classes</option>
                    @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ request('class_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($students as $student)
                <tr>
                    <td class="px-6 py-4">
                        @if($student->profile_image)
                        <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->first_name }}" class="h-10 w-10 rounded-full object-cover">
                        @else
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">{{ substr($student->first_name, 0, 1) }}</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $student->student_id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                        @if($student->bangla_name)
                        <div class="text-sm text-gray-500">{{ $student->bangla_name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $student->class->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $student->section->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 capitalize">{{ $student->gender }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $student->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @switch($student->status)
                        @case('active')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                        @break
                        @case('inactive')
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Inactive</span>
                        @break
                        @case('transferred')
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Transferred</span>
                        @break
                        @case('graduated')
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Graduated</span>
                        @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                        <a href="{{ route('students.edit', $student) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No students found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
@endsection
