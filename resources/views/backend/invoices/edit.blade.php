@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Invoice</h1>
        <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Invoice Number</label>
                    <p class="mt-1 text-gray-900">{{ $invoice->invoice_number }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Student</label>
                    <p class="mt-1 text-gray-900">{{ $invoice->student->first_name ?? '' }} {{ $invoice->student->last_name ?? '' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <p class="mt-1 text-gray-900">{{ $invoice->class->name ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Issue Date</label>
                    <p class="mt-1 text-gray-900">{{ $invoice->issue_date }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date *</label>
                    <input type="date" name="due_date" value="{{ $invoice->due_date }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Discount</label>
                    <input type="number" name="discount" step="0.01" min="0" value="{{ $invoice->discount }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ $invoice->notes }}</textarea>
            </div>

            <div class="border-t pt-6">
                <h3 class="font-medium text-gray-800 mb-4">Invoice Summary</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Subtotal</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($invoice->subtotal, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Discount</p>
                        <p class="text-xl font-bold text-green-600">-{{ number_format($invoice->discount, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Paid</p>
                        <p class="text-xl font-bold text-green-600">{{ number_format($invoice->paid_amount, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Due</p>
                        <p class="text-xl font-bold text-red-600">{{ number_format($invoice->due_amount, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('invoices.show', $invoice) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Invoice
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
