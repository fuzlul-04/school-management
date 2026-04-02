@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Create Invoice</h1>
        <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('invoices.store') }}" class="space-y-6" id="invoiceForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class *</label>
                    <select name="class_id" id="classSelect" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year *</label>
                    <select name="academic_year_id" id="academicYearSelect" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Student *</label>
                    <select name="student_id" id="studentSelect" required disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Class First</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Issue Date *</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', now()->toDateString()) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(30)->toDateString()) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                    <select name="discount_type" id="discountType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="fixed">Fixed Amount</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Discount Value</label>
                    <input type="number" name="discount" id="discountValue" step="0.01" min="0" value="{{ old('discount', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
            </div>

            <div class="border-t pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Invoice Items</h3>
                    <button type="button" id="loadFeeStructure" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 text-sm">
                        Load Fee Structure
                    </button>
                </div>

                <table class="w-full" id="itemsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fee Type</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr>
                            <td class="px-4 py-2">
                                <select name="items[0][fee_type_id]" class="fee-type-select block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    <option value="">Select Fee Type</option>
                                    @foreach($feeTypes ?? [] as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" name="items[0][description]" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" name="items[0][amount]" step="0.01" min="0" class="amount-input block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </td>
                        <td class="px-4 py-2">
                            <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">×</button>
                        </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="addItem" onclick="addNewItem()" class="mt-2 text-blue-600 hover:text-blue-900 text-sm">+ Add Item</button>
            </div>

            <div class="border-t pt-6 flex justify-end">
                <div class="w-64">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" id="subtotal">0.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Discount:</span>
                        <span class="font-medium" id="displayDiscount">0.00</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span id="total">0.00</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
console.log('Invoice create script loaded');
let itemIndex = 1;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready');
    
    // Class select change
    const classSelect = document.getElementById('classSelect');
    if (classSelect) {
        classSelect.addEventListener('change', function() {
            const classId = this.value;
            console.log('Class changed:', classId);
            if (classId) {
                loadStudents(classId);
            } else {
                const select = document.getElementById('studentSelect');
                select.innerHTML = '<option value="">Select Class First</option>';
                select.disabled = true;
            }
        });
    }
    
    // Add Item button
    const addItemBtn = document.getElementById('addItem');
    if (addItemBtn) {
        console.log('Add Item button found');
        addItemBtn.addEventListener('click', function() {
            console.log('Add Item clicked');
            addNewItem();
        });
    }
});

function loadStudents(classId) {
    console.log('Loading students for class:', classId);
    const url = '/invoices/students/' + classId;
    console.log('Fetch URL:', url);
    
    fetch(url)
        .then(res => {
            console.log('Response status:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('Students loaded:', data);
            const select = document.getElementById('studentSelect');
            select.innerHTML = '<option value="">Select Student</option>';
            if (data.length === 0) {
                select.innerHTML = '<option value="">No students found</option>';
            } else {
                data.forEach(student => {
                    select.innerHTML += '<option value="' + student.id + '">' + student.first_name + ' ' + student.last_name + ' (' + student.student_id + ')</option>';
                });
            }
            select.disabled = false;
        })
        .catch(err => {
            console.error('Error loading students:', err);
        });
}

function addNewItem() {
    console.log('Adding new item');
    const tbody = document.getElementById('itemsBody');
    if (!tbody) {
        console.error('itemsBody not found');
        return;
    }
    
    const row = document.createElement('tr');
    
    let feeTypeOptions = '<option value="">Select Fee Type</option>';
    @foreach($feeTypes ?? [] as $type)
        feeTypeOptions += '<option value="{{ $type->id }}">{{ $type->name }}</option>';
    @endforeach
    
    row.innerHTML = 
        '<td class="px-4 py-2">' +
            '<select name="items[' + itemIndex + '][fee_type_id]" class="fee-type-select block w-full rounded-md border-gray-300 shadow-sm text-sm">' +
                feeTypeOptions +
            '</select>' +
        '</td>' +
        '<td class="px-4 py-2">' +
            '<input type="text" name="items[' + itemIndex + '][description]" value="" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">' +
        '</td>' +
        '<td class="px-4 py-2">' +
            '<input type="number" name="items[' + itemIndex + '][amount]" step="0.01" min="0" value="0" class="amount-input block w-full rounded-md border-gray-300 shadow-sm text-sm">' +
        '</td>' +
        '<td class="px-4 py-2">' +
            '<button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">×</button>' +
        '</td>';
    
    tbody.appendChild(row);
    itemIndex++;
    console.log('New item added, index:', itemIndex);
}

function removeRow(button) {
    button.closest('tr').remove();
    calculateTotal();
}

function calculateTotal() {
    console.log('calculateTotal called');
    let subtotal = 0;
    document.querySelectorAll('.amount-input').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const discountType = document.getElementById('discountType').value;
    const discountValue = parseFloat(document.getElementById('discountValue').value) || 0;
    
    console.log('discountType:', discountType);
    console.log('discountValue:', discountValue);
    console.log('subtotal:', subtotal);
    
    let discount = 0;
    if (discountType === 'percentage') {
        discount = (subtotal * discountValue) / 100;
    } else {
        discount = discountValue;
    }
    
    console.log('calculated discount:', discount);
    
    const total = Math.max(0, subtotal - discount);
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('displayDiscount').textContent = discount.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
}

// Attach event listeners to initial row
document.querySelectorAll('.amount-input').forEach(input => {
    input.addEventListener('input', calculateTotal);
});

document.getElementById('discountType').addEventListener('change', calculateTotal);
document.getElementById('discountValue').addEventListener('input', calculateTotal);

// Fee Type change event - auto-populate amount
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('fee-type-select')) {
        const select = e.target;
        const row = select.closest('tr');
        const amountInput = row.querySelector('.amount-input');
        const descInput = row.querySelector('input[name*="[description]"]');
        const feeTypeId = select.value;
        
        const classId = document.getElementById('classSelect').value;
        const academicYearId = document.getElementById('academicYearSelect').value;
        
        if (feeTypeId && classId && academicYearId) {
            fetch('/invoices/fee-amount/' + feeTypeId + '/' + classId + '/' + academicYearId)
                .then(res => res.json())
                .then(data => {
                    amountInput.value = data.amount;
                    if (data.description && !descInput.value) {
                        descInput.value = data.description;
                    }
                    calculateTotal();
                });
        }
    }
});
</script>
@endpush
@endsection
