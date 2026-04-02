@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Payment Details</h1>
        <a href="{{ route('payments.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Payments
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">{{ $payment->payment_number }}</h2>
                @php
                    $statusColors = [
                        'success' => 'bg-green-100 text-green-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-gray-100 text-gray-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Payment Date</span>
                    <span class="text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Method</span>
                    <span class="text-gray-900 uppercase">{{ $payment->method }}</span>
                </div>
                @if($payment->transaction_id)
                <div class="flex justify-between">
                    <span class="text-gray-500">Transaction ID</span>
                    <span class="text-gray-900">{{ $payment->transaction_id }}</span>
                </div>
                @endif
                @if($payment->gateway)
                <div class="flex justify-between">
                    <span class="text-gray-500">Gateway</span>
                    <span class="text-gray-900">{{ $payment->gateway }}</span>
                </div>
                @endif
                @if($payment->notes)
                <div>
                    <span class="text-gray-500">Notes</span>
                    <p class="text-gray-900 mt-1">{{ $payment->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-medium text-gray-800 mb-4">Invoice Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-500">Invoice Number</label>
                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                        {{ $payment->invoice->invoice_number }}
                    </a>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Student</label>
                    <p class="text-gray-900">{{ $payment->invoice->student->first_name ?? '' }} {{ $payment->invoice->student->last_name ?? '' }}</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500">Class</label>
                    <p class="text-gray-900">{{ $payment->invoice->class->name ?? '-' }}</p>
                </div>
                <div class="border-t pt-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Invoice Total</span>
                        <span class="text-gray-900">{{ number_format($payment->invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Amount Paid</span>
                        <span class="text-green-600">{{ number_format($payment->invoice->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Due</span>
                        <span class="text-red-600 font-medium">{{ number_format($payment->invoice->due_amount, 2) }}</span>
                    </div>
                </div>
                <div class="border-t pt-4">
                    <div class="flex justify-between text-lg">
                        <span class="font-medium text-gray-800">Payment Amount</span>
                        <span class="font-bold text-green-600">{{ number_format($payment->amount, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($payment->status === 'success')
            <div class="mt-6">
                <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Are you sure you want to refund this payment?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Refund Payment
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
