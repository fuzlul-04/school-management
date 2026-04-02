<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Absentee List</title>
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
        .badge { display: inline-block; padding: 2px 8px; background-color: #fee2e2; color: #dc2626; border-radius: 4px; font-weight: bold; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Absentee List</h1>
        <p>{{ $startDate }} to {{ $endDate }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Section</th>
                <th class="text-center">Total Absent</th>
                <th>Absent Dates</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groupedAbsentees as $item)
            <tr>
                <td>{{ $item->student->first_name }} {{ $item->student->last_name }}</td>
                <td>{{ $item->student->class->name ?? '-' }}</td>
                <td>{{ $item->student->section->name ?? '-' }}</td>
                <td class="text-center"><span class="badge">{{ $item->total_absent }}</span></td>
                <td>{{ implode(', ', $item->absent_dates) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No absentees found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        Generated on: {{ now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
