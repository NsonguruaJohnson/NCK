<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->role !== 'admin') {
            $info = [
                'status' => 'false',
                'message' => 'You must be an admin to access this resource'
            ];
            return response()->json($info, 400);
        }
        return $next($request);
    }
}
