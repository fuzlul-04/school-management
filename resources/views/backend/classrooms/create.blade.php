@extends('layouts.backend')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Add Class</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('classrooms.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class Name</label>
                    <input type="text" name="name" placeholder="e.g., Class Six" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Numeric Value (for sorting)</label>
                    <input type="number" name="numeric_value" placeholder="e.g., 6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Room Number</label>
                    <input type="text" name="room_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input type="number" name="capacity" value="40" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sections (Optional)</label>
                <p class="text-gray-500 text-sm mb-3">Add sections for this class (e.g., A, B, C)</p>
                <div id="sections-container" class="space-y-2">
                    <div class="flex gap-2">
                        <input type="text" name="sections[]" placeholder="Section Name (e.g., A)" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <button type="button" onclick="addSection()" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700">+</button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Create</button>
                <a href="{{ route('classrooms.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function addSection() {
    const container = document.getElementById('sections-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="sections[]" placeholder="Section Name (e.g., A)" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        <button type="button" onclick="this.parentElement.remove()" class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700">×</button>
    `;
    container.appendChild(div);
}
</script>
@endsection
