<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Sales metrics
        $totalRevenue = Invoice::where('company_id', $user->company_id)
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('total');

        $totalInvoices = Invoice::where('company_id', $user->company_id)
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->count();

        $pendingInvoices = Invoice::where('company_id', $user->company_id)
            ->where('status', 'pending')
            ->count();

        // Customer metrics
        $totalCustomers = Customer::where('company_id', $user->company_id)->count();
        $activeCustomers = Customer::where('company_id', $user->company_id)
            ->where('is_active', true)->count();

        // Employee metrics
        $totalEmployees = Employee::where('company_id', $user->company_id)->count();
        $activeEmployees = Employee::where('company_id', $user->company_id)
            ->where('is_active', true)->count();

        // Payroll metrics
        $monthlyPayroll = Payroll::where('company_id', $user->company_id)
            ->whereMonth('payroll_date', now()->month)
            ->whereYear('payroll_date', now()->year)
            ->sum('net_salary');

        // Revenue trend (last 7 days)
        $revenueTrend = Invoice::where('company_id', $user->company_id)
            ->whereBetween('invoice_date', [
                now()->subDays(7),
                now(),
            ])
            ->selectRaw('DATE(invoice_date) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->get();

        // Recent invoices
        $recentInvoices = Invoice::where('company_id', $user->company_id)
            ->with('customer')
            ->latest('invoice_date')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalRevenue',
            'totalInvoices',
            'pendingInvoices',
            'totalCustomers',
            'activeCustomers',
            'totalEmployees',
            'activeEmployees',
            'monthlyPayroll',
            'revenueTrend',
            'recentInvoices'
        ));
    }
}
