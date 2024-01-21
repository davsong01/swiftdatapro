<?php

namespace App\Http\Controllers\Auth;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        if (isset($request->referral)) {
            $myrefferal = $request->referral;
        } else {
            $myrefferal = "ADMIN";
        }

        return view('auth.register', compact('myrefferal'));
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'full_name' => ['required', 'string', 'max:255'],
            'referral' => ['required', 'string', 'exists:users,ref_code', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:11', 'min:11', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'gender' => ['required', 'string', 'in:Male,Female', 'max:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Get referral
        $myrefferal = User::where('ref_code', $data['referral'])->first();

        $dateTime = new DateTime('now', new DateTimeZone('Africa/Lagos'));
        $time = $dateTime->format("d-M-y  h:i A");
        $users_sid = base64_encode(uniqid() . time() . mt_rand(10000000, 90000000));
        $sk_token = base64_encode($data['email']) . uniqid();

        // Create user
        $user = User::create([
            'full_name' => $data['full_name'],
            'ref_code' => $data['username'],
            'ref_by' => $myrefferal->username,
            'mobile' => $data['mobile'],
            'username' => $data['username'],
            'pass' => Hash::make($data['password']),
            'password' => md5($data['password']),
            'wallets' => 0,
            'cashback' => 0,
            'block' => 0,
            'users_sid' => $users_sid,
            'referral' => '0',
            'level' => '1',
            'last_login' => $time,
            'email' => $data['email'],
            'sk_token' => $sk_token,

            'wema' => '',
            'acctname' => '',
            'rolez' => '',
            'sterling' => '',
            'security_pin' => '',
            'fidelity' => '',
            'reg_date' => $time,
            'status' => 0,

            'mtnsme_access' => 1,
            'mtncg_access' => 1,
            'glo_access' => 1,
            'airtel_access' => 1,
            'airtelcg_access' => 1,
            'mobile_access' => 1,
            'cable_access' => 1,
            'bills_access' => 1,
            'epin_access' => 1,
            'airtime_access' => 1,

            'susbscription' => 0,
            'webhook' => '',
            'rating' => '0',
            'reserved_account' => '',
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'app_version' => 'Version 2',
        ]);

        // Update referral
        $myrefferal->update(['referral' => $myrefferal->referral + 1]);

        // Send verification email
        $this->sendRegistrationEmail($user);
        // Send Admin Notification email
        $this->sendAdminNotificationEmail($user);

        UserVerify::create([
            'user_id' => $user->id,
            'token' => $user->users_sid
        ]);

        return $user;
    }

    public function resendEmailVerificationLink($email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->sendRegistrationEmail($user);

        return redirect()->route('login')
            ->with('message', 'We have re-sent your activation link, please check your email for the link');
    }

    public function sendRegistrationEmail($user)
    {
        $verify_link = url('/') . '/email/verify/' . $user->users_sid;
        $subject = "Welcome To " . config('app.name');
        $body = '<p>Hello! ' . $user->full_name . '</p>';
        $body .= '<p>Ready to take your business to the next level ?<br/> You can unlock special perks by just purchasing Data Bundles, AirtimeVTU, Cable Subscription, Billspayment e.t.c. on ' . config('app.name') . ' <br/><b>To have you fully on our platform, click the link below to activate your account</b><br/>' . $verify_link . '<br/><br/>You Are Welcome,<br/>Your Customer Support at ' . config('app.name') . '.</p>';

        $this->sendEmail($user->email, $subject, $body);
    }

    public function sendAdminNotificationEmail($user)
    {
        $subject = "New Registration";
        $body = 'Hello Admin, <br>New registration on ' . config('app.name') . '. Please find details below: <br><br>
          Full Name: ' . $user->full_name . ' ' . $user->middle_name . '' . $user->last_name . ' <br>
          Email: ' . $user->email . '<br>
          Phone Number: ' . $user->mobile . '<br>
          Registration Date: ' . $user->created_at . '<br>
          Referral : ' . $user->refferal . '
          ';

        $this->sendEmail($user->email, $subject, $body);
    }
}
