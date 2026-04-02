@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $subject->name }}</h1>
            <p class="text-gray-500">Code: {{ $subject->code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('subjects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Back</a>
            <a href="{{ route('subjects.edit', $subject) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Subject Details</h3>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Bangla Name:</dt>
                    <dd class="font-medium">{{ $subject->bangla_name ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Type:</dt>
                    <dd class="font-medium">{{ ucfirst($subject->type) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Full Mark:</dt>
                    <dd class="font-medium">{{ $subject->full_mark }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Pass Mark:</dt>
                    <dd class="font-medium">{{ $subject->pass_mark }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Status:</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full {{ $subject->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($subject->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Assigned Teachers</h3>
            @if($subject->teacherSubjects->count() > 0)
                <ul class="space-y-2">
                    @foreach($subject->teacherSubjects as $ts)
                    <li class="p-2 bg-gray-50 rounded">
                        {{ $ts->teacher->full_name }}
                        <span class="text-gray-500 text-sm">({{ $ts->academicYear->name ?? 'N/A' }})</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-sm">No teachers assigned yet.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-700">Assigned Classes</h3>
        </div>

        <form action="{{ route('subjects.assign-class') }}" method="POST" class="mb-4 p-4 bg-gray-50 rounded">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Class</option>
                        @foreach(\App\Models\Classe::with('academicYear')->get() as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Year</option>
                        @foreach(\App\Models\AcademicYear::where('status', 'active')->get() as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Credit Hour</label>
                    <input type="number" name="credit_hour" value="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Assign to Class</button>
        </form>

        @if($subject->classes->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Class</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Academic Year</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Credit Hour</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($subject->classes as $class)
                    <tr>
                        <td class="px-4 py-2">{{ $class->name }}</td>
                        <td class="px-4 py-2">{{ $class->academicYear->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $class->pivot->credit_hour ?? 1 }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('subjects.remove-class', ['class_id' => $class->id, 'subject_id' => $subject->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Remove from this class?')">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 text-sm">No classes assigned yet.</p>
        @endif
    </div>
</div>
@endsection