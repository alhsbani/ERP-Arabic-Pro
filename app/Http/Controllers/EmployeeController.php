<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of employees.
     */
    public function index()
    {
        $user = Auth::user();
        $employees = Employee::where('company_id', $user->company_id)
            ->with('department', 'position')
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $user = Auth::user();
        $departments = Department::where('company_id', $user->company_id)->get();
        $positions = Position::where('company_id', $user->company_id)->get();

        return view('employees.create', compact('departments', 'positions'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'employee_code' => 'required|string|unique:employees',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'national_id' => 'required|string|unique:employees',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'hire_date' => 'required|date',
            'employment_type' => 'required|in:full-time,part-time,contract',
            'salary' => 'required|numeric|min:0',
            'salary_currency' => 'required|string|max:3',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Employee::create(array_merge(
            $request->only(
                'first_name', 'last_name', 'employee_code', 'email', 'phone',
                'date_of_birth', 'national_id', 'gender', 'address', 'city',
                'country', 'department_id', 'position_id', 'hire_date',
                'employment_type', 'salary', 'salary_currency', 'bank_name', 'bank_account'
            ),
            ['company_id' => $user->company_id, 'is_active' => true]
        ));

        return redirect()->route('employees.index')->with('success', __('messages.employee_created'));
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $user = Auth::user();
        $departments = Department::where('company_id', $user->company_id)->get();
        $positions = Position::where('company_id', $user->company_id)->get();

        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'employee_code' => 'required|string|unique:employees,employee_code,' . $employee->id,
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'national_id' => 'required|string|unique:employees,national_id,' . $employee->id,
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'hire_date' => 'required|date',
            'employment_type' => 'required|in:full-time,part-time,contract',
            'salary' => 'required|numeric|min:0',
            'salary_currency' => 'required|string|max:3',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $employee->update($request->only(
            'first_name', 'last_name', 'employee_code', 'email', 'phone',
            'date_of_birth', 'national_id', 'gender', 'address', 'city',
            'country', 'department_id', 'position_id', 'hire_date',
            'employment_type', 'salary', 'salary_currency', 'bank_name', 'bank_account', 'is_active'
        ));

        return redirect()->route('employees.index')->with('success', __('messages.employee_updated'));
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', __('messages.employee_deleted'));
    }
}
