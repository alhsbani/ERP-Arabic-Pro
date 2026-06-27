<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http
Request;
use Illuminate\Support\Facades\Auth;

class LogAuditActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->isMethod(['post', 'put', 'patch', 'delete'])) {
            $user = Auth::user();
            
            AuditLog::create([
                'user_id' => $user->id,
                'company_id' => $user->company_id,
                'action' => $request->method() . ' ' . $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
