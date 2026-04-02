@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Fee Type Details</h1>
        <a href="{{ route('fee-types.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Name</label>
                <p class="mt-1 text-lg text-gray-900">{{ $feeType->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Bangla Name</label>
                <p class="mt-1 text-lg text-gray-900">{{ $feeType->bangla_name ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Type</label>
                <p class="mt-1 text-lg text-gray-900">{{ ucfirst($feeType->type) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Recurring</label>
                <p class="mt-1 text-lg text-gray-900">
                    @if($feeType->is_recurring === 'yes')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Yes
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            No
                        </span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p class="mt-1 text-lg text-gray-900">
                    @if($feeType->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                    @endif
                </p>
            </div>
        </div>

        @if($feeType->description)
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-500">Description</label>
            <p class="mt-1 text-gray-900">{{ $feeType->description }}</p>
        </div>
        @endif

        <div class="mt-6 flex items-center space-x-3">
            <a href="{{ route('fee-types.edit', $feeType) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                Edit
            </a>
            <form action="{{ route('fee-types.destroy', $feeType) }}" method="POST" class="inline">
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
