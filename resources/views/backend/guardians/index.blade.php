@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Guardians</h1>
        <a href="{{ route('guardians.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add Guardian
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('guardians.index') }}" class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone, NID..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="w-40">
                <select name="relation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Relations</option>
                    <option value="father" {{ request('relation') == 'father' ? 'selected' : '' }}>Father</option>
                    <option value="mother" {{ request('relation') == 'mother' ? 'selected' : '' }}>Mother</option>
                    <option value="guardian" {{ request('relation') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Photo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Relation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profession</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($guardians as $guardian)
                <tr>
                    <td class="px-6 py-4">
                        @if($guardian->profile_image)
                        <img src="{{ asset('storage/' . $guardian->profile_image) }}" alt="{{ $guardian->first_name }}" class="h-10 w-10 rounded-full object-cover">
                        @else
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">{{ substr($guardian->first_name, 0, 1) }}</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $guardian->first_name }} {{ $guardian->last_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 capitalize">{{ $guardian->relation }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $guardian->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $guardian->profession ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($guardian->status == 'active')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                        @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('guardians.show', $guardian) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                        <a href="{{ route('guardians.edit', $guardian) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                        <form action="{{ route('guardians.destroy', $guardian) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No guardians found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $guardians->links() }}
    </div>
</div>
@endsection
