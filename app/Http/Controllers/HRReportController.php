<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRReportController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show employee summary report.
     */
    public function employeeSummary()
    {
        $user = Auth::user();

        $totalEmployees = Employee::where('company_id', $user->company_id)->count();
        $activeEmployees = Employee::where('company_id', $user->company_id)
            ->where('is_active', true)->count();
        $inactiveEmployees = $totalEmployees - $activeEmployees;

        $employees = Employee::where('company_id', $user->company_id)
            ->with('department', 'position')
            ->get();

        return view('reports.employee_summary', compact(
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'employees'
        ));
    }

    /**
     * Show attendance report.
     */
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->start_date ? now()->parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? now()->parse($request->end_date) : now()->endOfMonth();

        $attendance = Attendance::where('company_id', $user->company_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('employee')
            ->get();

        return view('reports.attendance', compact('attendance', 'startDate', 'endDate'));
    }

    /**
     * Show payroll summary report.
     */
    public function payrollSummary(Request $request)
    {
        $user = Auth::user();
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $payrolls = Payroll::where('company_id', $user->company_id)
            ->whereYear('payroll_date', $year)
            ->whereMonth('payroll_date', $month)
            ->with('employee')
            ->get();

        $totalSalary = $payrolls->sum('salary');
        $totalAllowances = $payrolls->sum('total_allowances');
        $totalDeductions = $payrolls->sum('total_deductions');
        $totalNetSalary = $payrolls->sum('net_salary');

        return view('reports.payroll', compact(
            'payrolls',
            'totalSalary',
            'totalAllowances',
            'totalDeductions',
            'totalNetSalary',
            'year',
            'month'
        ));
    }
}
