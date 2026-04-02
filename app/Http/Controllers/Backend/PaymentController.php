<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.student', 'invoice.class']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->method) {
            $query->where('method', $request->method);
        }

        if ($request->from_date) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $query->orderByDesc('id')->paginate(15);

        return view('backend.payments.index', compact('payments'));
    }

    public function create()
    {
        $invoices = Invoice::with('student')
            ->whereIn('status', ['issued', 'partial', 'unpaid'])
            ->where('due_amount', '>', 0)
            ->orderByDesc('id')
            ->get();

        return view('backend.payments.create', compact('invoices'));
    }

    public function getInvoice(Request $request)
    {
        $invoice = Invoice::with(['student', 'class', 'items.feeType', 'payments'])
            ->where('id', $request->invoice_id)
            ->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json($invoice);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:cash,bkash,nagad,rocket,bank,card,other',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);

        if ($request->amount > $invoice->due_amount) {
            return back()->with('error', 'Payment amount cannot exceed due amount (' . $invoice->due_amount . ')')->withInput();
        }

        $paymentNumber = $this->generatePaymentNumber();

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'payment_number' => $paymentNumber,
                'amount' => $request->amount,
                'method' => $request->method,
                'transaction_id' => $request->transaction_id,
                'gateway' => $request->gateway ?? null,
                'status' => 'success',
                'payment_date' => $request->payment_date,
                'notes' => $request->notes,
            ]);

            $invoice->paid_amount += $request->amount;
            $invoice->due_amount -= $request->amount;

            if ($invoice->due_amount <= 0) {
                $invoice->status = 'paid';
                $invoice->due_amount = 0;
            } else {
                $invoice->status = 'partial';
            }

            $invoice->save();

            DB::commit();
            return redirect()->route('payments.show', $payment)->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice.student', 'invoice.class', 'invoice.academicYear']);
        return view('backend.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;

        DB::beginTransaction();
        try {
            $payment->update(['status' => 'refunded']);

            $invoice->paid_amount -= $payment->amount;
            $invoice->due_amount += $payment->amount;

            if ($invoice->due_amount <= 0) {
                $invoice->status = 'paid';
                $invoice->due_amount = 0;
            } else {
                $invoice->status = 'partial';
            }

            $invoice->save();

            DB::commit();
            return redirect()->route('payments.index')->with('success', 'Payment refunded successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to refund payment: ' . $e->getMessage());
        }
    }

    private function generatePaymentNumber()
    {
        $year = date('Y');
        $prefix = "PAY-{$year}-";

        $lastPayment = Payment::where('payment_number', 'like', "{$prefix}%")
            ->orderByDesc('payment_number')
            ->first();

        if ($lastPayment) {
            $lastNumber = (int) str_replace($prefix, '', $lastPayment->payment_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
