<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of invoices.
     */
    public function index()
    {
        $user = Auth::user();
        $invoices = Invoice::where('company_id', $user->company_id)
            ->with('customer')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $user = Auth::user();
        $customers = Customer::where('company_id', $user->company_id)->get();
        $products = Product::where('company_id', $user->company_id)->get();

        return view('invoices.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $discount = $request->discount_amount ?? 0;
            $taxableAmount = $subtotal - $discount;
            $taxAmount = ($taxableAmount * $request->tax_rate) / 100;
            $total = $taxableAmount + $taxAmount;

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber($user->company_id),
                'customer_id' => $request->customer_id,
                'company_id' => $user->company_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'tax_rate' => $request->tax_rate,
                'discount_amount' => $discount,
                'total' => $total,
                'paid_amount' => 0,
                'status' => 'draft',
                'notes' => $request->notes,
            ]);

            // Create invoice items
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice)->with('success', __('messages.invoice_created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('messages.error_creating_invoice'));
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Update invoice status.
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $invoice->update(['status' => $request->status]);

        return redirect()->back()->with('success', __('messages.invoice_updated'));
    }

    /**
     * Record a payment for the invoice.
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->remaining_balance,
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $invoice->payments()->create($request->only('amount', 'payment_method', 'payment_date', 'reference_number'));
        $invoice->update(['paid_amount' => $invoice->paid_amount + $request->amount]);

        if ($invoice->remaining_balance == 0) {
            $invoice->update(['status' => 'paid']);
        }

        return redirect()->back()->with('success', __('messages.payment_recorded'));
    }

    /**
     * Generate a unique invoice number.
     */
    private function generateInvoiceNumber($companyId)
    {
        $year = now()->year;
        $month = now()->month;
        $lastInvoice = Invoice::where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -4)) + 1 : 1;

        return sprintf('INV-%04d-%02d-%04d', $companyId, $month, $sequence);
    }
}
