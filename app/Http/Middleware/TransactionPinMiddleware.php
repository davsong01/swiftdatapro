<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\MobileResponseService;
use Symfony\Component\HttpFoundation\Response;

class TransactionPinMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if($user && empty($user->transaction_pin)){
            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    data: [
                        'error' => 'TRANSACTIONPINNOTSET',
                        'status_code' => 422,
                    ],
                    error: 'TRANSACTIONPINNOTSET',
                    status: 422
                );
            }

            return redirect(route('pin.reset','set'));
        }

        return $next($request);
    }
}
