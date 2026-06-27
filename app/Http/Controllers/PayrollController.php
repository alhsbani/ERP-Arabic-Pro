<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\PayrollAllowance;
use App\Models\PayrollDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of payroll records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $payrolls = Payroll::where('company_id', $user->company_id)
            ->with('employee')
            ->when($request->year && $request->month, function ($query) use ($request) {
                $query->whereYear('payroll_date', $request->year)
                    ->whereMonth('payroll_date', $request->month);
            })
            ->paginate(15);

        return view('payroll.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new payroll record.
     */
    public function create()
    {
        $user = Auth::user();
        $employees = Employee::where('company_id', $user->company_id)
            ->where('is_active', true)
            ->get();

        return view('payroll.create', compact('employees'));
    }

    /**
     * Store a newly created payroll record in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'payroll_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $employee = Employee::find($request->employee_id);
            $salary = $request->salary;
            $totalAllowances = 0;
            $totalDeductions = 0;

            $payroll = Payroll::create([
                'employee_id' => $request->employee_id,
                'company_id' => $user->company_id,
                'payroll_date' => $request->payroll_date,
                'salary' => $salary,
                'total_allowances' => 0,
                'total_deductions' => 0,
                'net_salary' => $salary,
                'status' => 'draft',
                'notes' => $request->notes,
            ]);

            // Calculate net salary
            $netSalary = $salary + $totalAllowances - $totalDeductions;
            $payroll->update([
                'total_allowances' => $totalAllowances,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
            ]);

            DB::commit();

            return redirect()->route('payroll.show', $payroll)->with('success', __('messages.payroll_created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('messages.error_creating_payroll'));
        }
    }

    /**
     * Display the specified payroll.
     */
    public function show(Payroll $payroll)
    {
        return view('payroll.show', compact('payroll'));
    }

    /**
     * Update payroll status.
     */
    public function updateStatus(Request $request, Payroll $payroll)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,approved,paid',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $payroll->update(['status' => $request->status]);

        return redirect()->back()->with('success', __('messages.payroll_updated'));
    }
}
