@extends('layouts.student')

@section('header-title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    @if($student)
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('student.live-class') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-green-500">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Live Classes</h3>
                    <p class="text-sm text-gray-500">Join today's classes</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.live-exams.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-blue-500">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Live Exams</h3>
                    <p class="text-sm text-gray-500">Take scheduled exams</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.course-content') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-indigo-500">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Course Content</h3>
                    <p class="text-sm text-gray-500">Study materials</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.past-classes') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-purple-500">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Past Classes</h3>
                    <p class="text-sm text-gray-500">Recorded videos</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.past-exams.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-yellow-500">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Past Exams</h3>
                    <p class="text-sm text-gray-500">View results</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.practice-exam.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-pink-500">
            <div class="flex items-center gap-4">
                <div class="bg-pink-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Practice Exam</h3>
                    <p class="text-sm text-gray-500">MCQ practice</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.solve-sheets') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-teal-500">
            <div class="flex items-center gap-4">
                <div class="bg-teal-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Solve Sheets</h3>
                    <p class="text-sm text-gray-500">Download PDFs</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.qna.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-orange-500">
            <div class="flex items-center gap-4">
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Q&A</h3>
                    <p class="text-sm text-gray-500">Ask questions</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.performance') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-cyan-500">
            <div class="flex items-center gap-4">
                <div class="bg-cyan-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Performance</h3>
                    <p class="text-sm text-gray-500">View analytics</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.payments') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border-l-4 border-red-500">
            <div class="flex items-center gap-4">
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Payments</h3>
                    <p class="text-sm text-gray-500">View dues</p>
                </div>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Profile</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-6">
                <div class="bg-gray-100 p-4 rounded-full">
                    <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">{{ $student->first_name }} {{ $student->last_name }}</h3>
                    <p class="text-sm text-gray-500">Student ID: {{ $student->student_id }}</p>
                    <p class="text-sm text-gray-500">Class: {{ $student->class?->name ?? 'N/A' }} @if($student->section) - {{ $student->section->name }} @endif</p>
                    <p class="text-sm text-gray-500">Academic Year: {{ $student->academicYear?->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Results</h2>
        </div>
        <div class="p-6">
            @if($results->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Exam</th>
                        <th class="pb-3">Total Marks</th>
                        <th class="pb-3">GPA</th>
                        <th class="pb-3">Grade</th>
                        <th class="pb-3">Rank</th>
                        <th class="pb-3">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($results as $result)
                    <tr>
                        <td class="py-3">{{ $result->exam?->name ?? 'N/A' }}</td>
                        <td class="py-3">{{ $result->total_mark }}</td>
                        <td class="py-3">{{ $result->gpa }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $result->grade }}</span>
                        </td>
                        <td class="py-3">{{ $result->rank ?? 'N/A' }}</td>
                        <td class="py-3">{{ $result->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500">No results published yet.</p>
            @endif
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-yellow-800">No student profile found. Please contact the administrator.</p>
    </div>
    @endif
</div>
@endsection
