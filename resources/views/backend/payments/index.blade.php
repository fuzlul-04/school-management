@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Payments</h1>
        <a href="{{ route('payments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Record Payment
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Method</label>
                <select name="method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bkash" {{ request('method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                    <option value="nagad" {{ request('method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                    <option value="rocket" {{ request('method') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                    <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="other" {{ request('method') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="md:col-span-4 flex items-end">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr>
                    <td class="px-6 py-4">
                        <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            {{ $payment->payment_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4">{{ $payment->invoice->invoice_number ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $payment->invoice->student->first_name ?? '-' }} {{ $payment->invoice->student->last_name ?? '' }}</td>
                    <td class="px-6 py-4">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 uppercase">{{ $payment->method }}</td>
                    <td class="px-6 py-4 font-medium text-green-600">{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'success' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'failed' => 'bg-red-100 text-red-800',
                                'refunded' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $payment->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No payments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $payments->links() }}
    </div>
</div>
@endsection
