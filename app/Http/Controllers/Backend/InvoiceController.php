<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['student', 'class', 'academicYear']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->from_date) {
            $query->whereDate('issue_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('issue_date', '<=', $request->to_date);
        }

        if ($request->due_only) {
            $query->where('due_amount', '>', 0);
        }

        $invoices = $query->orderByDesc('id')->paginate(15);
        $classes = Classe::where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();

        return view('backend.invoices.index', compact('invoices', 'classes', 'academicYears'));
    }

    public function create()
    {
        $classes = Classe::where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();
        $feeTypes = FeeType::all();
        return view('backend.invoices.create', compact('classes', 'academicYears', 'feeTypes'));
    }

    public function getStudents($classId)
    {
        $students = Student::where('class_id', $classId)
            ->where('status', 'active')
            ->select('id', 'student_id', 'first_name', 'last_name')
            ->get();

        return response()->json($students);
    }

    public function getFeeStructure(Request $request)
    {
        $academicYearId = $request->academic_year_id;
        $classId = $request->class_id;

        $feeStructures = FeeStructure::with('feeType')
            ->where('academic_year_id', $academicYearId)
            ->where(function ($q) use ($classId) {
                $q->where('class_id', $classId)
                  ->orWhereNull('class_id');
            })
            ->get();

        return response()->json($feeStructures);
    }

    public function getFeeAmount($feeTypeId, $classId, $academicYearId)
    {
        $feeStructure = FeeStructure::where('fee_type_id', $feeTypeId)
            ->where('academic_year_id', $academicYearId)
            ->where(function ($q) use ($classId) {
                $q->where('class_id', $classId)
                  ->orWhereNull('class_id');
            })
            ->first();

        return response()->json([
            'amount' => $feeStructure ? $feeStructure->amount : 0,
            'description' => $feeStructure ? $feeStructure->feeType->name : ''
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'items' => 'required|array|min:1',
            'items.*.fee_type_id' => 'required|exists:fee_types,id',
            'items.*.amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        $student = Student::with('class')->findOrFail($request->student_id);

        $subtotal = collect($request->items)->sum('amount');
        
        $discountType = $request->discount_type ?? 'fixed';
        $discountValue = $request->discount ?? 0;
        
        if ($discountType === 'percentage') {
            $discount = ($subtotal * $discountValue) / 100;
        } else {
            $discount = $discountValue;
        }
        
        $totalAmount = max(0, $subtotal - $discount);

        $invoiceNumber = $this->generateInvoiceNumber();

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'student_id' => $request->student_id,
                'academic_year_id' => $request->academic_year_id,
                'class_id' => $request->class_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'due_amount' => $totalAmount,
                'status' => 'issued',
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_type_id' => $item['fee_type_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $item['amount'],
                    'total_price' => $item['amount'] * ($item['quantity'] ?? 1),
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create invoice: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['student', 'class', 'academicYear', 'items.feeType', 'payments']);
        return view('backend.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['items', 'student', 'class']);
        $classes = Classe::where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();
        $feeTypes = FeeType::where('status', 'active')->get();
        return view('backend.invoices.edit', compact('invoice', 'classes', 'academicYears', 'feeTypes'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $discount = $request->discount ?? 0;
        $newTotal = $invoice->subtotal - $discount;
        $newDue = max(0, $newTotal - $invoice->paid_amount);

        $status = 'issued';
        if ($invoice->paid_amount > 0 && $newDue > 0) {
            $status = 'partial';
        } elseif ($newDue <= 0) {
            $status = 'paid';
        }

        $invoice->update([
            'discount' => $discount,
            'total_amount' => $newTotal,
            'due_amount' => $newDue,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'status' => $status,
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->payments()->exists()) {
            return back()->with('error', 'Cannot delete invoice with payments.');
        }

        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function generate(Request $request)
    {
        $classes = Classe::where('status', 'active')->get();
        $academicYears = AcademicYear::where('status', 'active')->get();
        return view('backend.invoices.generate', compact('classes', 'academicYears'));
    }

    public function generatePreview(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'due_date' => 'required|date',
        ]);

        $class = Classe::with('students')->findOrFail($request->class_id);
        $students = $class->students()->where('status', 'active')->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No active students found in this class.');
        }

        $feeStructures = FeeStructure::with('feeType')
            ->where('academic_year_id', $request->academic_year_id)
            ->where(function ($q) use ($request) {
                $q->where('class_id', $request->class_id)
                  ->orWhereNull('class_id');
            })
            ->where('is_mandatory', 'yes')
            ->get();

        if ($feeStructures->isEmpty()) {
            return back()->with('error', 'No fee structure found for this class.');
        }

        $previews = [];
        $totalAmount = 0;

        foreach ($students as $student) {
            $subtotal = $feeStructures->sum('amount');
            $discount = 0;

            if ($student->concession_type && $student->concession_amount) {
                if ($student->concession_type === 'percentage') {
                    $discount = ($subtotal * $student->concession_amount) / 100;
                } else {
                    $discount = min($student->concession_amount, $subtotal);
                }
            }

            $studentTotal = max(0, $subtotal - $discount);
            $totalAmount += $studentTotal;

            $previews[] = [
                'student' => $student,
                'items' => $feeStructures,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $studentTotal,
                'has_concession' => $student->concession_type ? true : false,
            ];
        }

        $request->flash(['class_id', 'academic_year_id', 'due_date']);

        return view('backend.invoices.generate-preview', compact('previews', 'class', 'totalAmount'));
    }

    public function generateStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'due_date' => 'required|date',
        ]);

        $class = Classe::with('students')->findOrFail($request->class_id);
        $students = $class->students()->where('status', 'active')->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No active students found in this class.');
        }

        $feeStructures = FeeStructure::with('feeType')
            ->where('academic_year_id', $request->academic_year_id)
            ->where(function ($q) use ($request) {
                $q->where('class_id', $request->class_id)
                  ->orWhereNull('class_id');
            })
            ->where('is_mandatory', 'yes')
            ->get();

        if ($feeStructures->isEmpty()) {
            return back()->with('error', 'No fee structure found for this class.');
        }

        $created = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($students as $student) {
                $existingInvoice = Invoice::where('student_id', $student->id)
                    ->where('academic_year_id', $request->academic_year_id)
                    ->whereIn('status', ['issued', 'partial', 'unpaid'])
                    ->exists();

                if ($existingInvoice) {
                    $skipped++;
                    continue;
                }

                $subtotal = $feeStructures->sum('amount');
                $discount = 0;

                if ($student->concession_type && $student->concession_amount) {
                    if ($student->concession_type === 'percentage') {
                        $discount = ($subtotal * $student->concession_amount) / 100;
                    } else {
                        $discount = min($student->concession_amount, $subtotal);
                    }
                }

                $totalAmount = max(0, $subtotal - $discount);
                $invoiceNumber = $this->generateInvoiceNumber();

                $invoice = Invoice::create([
                    'invoice_number' => $invoiceNumber,
                    'student_id' => $student->id,
                    'academic_year_id' => $request->academic_year_id,
                    'class_id' => $request->class_id,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total_amount' => $totalAmount,
                    'paid_amount' => 0,
                    'due_amount' => $totalAmount,
                    'status' => 'issued',
                    'issue_date' => now()->toDateString(),
                    'due_date' => $request->due_date,
                ]);

                foreach ($feeStructures as $fs) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'fee_type_id' => $fs->fee_type_id,
                        'description' => $fs->feeType->name,
                        'quantity' => 1,
                        'unit_price' => $fs->amount,
                        'total_price' => $fs->amount,
                    ]);
                }

                $created++;
            }

            DB::commit();
            return redirect()->route('invoices.index')->with('success', "Generated {$created} invoices. Skipped {$skipped} students with existing invoices.");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to generate invoices: ' . $e->getMessage());
        }
    }

    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";

        $lastInvoice = Invoice::where('invoice_number', 'like', "{$prefix}%")
            ->orderByDesc('invoice_number')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) str_replace($prefix, '', $lastInvoice->invoice_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
