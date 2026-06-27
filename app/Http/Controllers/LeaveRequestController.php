<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of leave requests.
     */
    public function index()
    {
        $user = Auth::user();
        $leaveRequests = LeaveRequest::where('company_id', $user->company_id)
            ->with('employee', 'leaveType')
            ->paginate(15);

        return view('leave.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        $user = Auth::user();
        $leaveTypes = LeaveType::where('company_id', $user->company_id)
            ->where('is_active', true)
            ->get();
        $employees = Employee::where('company_id', $user->company_id)
            ->where('is_active', true)
            ->get();

        return view('leave.create', compact('leaveTypes', 'employees'));
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        LeaveRequest::create(array_merge(
            $request->only('employee_id', 'leave_type_id', 'start_date', 'end_date', 'reason'),
            ['company_id' => $user->company_id, 'status' => 'pending']
        ));

        return redirect()->route('leave.index')->with('success', __('messages.leave_request_created'));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', __('messages.leave_request_approved'));
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', __('messages.leave_request_rejected'));
    }
}
