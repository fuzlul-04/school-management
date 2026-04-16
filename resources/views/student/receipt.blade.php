<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .receipt { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>Payment Receipt</h1>
            <p>School Management System</p>
        </div>
        
        <table>
            <tr>
                <th>Receipt ID:</th>
                <td>#{{ $payment->id }}</td>
            </tr>
            <tr>
                <th>Student Name:</th>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
            </tr>
            <tr>
                <th>Student ID:</th>
                <td>{{ $student->student_id }}</td>
            </tr>
            <tr>
                <th>Class:</th>
                <td>{{ $student->class?->name }}</td>
            </tr>
            <tr>
                <th>Month:</th>
                <td>{{ \Carbon\Carbon::createFromDate($payment->year, $payment->month, 1)->format('F') }}</td>
            </tr>
            <tr>
                <th>Year:</th>
                <td>{{ $payment->year }}</td>
            </tr>
            <tr>
                <th>Amount:</th>
                <td>${{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>Paid</td>
            </tr>
            <tr>
                <th>Paid Date:</th>
                <td>{{ $payment->paid_at->format('d M, Y h:i A') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
