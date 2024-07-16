<?php

namespace App\Http\Controllers\Auth;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\UserVerify;
use App\Models\WebSetting;
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
        $disable_registration = WebSetting::value('disable_registration');
        
        if($disable_registration == 'yes'){
            return redirect(route('welcome'));
        }

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
        $settings = WebSetting::first();
        $allowedDomains = $settings->allowed_domain_emails_for_registration;
        $allowedDomains = !empty($allowedDomains) ? json_decode($allowedDomains, true) : [];
        
        $allowedDomainsString = implode('|', $allowedDomains);

        return Validator::make($data, [
            'full_name' => ['required', 'string', 'max:15'],
            'referral' => ['required', 'string', 'exists:users,ref_code', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'mobile' => ['required', 'string', 'max:11', 'min:11', 'unique:users'],
            'username' => ['required', 'string', 'max:30', 'unique:users'],
            'gender' => ['required', 'string', 'in:Male,Female', 'max:10'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users', 'regex:/^[A-Za-z0-9._%+-]+@(' . $allowedDomainsString . ')$/i'],
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
        $settings = WebSetting::select('disable_registration', 'admin_notification_email')->first();
        $disable_registration = $settings->disable_registration;
        
        if ($disable_registration == 'yes') {
            return redirect(route('welcome'));
        }

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
            'password' => ' ',
            'wallets' => 0,
            'cashback' => 0,
            // 'block' => 0,
            'users_sid' => $users_sid,
            'referral' => '',
            'level' => 1,
            'last_login' => $time,
            'email' => $data['email'],
            // 'sk_token' => $sk_token,
            'security_pin' => '',
            // 'fidelity' => '',
            // 'reg_date' => $time,
            'status' => 'active',
            'webhook' => '',
            'rating' => '0',
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            // 'app_version' => '2',
        ]);

        // Update referral
        $myrefferal->update(['referral' => $myrefferal->referral + 1]);

        // Send verification email
        $this->sendRegistrationEmail($user);
        // Send Admin Notification email
        $this->sendAdminNotificationEmail($user,$settings);

        // UserVerify::create([
        //     'user_id' => $user->id,
        //     'token' => $user->users_sid
        // ]);

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

    public function sendAdminNotificationEmail($user,$settings=null)
    {

        $subject = "New Registration";
        $body = 'Hello Admin, <br>New registration on ' . config('app.name') . '. Please find details below: <br><br>
          Full Name: ' . $user->full_name . ' ' . $user->middle_name . '' . $user->last_name . ' <br>
          Email: ' . $user->email . '<br>
          Phone Number: ' . $user->mobile . '<br>
          Registration Date: ' . $user->created_at . '<br>
          Referral : ' . $user->refferal . '
          ';

        if(!empty($settings)){
            $this->sendEmail($settings->admin_notification_email, $subject, $body);
        }
    }
}
