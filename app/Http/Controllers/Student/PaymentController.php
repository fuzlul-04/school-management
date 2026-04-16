<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentPayment;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $payments = StudentPayment::where('student_id', $student->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('student.payments', compact('payments'));
    }

    public function pay($id)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $payment = StudentPayment::where('student_id', $student->id)
            ->findOrFail($id);

        if ($payment->status === 'paid') {
            return back()->with('info', 'This payment is already paid');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment successful');
    }

    public function downloadReceipt($id)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found');
        }

        $payment = StudentPayment::where('student_id', $student->id)
            ->findOrFail($id);

        if ($payment->status !== 'paid') {
            return back()->with('error', 'Receipt not available for unpaid dues');
        }

        $data = [
            'payment' => $payment,
            'student' => $student,
        ];

        $pdf = Pdf::loadView('student.receipt', $data);

        return $pdf->download('receipt-'.$payment->id.'.pdf');
    }
}
