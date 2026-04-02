@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Student Dashboard</h1>
    </div>

    @if($student)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Profile</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-6">
                <div class="bg-gray-100 p-4 rounded-full">
                    <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">{{ $student->first_name }} {{ $student->last_name }}</h3>
                    <p class="text-sm text-gray-500">Student ID: {{ $student->student_id }}</p>
                    <p class="text-sm text-gray-500">Class: {{ $student->class?->name ?? 'N/A' }} @if($student->section) - {{ $student->section->name }} @endif</p>
                    <p class="text-sm text-gray-500">Academic Year: {{ $student->academicYear?->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">My Fees</h2>
        </div>
        <div class="p-6">
            @if($invoices->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Invoice #</th>
                        <th class="pb-3">Fee Type</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Paid</th>
                        <th class="pb-3">Due</th>
                        <th class="pb-3">Due Date</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($invoices as $invoice)
                    <tr>
                        <td class="py-3">{{ $invoice->invoice_number }}</td>
                        <td class="py-3">
                            @php
                                $feeTypes = $invoice->items->pluck('feeType.name')->filter()->unique()->implode(', ');
                            @endphp
                            {{ $feeTypes ?: 'N/A' }}
                        </td>
                        <td class="py-3">${{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="py-3">${{ number_format($invoice->paid_amount, 2) }}</td>
                        <td class="py-3">${{ number_format($invoice->due_amount, 2) }}</td>
                        <td class="py-3">{{ $invoice->due_date?->format('d M Y') }}</td>
                        <td class="py-3">
                            @if($invoice->status === 'paid')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                            @elseif($invoice->status === 'partial')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Partial</span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Due</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($invoice->due_amount > 0)
                            <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Pay Now</a>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500">No invoices found.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Results</h2>
        </div>
        <div class="p-6">
            @if($results->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Exam</th>
                        <th class="pb-3">Total Marks</th>
                        <th class="pb-3">GPA</th>
                        <th class="pb-3">Grade</th>
                        <th class="pb-3">Rank</th>
                        <th class="pb-3">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($results as $result)
                    <tr>
                        <td class="py-3">{{ $result->exam?->name ?? 'N/A' }}</td>
                        <td class="py-3">{{ $result->total_mark }}</td>
                        <td class="py-3">{{ $result->gpa }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $result->grade }}</span>
                        </td>
                        <td class="py-3">{{ $result->rank ?? 'N/A' }}</td>
                        <td class="py-3">{{ $result->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500">No results published yet.</p>
            @endif
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-yellow-800">No student profile found. Please contact the administrator.</p>
    </div>
    @endif
</div>
@endsection
