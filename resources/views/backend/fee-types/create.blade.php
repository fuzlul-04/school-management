@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Add Fee Type</h1>
        <a href="{{ route('fee-types.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('fee-types.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bangla Name</label>
                    <input type="text" name="bangla_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Type *</label>
                    <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="tuition">Tuition</option>
                        <option value="admission">Admission</option>
                        <option value="exam">Exam</option>
                        <option value="library">Library</option>
                        <option value="transport">Transport</option>
                        <option value="food">Food</option>
                        <option value="uniform">Uniform</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Recurring *</label>
                    <select name="is_recurring" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="yes">Yes - Recurring</option>
                        <option value="no">No - One-time</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create Fee Type
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
