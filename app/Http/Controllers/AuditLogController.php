<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display audit logs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $logs = AuditLog::where('company_id', $user->company_id)
            ->with('user')
            ->when($request->action, function ($query) use ($request) {
                $query->where('action', $request->action);
            })
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('audit.index', compact('logs'));
    }

    /**
     * Display audit log details.
     */
    public function show(AuditLog $log)
    {
        return view('audit.show', compact('log'));
    }
}
