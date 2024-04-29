<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna adalah seorang admin
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }
    
        // Jika bukan admin, kembalikan respons dengan status 403 (Forbidden)
        return response()->json([
            'error' => true,
            'message' => 'Unauthorized access. Only admins can perform this action.',
        ], 403);
    }
}
