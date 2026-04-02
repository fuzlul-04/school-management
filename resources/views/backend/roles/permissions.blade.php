@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Manage Permissions: {{ $role->name }}</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('roles.permissions.update', $role) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            @php
            $groupedPermissions = $permissions->groupBy('module');
            @endphp

            @foreach($groupedPermissions as $module => $perms)
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">{{ $module ?? 'General' }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($perms as $permission)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Permissions</button>
            <a href="{{ route('roles.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Back</a>
        </div>
    </form>
</div>
@endsection