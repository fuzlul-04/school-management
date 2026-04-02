@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Teacher Dashboard</h1>
        <a href="{{ route('attendances.mark') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
            Mark Attendance
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Classes</h2>
        </div>
        <div class="p-6">
            @if($classes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($classes as $teacherClass)
                <a href="{{ route('students.index', ['class_id' => $teacherClass->class_id]) }}" class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $teacherClass->class->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">
                                @if($teacherClass->section)
                                Section: {{ $teacherClass->section->name }}
                                @endif
                            </p>
                            @if($teacherClass->subject)
                            <p class="text-sm text-gray-500">Subject: {{ $teacherClass->subject->name }}</p>
                            @endif
                        </div>
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <p class="text-gray-500">No classes assigned yet.</p>
            @endif
        </div>
    </div>

    @if($teacher)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Profile</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="bg-gray-100 p-4 rounded-full">
                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $teacher->first_name }} {{ $teacher->last_name }}</h3>
                    <p class="text-sm text-gray-500">Employee ID: {{ $teacher->employee_id }}</p>
                    <p class="text-sm text-gray-500">Designation: {{ $teacher->designation }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
