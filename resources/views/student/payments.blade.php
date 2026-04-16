@extends('layouts.student')

@section('header-title', 'Payments')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-blue-800">{{ session('info') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">My Payments</h2>
        </div>
        <div class="p-6">
            @if($payments->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Month</th>
                        <th class="pb-3">Year</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Paid Date</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($payments as $payment)
                    <tr>
                        <td class="py-3">{{ \Carbon\Carbon::createFromDate($payment->year, $payment->month, 1)->format('F') }}</td>
                        <td class="py-3">{{ $payment->year }}</td>
                        <td class="py-3">${{ number_format($payment->amount, 2) }}</td>
                        <td class="py-3">
                            @if($payment->status === 'paid')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Unpaid</span>
                            @endif
                        </td>
                        <td class="py-3">{{ $payment->paid_at ? $payment->paid_at->format('d M, Y') : '-' }}</td>
                        <td class="py-3">
                            @if($payment->status === 'unpaid')
                            <form action="{{ route('student.payments.pay', $payment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-medium">Pay Now</button>
                            </form>
                            @else
                            <a href="{{ route('student.payments.receipt', $payment->id) }}" class="text-green-600 hover:text-green-800 font-medium">Download Receipt</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $payments->links() }}
            @else
            <p class="text-gray-500 text-center">No payment records found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
