@extends('layouts.student')

@section('header-title', 'Live Classes')

@section('content')
<div class="space-y-6">
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    @if($todayClasses->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Today's Classes</h2>
        </div>
        <div class="p-6">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($todayClasses as $class)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($class->status === 'live') bg-green-100 text-green-800
                            @elseif($class->status === 'ended') bg-gray-100 text-gray-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($class->status) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $class->start_time->format('h:i A') }} - {{ $class->end_time->format('h:i A') }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ $class->subject }}</h3>
                    <p class="text-sm text-gray-500">{{ $class->teacher_name }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $class->class_name }}</p>
                    @if($class->status === 'live' && $class->join_link)
                    <a href="{{ $class->join_link }}" target="_blank" class="mt-3 inline-block px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                        Join Now
                    </a>
                    @elseif($class->status === 'scheduled')
                    <button disabled class="mt-3 px-4 py-2 bg-gray-300 text-gray-500 text-sm rounded cursor-not-allowed">
                        Join Now
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($upcomingClasses->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Upcoming Classes</h2>
        </div>
        <div class="p-6">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($upcomingClasses as $class)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Scheduled</span>
                        <span class="text-sm text-gray-500">{{ $class->start_time->format('d M, h:i A') }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ $class->subject }}</h3>
                    <p class="text-sm text-gray-500">{{ $class->teacher_name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($todayClasses->count() === 0 && $upcomingClasses->count() === 0)
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-center">No live classes scheduled for your class.</p>
    </div>
    @endif
</div>
@endsection
