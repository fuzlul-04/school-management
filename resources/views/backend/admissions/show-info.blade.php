@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Student Found</h1>
        <a href="{{ route('admissions.index') }}" class="text-gray-600 hover:text-gray-800">Back to Admissions</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-start gap-6">
            @if($student->profile_image)
            <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->first_name }}" class="h-24 w-24 rounded-full object-cover">
            @else
            <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500 text-2xl">{{ substr($student->first_name, 0, 1) }}</span>
            </div>
            @endif
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h2>
                <p class="text-sm text-gray-500">Student ID: {{ $student->student_id }}</p>
                <div class="mt-2 flex flex-wrap gap-4">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Class: {{ $student->class->name ?? 'N/A' }}</span>
                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">Section: {{ $student->section->name ?? 'N/A' }}</span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Status: {{ $student->status }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Personal Information</h3>
                <dl class="mt-2 space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Date of Birth</dt>
                        <dd class="text-sm text-gray-900">{{ $student->date_of_birth?->format('Y-m-d') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Gender</dt>
                        <dd class="text-sm text-gray-900 capitalize">{{ $student->gender }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Religion</dt>
                        <dd class="text-sm text-gray-900">{{ $student->religion }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Blood Group</dt>
                        <dd class="text-sm text-gray-900">{{ $student->blood_group }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900">{{ $student->phone }}</dd>
                    </div>
                </dl>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Address</h3>
                <dl class="mt-2 space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500">Present Address</dt>
                        <dd class="text-sm text-gray-900">{{ $student->present_address }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Permanent Address</dt>
                        <dd class="text-sm text-gray-900">{{ $student->permanent_address }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @if($student->guardians->count() > 0)
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase">Guardians</h3>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($student->guardians as $guardian)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $guardian->first_name }} {{ $guardian->last_name }}</p>
                            <p class="text-sm text-gray-500">{{ $guardian->relation }}</p>
                        </div>
                        @if($guardian->pivot->is_primary)
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Primary</span>
                        @endif
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        <p>Phone: {{ $guardian->phone }}</p>
                        @if($guardian->profession)
                        <p>Profession: {{ $guardian->profession }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-6 border-t pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Enroll in New Class</h3>
            <form action="{{ route('admissions.enroll', $student) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Year</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Class</option>
                        @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Section</label>
                    <select name="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Enroll
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
