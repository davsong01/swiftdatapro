<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\MobileResponseService;
use Symfony\Component\HttpFoundation\Response;

class KycMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if($user && $user->kyc_status == 0){
            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    data: [
                        'error' => 'KYCNOTFILLED',
                        'status_code' => 422,
                    ],
                    error: 'KYCNOTFILLED',
                    status: 422
                );
            }

            return redirect(route('kyc.form'));
        }
        return $next($request);
    }
}
