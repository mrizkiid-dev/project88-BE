<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        Log::info(['user role = ', $request->user()->role->pluck('role_name')]);
        if($request->user()->role->pluck('role_name')->contains(RoleEnum::ADMIN->value)) {
            return $next($request);
        }

        $ErrorResponse = [
            'message' =>'Invalid Token',
        ];
        abort(response()->json($ErrorResponse, 403));
    }
}
