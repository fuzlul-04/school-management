@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Preview Generated Invoices</h1>
        <a href="{{ route('invoices.generate') }}" class="text-gray-600 hover:text-gray-900">
            ← Go Back
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-medium text-gray-800">Class: {{ $class->name }}</h2>
                <p class="text-sm text-gray-500">Total Amount: {{ number_format($totalAmount, 2) }}</p>
            </div>
            <form method="POST" action="{{ route('invoices.generate-store') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ old('class_id') }}">
                <input type="hidden" name="academic_year_id" value="{{ old('academic_year_id') }}">
                <input type="hidden" name="due_date" value="{{ old('due_date') }}">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Confirm & Generate
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Discount</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Concession</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($previews as $preview)
                    <tr class="{{ $preview['has_concession'] ? 'bg-yellow-50' : '' }}">
                        <td class="px-4 py-3">{{ $preview['student']['student_id'] }}</td>
                        <td class="px-4 py-3">{{ $preview['student']['first_name'] }} {{ $preview['student']['last_name'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($preview['subtotal'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-green-600">-{{ number_format($preview['discount'], 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ number_format($preview['total'], 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($preview['has_concession'])
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Yes
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">No students found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
