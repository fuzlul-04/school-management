@extends('layouts.student')

@section('header-title', 'Performance')

@section('content')
<div class="space-y-6">
    @if(count($achievements) > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Achievements</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($achievements as $achievement)
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                {{ $achievement }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Subject Performance</h2>
        </div>
        <div class="p-6">
            <canvas id="subjectChart" height="100"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Live Exam History</h2>
        </div>
        <div class="p-6">
            @if($liveExamResults->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Exam</th>
                        <th class="pb-3">Score</th>
                        <th class="pb-3">Rank</th>
                        <th class="pb-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($liveExamResults as $result)
                    <tr>
                        <td class="py-3">{{ $result->exam?->subject ?? 'N/A' }}</td>
                        <td class="py-3">{{ $result->score }}/{{ $result->exam?->total_marks }}</td>
                        <td class="py-3">#{{ $result->rank }}</td>
                        <td class="py-3">{{ $result->submitted_at->format('d M, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500 text-center">No exam results yet.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Practice Exam History</h2>
        </div>
        <div class="p-6">
            @if($practiceResults->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-3">Subject</th>
                        <th class="pb-3">Score</th>
                        <th class="pb-3">Accuracy</th>
                        <th class="pb-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($practiceResults as $result)
                    <tr>
                        <td class="py-3">{{ $result->subject }}</td>
                        <td class="py-3">{{ $result->score }}/{{ $result->total }}</td>
                        <td class="py-3">{{ $result->accuracy }}%</td>
                        <td class="py-3">{{ $result->taken_at->format('d M, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-gray-500 text-center">No practice results yet.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = @json($chartData);
const ctx = document.getElementById('subjectChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
</script>
@endpush
