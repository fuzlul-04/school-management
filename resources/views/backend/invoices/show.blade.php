@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Invoice Details</h1>
        <div class="flex items-center space-x-3">
            <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Invoices
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                        <p class="text-sm text-gray-500">Created {{ $invoice->created_at->format('M d, Y') }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-800',
                            'issued' => 'bg-blue-100 text-blue-800',
                            'partial' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>

                <div class="border-t pt-6">
                    <h3 class="font-medium text-gray-800 mb-4">Invoice Items</h3>
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fee Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($invoice->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->feeType->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $item->description ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center text-gray-500">No items</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-800 mb-4">Payment History</h3>
                @if($invoice->payments->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Payment #</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($invoice->payments as $payment)
                        <tr>
                            <td class="px-4 py-3">{{ $payment->payment_number }}</td>
                            <td class="px-4 py-3">{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 uppercase">{{ $payment->method }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : 
                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-500 text-center py-4">No payments yet</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-800 mb-4">Student Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-500">Name</label>
                        <p class="text-gray-900">{{ $invoice->student->first_name ?? '' }} {{ $invoice->student->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500">Student ID</label>
                        <p class="text-gray-900">{{ $invoice->student->student_id ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500">Class</label>
                        <p class="text-gray-900">{{ $invoice->class->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-800 mb-4">Invoice Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Issue Date</span>
                        <span class="text-gray-900">{{ $invoice->issue_date }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Due Date</span>
                        <span class="text-gray-900 {{ $invoice->due_amount > 0 && $invoice->due_date < now()->toDateString() ? 'text-red-600 font-medium' : '' }}">
                            {{ $invoice->due_date }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="text-gray-900">{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Discount</span>
                        <span class="text-gray-900">-{{ number_format($invoice->discount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="font-medium text-gray-800">Total Amount</span>
                        <span class="font-bold text-gray-800">{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Paid</span>
                        <span class="text-green-600">{{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="font-medium text-gray-800">Due Amount</span>
                        <span class="font-bold text-red-600 {{ $invoice->due_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($invoice->due_amount, 2) }}
                        </span>
                    </div>
                </div>

                @if($invoice->due_amount > 0)
                <div class="mt-4">
                    <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="block w-full bg-green-600 text-white text-center px-4 py-2 rounded-lg hover:bg-green-700">
                        Record Payment
                    </a>
                </div>
                @endif
            </div>

            @if($invoice->notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-800 mb-2">Notes</h3>
                <p class="text-gray-600 text-sm">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
