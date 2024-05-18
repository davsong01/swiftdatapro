<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(){
        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // if (\Request::route()->getPrefix() == 'api/customer') {

        // }

        // $credentials = $request->only('email', 'pass');
        $user = User::where('email', $request->email)->orWhere('username', $request->username)->first();
        
        if(isset($user) && empty($user->pass)){
            return redirect(route('password.reset.new', $user->id));
        }

        if (!$user || !Hash::check($request->password, $user->pass)) {
            return back()->with('error', 'Incorrect Username or Password');
        }else{
            Auth::login($user);

            $body = 'Dear ' . auth()->user()->full_name . '<br>Your login to ' . config('app.php') . ' at '.Carbon::now().' was successful<br>';
            $body .= '<br><br>Warm Regards<br>' . config('app.name');

            $subject = "Welcome Back!";
            $this->sendEmail(auth()->user()->email, $subject, $body);

            return redirect()->intended('dashboard');
        }

    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->orWhere('username', $request->username)->first();

        if (isset($user) && empty($user->pass)) {
            return redirect(route('password.reset.new', $user->id));
        }

        if (!$user || !Hash::check($request->password, $user->pass)) {
            return back()->with('error', 'Incorrect Username or Password');
        } else {
            Auth::login($user);

            $body = 'Dear ' . auth()->user()->full_name . '<br>Your login to ' . config('app.php') . ' at ' . Carbon::now() . ' was successful<br>';
            $body .= '<br><br>Warm Regards<br>' . config('app.name');

            $subject = "Welcome Back!";
            $this->sendEmail(auth()->user()->email, $subject, $body);

            return redirect()->intended('dashboard');
        }
    }

    public function resetNewPassword($user_id){
        $user = User::where('id', $user_id)->first();

        if(!$user){
            return redirect(route('login'));
        }
        if(!empty($user->pass)){
            return redirect(route('login'));
        }

        return view('auth.get_new_password', compact('user'));
    }

    public function processResetNewPassword(Request $request)
    {
        $web_settings = WebSetting::first();
        $id = base64_decode($request->idxse);
        
        $user = User::where('id', $id)->first();
        
        $request['email'] = $user->email;
       
        
        $check = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        
        if($check){
            $tk = $check->token;
        }else{
            $tk = Str::random(60);

            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $tk,
                'app_version' => '2',
                'created_at' => Carbon::now()
            ]); //Get the token just created above
        }
       
        $subject = "Your Password Recovery Link Has Arrived";
        $body = '<p>Hello! ' . $user->full_name . '</p>';
        $reset_link =  url('/') . "/reset-password?token=" . $tk;
        $body .= '<p style="line-height: 2.0;">Please click the button below to reset your password <a class="btn btn-info" target="_blank" href="' . $reset_link . '" style="position: relative;color: #fff;font-weight: 500;padding: 8px 20px;font-size: 13px;line-height: 24px;letter-spacing: 0.01em;border-radius: 4px;border: 1px solid;transition: all .4s ease;background:#950eb3;text-align: center;white-space: nowrap;vertical-align: middle;text-decoration:none">RESET PASSWORD</a><br/>If you did not request for Password Recovery, kindly notify us via whatsapp on ' . $web_settings->whatsapp_no . ' Immediately.<b><hr/><br>Warm Regards. (' . config('app.name') . ')<br/></p>';
        
        $this->sendEmail($user->email, $subject, $body);
        
        return back()->with('message', 'Please check your email for a password reset link!!!');
    }

    public function changePassword(Request $request){
       
       $user = DB::table('password_reset_tokens')->where('token', $request->token)->first();
      
       if(Auth::check()){
            Auth::logout();
       }
       
       if(!$user){
        return(redirect(route('login')));
       }

       return view('auth.enter_new_password')->with('idx', base64_encode(base64_encode($user->email)));
    }

    public function processChangePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
        ]);

        $email = base64_decode(base64_decode($request->email));        
        $token = DB::table('password_reset_tokens')->where('email', $email)->where('token', $request->token)->first();

        if (!$token) {
            return (redirect(route('login')));
        }

        $newPassword = Hash::make($request->password);

        $user = User::where('email', $email)->first();
        $user->update(['pass' => $newPassword]);

        $token = DB::table('password_reset_tokens')->where('email', $email)->where('token', $request->token)->delete();

        Auth::login($user);
        return redirect()->intended('dashboard');

    }
}