@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $classroom->name }}</h1>
            <p class="text-gray-500">Academic Year: {{ $classroom->academicYear->name ?? 'N/A' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('classrooms.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Back
            </a>
            <a href="{{ route('classrooms.edit', $classroom) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit Class
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Class Details</h3>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Section:</dt>
                    <dd class="font-medium">{{ $classroom->section ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Room Number:</dt>
                    <dd class="font-medium">{{ $classroom->room_number ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Capacity:</dt>
                    <dd class="font-medium">{{ $classroom->capacity }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Status:</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full {{ $classroom->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($classroom->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-700">Sections</h3>
                <a href="{{ route('classrooms.sections.create', $classroom) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                    + Add Section
                </a>
            </div>
            @if($classroom->sections->count() > 0)
                <ul class="space-y-2">
                    @foreach($classroom->sections as $section)
                    <li class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div>
                            <span class="font-medium">{{ $section->name }}</span>
                            <span class="text-gray-500 text-sm ml-2">({{ $section->capacity }} students)</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('classrooms.sections.edit', [$classroom, $section]) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                            <form action="{{ route('classrooms.sections.destroy', [$classroom, $section]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Delete this section?')">Delete</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-sm">No sections created yet.</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-700">Subjects</h3>
                <a href="{{ route('subjects.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">Manage</a>
            </div>
            @if($classroom->subjects->count() > 0)
                <ul class="space-y-2">
                    @foreach($classroom->subjects as $classSubject)
                    <li class="p-2 bg-gray-50 rounded">
                        <div class="font-medium">{{ $classSubject->subject->name }}</div>
                        <div class="text-gray-500 text-sm">
                            {{ $classSubject->subject->code ?? 'N/A' }} - {{ $classSubject->credit_hour ?? 1 }} credit
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-sm">No subjects assigned yet.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Enrolled Students</h3>
        <p class="text-gray-500">{{ $classroom->students->count() }} students enrolled</p>
    </div>
</div>
@endsection