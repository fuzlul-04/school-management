@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Fee Structure Details</h1>
        <a href="{{ route('fee-structures.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Fee Type</label>
                <p class="mt-1 text-lg text-gray-900">{{ $feeStructure->feeType->name ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Class</label>
                <p class="mt-1 text-lg text-gray-900">{{ $feeStructure->class->name ?? 'All Classes' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Academic Year</label>
                <p class="mt-1 text-lg text-gray-900">{{ $feeStructure->academicYear->name ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Amount</label>
                <p class="mt-1 text-lg text-gray-900">{{ number_format($feeStructure->amount, 2) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Mandatory</label>
                <p class="mt-1 text-lg text-gray-900">
                    @if($feeStructure->is_mandatory === 'yes')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Mandatory
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Optional
                        </span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Currency</label>
                <p class="mt-1 text-lg text-gray-900">{{ $feeStructure->currency }}</p>
            </div>
        </div>

        <div class="mt-6 flex items-center space-x-3">
            <a href="{{ route('fee-structures.edit', $feeStructure) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                Edit
            </a>
            <form action="{{ route('fee-structures.destroy', $feeStructure) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
