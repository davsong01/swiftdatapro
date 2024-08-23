<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MobileResponseService;
use App\Http\Controllers\Auth\RegisterController;

class VerifyEmail
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
        if (!Auth::user()->email_verified_at) {
            $user = Auth::user();
            // Send verification mail automatically
            $register = new RegisterController();
            $register->sendRegistrationEmail($user);

            $email = $user->email;
            $link = '<br><br><a class="btn btn-success btn-sm" href="'.route('resend.verification.link', $email).'">Resend Link</a>';
            $message = 'Thank you for getting started with us. You need to confirm your account. We have sent you an activation link, please check your email for the link and click it. You can also check your spam folder incase you did not find our email in your inbox' . $link;
            
            auth()->logout();

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    message: $message,
                    data: ['link' => route('resend.verification.link', $email)],
                    status: 200
                );
            }

            return redirect(route('login'))
                ->with('message', $message)
                ->with('email',$email);
        }
        
        return $next($request);
    }
}
