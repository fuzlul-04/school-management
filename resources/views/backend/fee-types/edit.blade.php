@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Fee Type</h1>
        <a href="{{ route('fee-types.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('fee-types.update', $feeType) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" value="{{ $feeType->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bangla Name</label>
                    <input type="text" name="bangla_name" value="{{ $feeType->bangla_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Type *</label>
                    <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="tuition" {{ $feeType->type === 'tuition' ? 'selected' : '' }}>Tuition</option>
                        <option value="admission" {{ $feeType->type === 'admission' ? 'selected' : '' }}>Admission</option>
                        <option value="exam" {{ $feeType->type === 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="library" {{ $feeType->type === 'library' ? 'selected' : '' }}>Library</option>
                        <option value="transport" {{ $feeType->type === 'transport' ? 'selected' : '' }}>Transport</option>
                        <option value="food" {{ $feeType->type === 'food' ? 'selected' : '' }}>Food</option>
                        <option value="uniform" {{ $feeType->type === 'uniform' ? 'selected' : '' }}>Uniform</option>
                        <option value="other" {{ $feeType->type === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Recurring *</label>
                    <select name="is_recurring" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="yes" {{ $feeType->is_recurring === 'yes' ? 'selected' : '' }}>Yes - Recurring</option>
                        <option value="no" {{ $feeType->is_recurring === 'no' ? 'selected' : '' }}>No - One-time</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="active" {{ $feeType->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $feeType->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ $feeType->description }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('fee-types.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Fee Type
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
