@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Student Fee Summary</h1>
        <a href="{{ route('finance.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <select name="class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Issued</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Paid</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Due</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Invoices</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($students as $student)
                <tr>
                    <td class="px-6 py-4">{{ $student->student_id }}</td>
                    <td class="px-6 py-4">{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td class="px-6 py-4">{{ $student->class->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($student->total_issued, 2) }}</td>
                    <td class="px-6 py-4 text-right text-green-600">{{ number_format($student->total_paid, 2) }}</td>
                    <td class="px-6 py-4 text-right font-medium {{ $student->total_due > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($student->total_due, 2) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                            {{ $student->paid_invoices }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">
                            {{ $student->partial_invoices }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 ml-1">
                            {{ $student->unpaid_invoices }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('finance.student-detail', $student) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No students found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $students->links() }}
    </div>
</div>
@endsection
