<?php

namespace App\Http\Controllers\Auth;

use App\Models\WebSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    public function showForgotPasswordForm(){
        return view('auth.passwords.forgot_password');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|exists:users,email'
        ]);

        $web_settings = WebSetting::first();
        
        $check = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($check) {
            $tk = $check->token;
        } else {
            $tk = Str::random(60);

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $tk,
                'app_version' => '2',
                'created_at' => Carbon::now()
            ]); //Get the token just created above
        }

        $subject = "Your Password Recovery Link Has Arrived";
        $body = '<p>Hello! ' . $request->full_name . '</p>';
        $reset_link =  url('/') . "/reset-password?token=" . $tk;
        $body .= '<p style="line-height: 2.0;">Please click the button below to reset your password <a class="btn btn-info" target="_blank" href="' . $reset_link . '" style="position: relative;color: #fff;font-weight: 500;padding: 8px 20px;font-size: 13px;line-height: 24px;letter-spacing: 0.01em;border-radius: 4px;border: 1px solid;transition: all .4s ease;background:#950eb3;text-align: center;white-space: nowrap;vertical-align: middle;text-decoration:none">RESET PASSWORD</a><br/>If you did not request for Password Recovery, kindly notify us via whatsapp on ' . $web_settings->whatsapp_no . ' Immediately.<b><hr/><br>Warm Regards. (' . config('app.name') . ')<br/></p>';

        $this->sendEmail($request->email, $subject, $body);

        return back()->with('message', 'Please check your email for a password reset link!!!');
    }
}
