<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\UserVerify;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('verifyAccount');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();
        $message = 'Sorry your email cannot be identified.';
        
        if (!empty($verifyUser)) {
            Auth::login($verifyUser->user);
            $user = $verifyUser->user;
            
            if (empty($user->email_verified_at)) {
                $verifyUser->user->email_verified_at = Carbon::now();
                $verifyUser->user->status = 'active';
                $verifyUser->user->save();
                $message = "Welcome, Your e-mail is verified.";
            } else {
                $message = "Welcome, Your e-mail is already verified.";
            }
            $verifyUser->delete();
            return redirect()->route('dashboard')->with('message', $message);
        }else{
            return back()->with('error', 'Email could not be vrified, Please try again');
        }

    }
}
