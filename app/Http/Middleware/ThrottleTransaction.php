<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ThrottleTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(env('ENT') !== 'local'){
            $transaction_restriction_settings = settings()->transaction_restriction_settings;
            $throttleTime = $transaction_restriction_settings['trottle_transactions_time'] ?? 0;
            
            if($throttleTime > 0){
                $key = 'transaction-throttle-' . $request->user()->id . '-' . $request->route()->getName();
        
                if (Cache::has($key)) {
                    return response()->json([
                        'status' => 'fail',
                        'status_code' => '100',
                        'message' => 'Please wait before making another transaction.',
                    ]);
                }
        
                // Store the key in cache for the throttle duration
                Cache::put($key, true, $throttleTime);
            }
        }
        return $next($request);

    }
}
