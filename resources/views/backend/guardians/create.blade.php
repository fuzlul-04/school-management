@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Add Guardian</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('guardians.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="border-b pb-4 mb-4">
                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bangla Name</label>
                    <input type="text" name="bangla_name" value="{{ old('bangla_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Relation</label>
                    <select name="relation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Relation</option>
                        <option value="father" {{ old('relation') == 'father' ? 'selected' : '' }}>Father</option>
                        <option value="mother" {{ old('relation') == 'mother' ? 'selected' : '' }}>Mother</option>
                        <option value="guardian" {{ old('relation') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                        <option value="other" {{ old('relation') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone (Bangladesh Format)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+8801XXXXXXXXX or 01XXXXXXXXX" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alternative Phone</label>
                    <input type="text" name="alternative_phone" value="{{ old('alternative_phone') }}" placeholder="+8801XXXXXXXXX" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NID Number</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Profession</label>
                <input type="text" name="profession" value="{{ old('profession') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                <input type="file" name="profile_image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            @if(isset($students) && $students->count() > 0)
            <div class="border-b pb-4 mb-4">
                <h3 class="text-lg font-medium text-gray-900">Link to Student</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Select Student</label>
                    <select name="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_primary" value="1" class="rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <label class="ml-2 text-sm text-gray-700">Primary Guardian</label>
                </div>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Create Guardian</button>
                <a href="{{ route('guardians.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
