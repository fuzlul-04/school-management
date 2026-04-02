@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Record Payment</h1>
        <a href="{{ route('payments.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Invoice *</label>
                    <select name="invoice_id" id="invoiceSelect" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Invoice</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" data-due="{{ $invoice->due_amount }}">
                                {{ $invoice->invoice_number }} - {{ $invoice->student->first_name ?? '' }} {{ $invoice->student->last_name ?? '' }} (Due: {{ number_format($invoice->due_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount *</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Payment Method *</label>
                    <select name="method" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="bank">Bank</option>
                        <option value="card">Card</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                    <input type="text" name="transaction_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Gateway</label>
                    <input type="text" name="gateway" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
            </div>

            <div id="invoiceDetails" class="hidden bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium text-gray-800 mb-2">Invoice Details</h3>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Student:</span>
                        <span id="detailStudent" class="font-medium"></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Class:</span>
                        <span id="detailClass" class="font-medium"></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Due Amount:</span>
                        <span id="detailDue" class="font-medium text-red-600"></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('invoiceSelect').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const dueAmount = option.dataset.due;
    
    if (dueAmount) {
        document.getElementById('amount').value = dueAmount;
        document.getElementById('invoiceDetails').classList.remove('hidden');
        
        const text = option.text;
        const parts = text.split(' - ');
        if (parts.length >= 2) {
            document.getElementById('detailStudent').textContent = parts[1].split(' (Due:')[0];
        }
        document.getElementById('detailDue').textContent = dueAmount;
    } else {
        document.getElementById('amount').value = '';
        document.getElementById('invoiceDetails').classList.add('hidden');
    }
});
</script>
@endpush
@endsection
