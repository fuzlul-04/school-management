@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Finance Dashboard</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Invoices</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_invoices'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Issued</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_issued'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Paid</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_paid'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Due</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_due'], 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Paid Invoices</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['paid_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Partial Payments</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['partial_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Unpaid Invoices</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['unpaid_count'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-800">Recent Payments</h2>
                <a href="{{ route('payments.index') }}" class="text-sm text-blue-600 hover:text-blue-900">View All</a>
            </div>
            @if($recentPayments->count() > 0)
            <div class="space-y-3">
                @foreach($recentPayments as $payment)
                <div class="flex items-center justify-between py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-800">{{ $payment->invoice->student->first_name ?? '' }} {{ $payment->invoice->student->last_name ?? '' }}</p>
                        <p class="text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-green-600">{{ number_format($payment->amount, 2) }}</p>
                        <p class="text-xs text-gray-500 uppercase">{{ $payment->method }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">No recent payments</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-800">Overdue Invoices</h2>
                <a href="{{ route('finance.due-invoices') }}" class="text-sm text-blue-600 hover:text-blue-900">View All</a>
            </div>
            @if($dueInvoices->count() > 0)
            <div class="space-y-3">
                @foreach($dueInvoices as $invoice)
                <div class="flex items-center justify-between py-2 border-b">
                    <div>
                        <a href="{{ route('invoices.show', $invoice) }}" class="font-medium text-gray-800 hover:text-blue-600">
                            {{ $invoice->invoice_number }}
                        </a>
                        <p class="text-sm text-gray-500">{{ $invoice->student->first_name ?? '' }} {{ $invoice->student->last_name ?? '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-red-600">{{ number_format($invoice->due_amount, 2) }}</p>
                        <p class="text-xs text-red-500">Due: {{ $invoice->due_date }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">No overdue invoices</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('invoices.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <p class="text-sm text-gray-500">Invoices</p>
            <p class="text-lg font-medium text-blue-600">Manage →</p>
        </a>
        <a href="{{ route('fee-structures.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <p class="text-sm text-gray-500">Fee Structures</p>
            <p class="text-lg font-medium text-blue-600">Manage →</p>
        </a>
        <a href="{{ route('finance.student-summary') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <p class="text-sm text-gray-500">Student Summary</p>
            <p class="text-lg font-medium text-blue-600">View →</p>
        </a>
        <a href="{{ route('finance.reports') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <p class="text-sm text-gray-500">Reports</p>
            <p class="text-lg font-medium text-blue-600">View →</p>
        </a>
    </div>
</div>
@endsection
