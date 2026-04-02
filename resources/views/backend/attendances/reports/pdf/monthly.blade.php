<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Report - {{ $monthName }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .high { color: #16a34a; }
        .medium { color: #ca8a04; }
        .low { color: #dc2626; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monthly Attendance Report</h1>
        <p>{{ $monthName }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Section</th>
                <th class="text-center">Present</th>
                <th class="text-center">Absent</th>
                <th class="text-center">Total Days</th>
                <th class="text-center">Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                <td>{{ $student->class->name ?? '-' }}</td>
                <td>{{ $student->section->name ?? '-' }}</td>
                <td class="text-center">{{ $student->present_days }}</td>
                <td class="text-center">{{ $student->absent_days }}</td>
                <td class="text-center">{{ $student->total_days }}</td>
                <td class="text-center">
                    @php
                    $percentage = $student->attendance_percentage;
                    $class = $percentage >= 90 ? 'high' : ($percentage >= 75 ? 'medium' : 'low');
                    @endphp
                    <span class="{{ $class }}">{{ $percentage }}%</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Generated on: {{ now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
