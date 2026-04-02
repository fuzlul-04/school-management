<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $result->student->first_name }} {{ $result->student->last_name }}</title>
    <style>
        @font-face {
            font-family: 'Nikosh';
            src: url('{{ public_path('fonts/Nikosh.ttf') }}') format('truetype');
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Nikosh', 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 10px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .school-address {
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .school-contact {
            font-size: 12px;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #333;
        }
        
        .student-info {
            margin-bottom: 20px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 5px 10px;
            border: 1px solid #333;
        }
        
        .info-label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 140px;
        }
        
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .marks-table th,
        .marks-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }
        
        .marks-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .marks-table .subject-name {
            text-align: left;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .summary-table td {
            padding: 8px 15px;
            border: 1px solid #333;
        }
        
        .summary-label {
            font-weight: bold;
            background-color: #f5f5f5;
            text-align: right;
            width: 150px;
        }
        
        .summary-value {
            font-weight: bold;
        }
        
        .grade-scale {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #333;
        }
        
        .grade-scale h4 {
            margin-bottom: 10px;
        }
        
        .grade-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .grade-table th,
        .grade-table td {
            border: 1px solid #333;
            padding: 5px 10px;
            text-align: center;
        }
        
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        
        .grade-a-plus { background-color: #d4edda; }
        .grade-a { background-color: #d4edda; }
        .grade-a-minus { background-color: #cce5ff; }
        .grade-b { background-color: #fff3cd; }
        .grade-c { background-color: #ffeeba; }
        .grade-d { background-color: #f8d7da; }
        .grade-f { background-color: #f8d7da; }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="school-name">{{ $schoolInfo['name'] }}</div>
            <div class="school-address">{{ $schoolInfo['address'] }}</div>
            <div class="school-contact">
                Phone: {{ $schoolInfo['phone'] }} | Email: {{ $schoolInfo['email'] }}
            </div>
        </div>

        <div class="report-title">
            ACADEMIC REPORT CARD
        </div>

        <div class="student-info">
            <table class="info-table">
                <tr>
                    <td class="info-label">Student Name:</td>
                    <td><strong>{{ $result->student->first_name }} {{ $result->student->last_name }}</strong></td>
                    <td class="info-label">Student ID:</td>
                    <td>{{ $result->student->student_id }}</td>
                </tr>
                <tr>
                    <td class="info-label">Class:</td>
                    <td>{{ $result->student->class?->name }}</td>
                    <td class="info-label">Section:</td>
                    <td>{{ $result->student->section?->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Exam Name:</td>
                    <td>{{ $result->exam->name }}</td>
                    <td class="info-label">Academic Year:</td>
                    <td>{{ $result->exam->academicYear?->year }}</td>
                </tr>
            </table>
        </div>

        <table class="marks-table">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Subject</th>
                    <th>Written</th>
                    <th>MCQ</th>
                    <th>Practical</th>
                    <th>CA</th>
                    <th>Total</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marks as $mark)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="subject-name">
                        {{ $mark->subject->name }}
                        @if($mark->subject->bangla_name)
                        <br><small>({{ $mark->subject->bangla_name }})</small>
                        @endif
                    </td>
                    <td>{{ $mark->written_mark ?? '-' }}</td>
                    <td>{{ $mark->mcq_mark ?? '-' }}</td>
                    <td>{{ $mark->practical_mark ?? '-' }}</td>
                    <td>{{ $mark->ca_mark ?? '-' }}</td>
                    <td><strong>{{ number_format($mark->total_mark, 2) }}</strong></td>
                    <td class="grade-{{ strtolower(str_replace('+', '-plus', $mark->grade)) }}">
                        <strong>{{ $mark->grade }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="6" style="text-align: right;">Total Marks:</td>
                    <td>{{ number_format($marks->sum('total_mark'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <table class="summary-table">
            <tr>
                <td class="summary-label">Total Marks:</td>
                <td class="summary-value">{{ number_format($result->total_mark, 2) }}</td>
                <td class="summary-label">GPA:</td>
                <td class="summary-value">{{ number_format($result->gpa, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Grade:</td>
                <td class="summary-value grade-{{ strtolower(str_replace('+', '-plus', $result->grade)) }}" style="padding: 5px 15px;">
                    {{ $result->grade }}
                </td>
                <td class="summary-label">Rank:</td>
                <td class="summary-value">
                    @if($result->rank == 1)
                    🥇 1st
                    @elseif($result->rank == 2)
                    🥈 2nd
                    @elseif($result->rank == 3)
                    🥉 3rd
                    @else
                    {{ $result->rank }}{{ match($result->rank % 10) {1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th'} }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="summary-label">Remarks:</td>
                <td colspan="3">{{ $result->remarks ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="grade-scale">
            <h4>Grade Scale:</h4>
            <table class="grade-table">
                <thead>
                    <tr>
                        <th>Marks Range</th>
                        <th>Grade</th>
                        <th>Grade Point</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>80 - 100</td><td class="grade-a-plus">A+</td><td>5.00</td></tr>
                    <tr><td>70 - 79</td><td class="grade-a">A</td><td>4.00</td></tr>
                    <tr><td>60 - 69</td><td class="grade-a-minus">A-</td><td>3.50</td></tr>
                    <tr><td>50 - 59</td><td class="grade-b">B</td><td>3.00</td></tr>
                    <tr><td>40 - 49</td><td class="grade-c">C</td><td>2.00</td></tr>
                    <tr><td>33 - 39</td><td class="grade-d">D</td><td>1.00</td></tr>
                    <tr><td>0 - 32</td><td class="grade-f">F</td><td>0.00</td></tr>
                </tbody>
            </table>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">Class Teacher</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Principal / Headmaster</div>
            </div>
        </div>

        <div class="footer">
            Generated on {{ now()->format('d M Y, h:i A') }}
        </div>
    </div>
</body>
</html>
