<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MobileResponseService;
use Laravel\Sanctum\PersonalAccessToken;

class MobileMiddleware
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
        $token = $request->bearerToken();
        
        if (!$token) {
            return MobileResponseService::json(
                message: 'Token is required',
                status: 401
            );
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || !$accessToken->tokenable || $accessToken->tokenable->is_suspended) {
            return MobileResponseService::json(
                message: 'Unauthenticated',
                status: 401
            );
        }

        Auth::setUser($accessToken->tokenable);
        
        return $next($request);
    }
}
