@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Admission</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Search Student by Phone</h2>
        <form action="{{ route('admissions.search') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Enter phone number (+8801XXXXXXXXX or 01XXXXXXXXX)" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">Admission Applications</h2>
            <a href="{{ route('admissions.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                New Admission
            </a>
        </div>

        <div class="flex flex-wrap gap-4 mb-4">
            <form method="GET" action="{{ route('admissions.index') }}" class="flex flex-wrap gap-4 w-full">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID, phone..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="w-40">
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="admitted" {{ request('status') == 'admitted' ? 'selected' : '' }}>Admitted</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">App ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($admissions as $admission)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $admission->application_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $admission->first_name }} {{ $admission->last_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $admission->class->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $admission->phone }}</td>
                        <td class="px-6 py-4">
                            @switch($admission->status)
                            @case('pending')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                            @break
                            @case('approved')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Approved</span>
                            @break
                            @case('rejected')
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Rejected</span>
                            @break
                            @case('admitted')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Admitted</span>
                            @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admissions.show', $admission) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                            <a href="{{ route('admissions.edit', $admission) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                            <form action="{{ route('admissions.destroy', $admission) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No admissions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $admissions->links() }}
        </div>
    </div>
</div>
@endsection
