<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $attendances = Attendance::where('company_id', $user->company_id)
            ->with('employee')
            ->when($request->date, function ($query) use ($request) {
                $query->where('date', $request->date);
            })
            ->paginate(15);

        return view('attendance.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        $user = Auth::user();
        $employees = Employee::where('company_id', $user->company_id)
            ->where('is_active', true)
            ->get();

        return view('attendance.create', compact('employees'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'status' => 'required|in:present,absent,late,leave',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Attendance::create(array_merge(
            $request->only('employee_id', 'date', 'check_in_time', 'check_out_time', 'status', 'notes'),
            ['company_id' => $user->company_id]
        ));

        return redirect()->route('attendance.index')->with('success', __('messages.attendance_recorded'));
    }
}
