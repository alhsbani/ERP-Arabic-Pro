<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show sales report.
     */
    public function salesReport(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->start_date ? now()->parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? now()->parse($request->end_date) : now()->endOfMonth();

        $sales = Invoice::where('company_id', $user->company_id)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with('customer', 'items')
            ->get();

        $totalSales = $sales->sum('total');
        $totalTax = $sales->sum('tax_amount');
        $totalPaid = $sales->sum('paid_amount');
        $totalPending = $totalSales - $totalPaid;

        return view('reports.sales', compact('sales', 'totalSales', 'totalTax', 'totalPaid', 'totalPending', 'startDate', 'endDate'));
    }

    /**
     * Show inventory report.
     */
    public function inventoryReport()
    {
        $user = Auth::user();
        $products = Product::where('company_id', $user->company_id)
            ->with('category')
            ->get();

        $totalValue = $products->sum(function ($product) {
            return $product->quantity * $product->cost;
        });

        return view('reports.inventory', compact('products', 'totalValue'));
    }

    /**
     * Show pending invoices report.
     */
    public function pendingInvoicesReport()
    {
        $user = Auth::user();
        $invoices = Invoice::where('company_id', $user->company_id)
            ->whereIn('status', ['sent', 'overdue'])
            ->with('customer')
            ->get();

        $totalPending = $invoices->sum('remaining_balance');
        $overdueCount = $invoices->filter(function ($invoice) {
            return $invoice->is_overdue;
        })->count();

        return view('reports.pending', compact('invoices', 'totalPending', 'overdueCount'));
    }
}
