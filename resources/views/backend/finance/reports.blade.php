@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Finance Reports</h1>
        <a href="{{ route('finance.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" name="from_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" name="to_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Collected</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($totalCollected, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-medium text-gray-800 mb-4">Collection by Method</h3>
            @if($byMethod->count() > 0)
            <div class="space-y-3">
                @foreach($byMethod as $method => $amount)
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600 uppercase">{{ $method }}</span>
                    <span class="font-medium text-green-600">{{ number_format($amount, 2) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">No data</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-medium text-gray-800 mb-4">Collection by Class</h3>
            @if($byClass->count() > 0)
            <div class="space-y-3">
                @foreach($byClass as $className => $amount)
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">{{ $className }}</span>
                    <span class="font-medium text-green-600">{{ number_format($amount, 2) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">No data</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-medium text-gray-800 mb-4">Daily Collection</h3>
        @if($dailyCollection->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($dailyCollection as $date => $amount)
                    <tr>
                        <td class="px-4 py-2 text-gray-600">{{ $date }}</td>
                        <td class="px-4 py-2 text-right font-medium text-green-600">{{ number_format($amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-4">No data</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <h3 class="font-medium text-gray-800 p-6 pb-0">Payment Transactions</h3>
        @if($payments->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr>
                    <td class="px-6 py-4">
                        <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            {{ $payment->payment_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4">{{ $payment->invoice->invoice_number ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $payment->invoice->student->first_name ?? '' }} {{ $payment->invoice->student->last_name ?? '' }}</td>
                    <td class="px-6 py-4">{{ $payment->invoice->class->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 uppercase">{{ $payment->method }}</td>
                    <td class="px-6 py-4 text-right font-medium text-green-600">{{ number_format($payment->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500 text-center py-8">No payments in this period</p>
        @endif
    </div>
</div>
@endsection
