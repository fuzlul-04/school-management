@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Student Fee Details</h1>
        <a href="{{ route('finance.student-summary') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $student->first_name }} {{ $student->last_name }}</h2>
                        <p class="text-sm text-gray-500">ID: {{ $student->student_id }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm text-gray-500">Class</label>
                        <p class="text-gray-900">{{ $student->class->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500">Section</label>
                        <p class="text-gray-900">{{ $student->section->name ?? '-' }}</p>
                    </div>
                </div>

                @if($student->concession_type)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-yellow-800">Concession Applied</h3>
                    <p class="text-sm text-yellow-700">
                        {{ $student->concession_type === 'percentage' ? $student->concession_amount . '%' : number_format($student->concession_amount, 2) }}
                        @if($student->concession_reason)
                            - {{ $student->concession_reason }}
                        @endif
                    </p>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-800 mb-4">Invoice History</h3>
                @if($invoices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Issue Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Paid</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Due</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($invoices as $invoice)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $invoice->issue_date }}</td>
                                <td class="px-4 py-3">{{ $invoice->due_date }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right text-green-600">{{ number_format($invoice->paid_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right font-medium {{ $invoice->due_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($invoice->due_amount, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($invoice->status === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No invoices found</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-800 mb-4">Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Issued</span>
                        <span class="font-medium text-gray-900">{{ number_format($summary['total_issued'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Paid</span>
                        <span class="font-medium text-green-600">{{ number_format($summary['total_paid'], 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="font-medium text-gray-800">Total Due</span>
                        <span class="font-bold text-red-600">{{ number_format($summary['total_due'], 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium text-gray-800 mb-4">Invoice Breakdown</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Paid Invoices</span>
                        <span class="font-medium text-green-600">{{ $invoices->where('status', 'paid')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Partial Payments</span>
                        <span class="font-medium text-yellow-600">{{ $invoices->where('status', 'partial')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Unpaid</span>
                        <span class="font-medium text-red-600">{{ $invoices->whereIn('status', ['issued', 'unpaid'])->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
