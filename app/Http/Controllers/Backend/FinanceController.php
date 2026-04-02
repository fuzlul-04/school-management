<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::where('status', 'active')->get();
        $classes = Classe::where('status', 'active')->get();

        $query = Invoice::query();

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        $invoices = $query->get();

        $stats = [
            'total_invoices' => $invoices->count(),
            'total_issued' => $invoices->sum('total_amount'),
            'total_paid' => $invoices->sum('paid_amount'),
            'total_due' => $invoices->sum('due_amount'),
            'paid_count' => $invoices->where('status', 'paid')->count(),
            'partial_count' => $invoices->where('status', 'partial')->count(),
            'unpaid_count' => $invoices->whereIn('status', ['issued', 'unpaid'])->count(),
        ];

        $recentPayments = Payment::with('invoice.student')
            ->where('status', 'success')
            ->orderByDesc('payment_date')
            ->limit(10)
            ->get();

        $dueInvoices = Invoice::with('student', 'class')
            ->where('due_amount', '>', 0)
            ->whereDate('due_date', '<', now()->toDateString())
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        return view('backend.finance.index', compact('stats', 'recentPayments', 'dueInvoices', 'academicYears', 'classes'));
    }

    public function dueInvoices(Request $request)
    {
        $query = Invoice::with(['student', 'class', 'academicYear']);

        if ($request->overdue_only) {
            $query->where('due_amount', '>', 0)
                  ->whereDate('due_date', '<', now()->toDateString());
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $query->where('due_amount', '>', 0);

        $invoices = $query->orderBy('due_date')->paginate(15);
        $classes = Classe::where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();

        return view('backend.finance.due-invoices', compact('invoices', 'classes', 'academicYears'));
    }

    public function studentSummary(Request $request)
    {
        $query = Student::with(['class', 'invoices' => function ($q) {
            $q->with('payments');
        }]);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $students = $query->where('status', 'active')->orderBy('student_id')->paginate(15);

        $students->getCollection()->transform(function ($student) {
            $invoices = $student->invoices;
            
            $student->total_issued = $invoices->sum('total_amount');
            $student->total_paid = $invoices->sum('paid_amount');
            $student->total_due = $invoices->sum('due_amount');
            $student->invoice_count = $invoices->count();
            $student->paid_invoices = $invoices->where('status', 'paid')->count();
            $student->partial_invoices = $invoices->where('status', 'partial')->count();
            $student->unpaid_invoices = $invoices->whereIn('status', ['issued', 'unpaid'])->count();

            return $student;
        });

        $classes = Classe::where('status', 'active')->get();

        return view('backend.finance.student-summary', compact('students', 'classes'));
    }

    public function studentDetail(Student $student)
    {
        $student->load(['class', 'academicYear', 'invoices.items.feeType', 'invoices.payments']);

        $invoices = $student->invoices()->orderByDesc('id')->get();

        $summary = [
            'total_issued' => $invoices->sum('total_amount'),
            'total_paid' => $invoices->sum('paid_amount'),
            'total_due' => $invoices->sum('due_amount'),
        ];

        return view('backend.finance.student-detail', compact('student', 'invoices', 'summary'));
    }

    public function reports(Request $request)
    {
        $startDate = $request->from_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->to_date ?? now()->endOfMonth()->toDateString();

        $payments = Payment::with('invoice.student', 'invoice.class')
            ->where('status', 'success')
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->orderByDesc('payment_date')
            ->get();

        $totalCollected = $payments->sum('amount');

        $byMethod = $payments->groupBy('method')
            ->map(fn($group) => $group->sum('amount'));

        $byClass = Payment::with('invoice.class')
            ->where('status', 'success')
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->get()
            ->groupBy(fn($p) => $p->invoice->class->name ?? 'Unknown')
            ->map(fn($group) => $group->sum('amount'));

        $dailyCollection = Payment::with('invoice.class')
            ->where('status', 'success')
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->get()
            ->groupBy(fn($p) => $p->payment_date->toDateString())
            ->map(fn($group) => $group->sum('amount'));

        return view('backend.finance.reports', compact(
            'payments', 'totalCollected', 'byMethod', 'byClass', 'dailyCollection',
            'startDate', 'endDate'
        ));
    }
}
