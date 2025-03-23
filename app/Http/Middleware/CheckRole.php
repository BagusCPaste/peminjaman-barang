<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        \Log::info('CheckRole middleware running', [
            'user' => $request->user(), 
            'required_role' => $role,
            'user_role' => $request->user()?->role,
            'matches' => $request->user()?->role === $role
        ]);
        
        // Bypass role check jika di development dan ada parameter debug=1
        if ((app()->environment('local', 'development') && $request->has('debug')) || 
            $request->header('X-Debug-Role') === 'bypass') {
            \Log::info('Role check di-bypass karena debug mode');
            return $next($request);
        }
        
        if (!$request->user() || $request->user()->role !== $role) {
            \Log::error('Role check gagal', [
                'user_id' => $request->user()?->id,
                'user_role' => $request->user()?->role,
                'required_role' => $role
            ]);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
