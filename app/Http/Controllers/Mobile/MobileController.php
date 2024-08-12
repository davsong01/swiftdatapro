<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;
use App\Models\Product;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\MobileResponseService;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Auth\RegisterController;

class MobileController extends Controller
{
    private $home;
    private $variation;
    private $transaction;
    private $register;

    public function __construct()
    {
        $this->home = new HomeController();
        $this->variation = new VariationController();
        $this->transaction = new TransactionsController();
        $this->register = new RegisterController();
        $this->login = new LoginController();
    }
    public function register(Request $request)
    {
        $settings = WebSetting::first();
        $allowedDomains = $settings->allowed_domain_emails_for_registration;
        $allowedDomains = !empty($allowedDomains) ? json_decode($allowedDomains, true) : [];

        $allowedDomainsString = implode('|', $allowedDomains);

        $validator = validator($request->all(), [
            'full_name' => ['required', 'string', 'max:15'],
            'referral' => ['sometimes', 'string', 'exists:users,ref_code', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'mobile' => ['required', 'string', 'max:11', 'min:11', 'unique:users'],
            'username' => ['required', 'string', 'max:30', 'unique:users'],
            'gender' => ['required', 'string', 'in:Male,Female', 'max:10'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users', 'regex:/^[A-Za-z0-9._%+-]+@(' . $allowedDomainsString . ')$/i'],
            'password' => ['required', 'string', 'min:5'],
        ]);

        if ($validator->fails())
            return MobileResponseService::json(
                message: 'Validation error',
                error: $validator->errors()->all(),
                status: 422
            );

        $create = $this->register->create($validator->valid());
        
        if(isset($create['status']) && $create['status'] == 'failed'){
            return MobileResponseService::json(
                message: $create['message'],
                error: $validator->errors()->all(),
                status: 422
            );
        }

        return MobileResponseService::json(
            message: 'Operation Successful',
            error: null,
            status: 200
        );

    }

    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails())
            return MobileResponseService::json(
                message: 'Validation error',
                error: $validator->errors()->all(),
                status: 422
            );

        if ($request->channel != 'mobile')
            return MobileResponseService::json(
                message: 'Invalid channel',
                error: null,
                status: 403
            );
        
        
        $create = $this->login->login($request);
    
        if (isset($create['status']) && $create['status'] == 'failed') {
            return MobileResponseService::json(
                message: $create['message'],
                error: $validator->errors()->all(),
                status: 422
            );
        }

        $user = Auth::user();
        $token = $user->createToken($request->channel)->plainTextToken;
        
        $homeController = new HomeController();
        $homeController->createReservedAccount($user);
        
        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $token,
            error: null,
            status: 200
        );
    }

    public function userDetails(){
        $data = [
            request()->user()->only([
                'full_name',
                'middle_name',
                'last_name',
                'gender',
                'username',
                'email',
                'wallets',
                'cashback',
                'mobile',
                'status',
                'ref_code',
                'product_blacklist'
            ]),
        ];

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function getCustomerLevels(){
        $levels = CustomerLevel::select(['id','name', 'order', 'upgrade_amount'])->orderBy('order', 'desc')->get();
        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $levels,
            error: null,
            status: 200
        );
    }

    public function automatedTransfer(){
        $data = $this->home->automatedTransfer();

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: [
                'active_gateways' => $data['active_gateways'] ?? null,
                'accounts' => $data['accounts'] ?? null,
            ],
            error: null,
            status: 200
        );
    }

    public function transactions(Request $request)
    {
        $data = $this->home->transactions($request);

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function getMenu(){
        $kyc_settings = !empty(settings()->kyc_settings) ? json_decode(settings()->kyc_settings, true) : [];  

        $menus = [
            'Dashboard' => 'Dashboard',
            'Deposit Money' => [
                'Automated Transfer' => 'Automated Transfer',
                'Manual Deposit/Transfer' => 'Manual Deposit/Transfer'
            ],
            'Buy Data Bundle' => [
                'name' => 'Buy Data Bundle',
                'slug' => 'data',
            ],
            'Buy Airtime VTU' => [
                'name' => 'Buy Airtime VTU',
                'slug' => 'airtime'
            ],
            'Buy Airtime SnS' => [
                'name' => 'Buy Airtime SnS',
                'slug' => 'airtime-sns'
            ],

            'Exchange Airtime' => [
                'name' => 'Exchange Airtime',
                'slug' => 'airtime2cash'
            ],


            'TV Subscription' => [
                'name' => 'TV Subscription',
                'slug' => 'cable'
            ],

            'Buy Electricity Bills' => [
                'name' => 'Buy Electricity Bills',
                'slug' => 'electricity'
            ],

            'Buy Result E-pins' => [
                'name' => 'Buy Result E-pins',
                'slug' => 'exam-epins'
            ],

            'My Transactions' => [
                'All Transactions' => 'All Transactions',
                'My Commissions' => 'My Commissions',
            ],

            'My Referrals' => [
                'My Downlines' => 'My Downlines',
                'Cashback Earn' => 'Cashback Earn',
            ],

            'Update Settings' => [
                'My Profile' => 'My Profile',
                'Change Password' => 'Change Password',
                'Generate API-KEY' => 'Generate API-KEY',
            ],
            'Logout' => 'Logout',
        ];

        if(!empty($kyc_settings['status']) && $kyc_settings['status'] == 'yes'){
            array_push($menus['Update Settings'], 'KYC Registration');
        }

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $menus,
            error: null,
            status: 200
        );
    }

    public function downlines(Request $request){
        $data = $this->home->downlines($request);

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function getCustomerVariations(Product $product){
        $data = $this->variation->getCustomerVariations($product);

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function verify(Request $request)
    {

        $data = $this->transaction->verify($request);
        dd($data);
        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function initialize($service){
        $data = $this->transaction->initialize($service);

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function getCustomerDiscount(Request $request)
    {
        $validator = validator($request->all(), [
            'amount' => ['required'],
            'product_id' => ['integer'],
            'quantity' => ['nullable'],
        ]);

        if ($validator->fails())
            return MobileResponseService::json(
                message: 'Validation error',
                error: $validator->errors()->all(),
                status: 422
            );

        $data = getProductDiscount($request->amount, $request->product_id, $request->quantity ?? 1);

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function getCustomerCharges(Request $request)
    {
        $validator = validator($request->all(), [
            'amount' => ['required'],
            'product_id' => ['integer'],
            'quantity' => ['nullable'],
        ]);

        if ($validator->fails())
            return MobileResponseService::json(
                message: 'Validation error',
                error: $validator->errors()->all(),
                status: 422
            );

        $data = getProductCharges($request->amount, $request->product_id, $request->quantity ?? 1);

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );

    }

    public function transactionStatus(Request $request){
        if (empty($request->transaction_id))
            return MobileResponseService::json(
                message: 'Validation error',
                error: ['The transaction id field is required'],
                status: 422
            );

        $data = $this->transaction->transactionStatus($request->transaction_id);

        return MobileResponseService::json(
            message: 'Operation Successful',
            data: $data,
            error: null,
            status: 200
        );
    }

    public function processBuy(Request $request){
        $validator = validator($request->all(), [
            'product_id' => ['integer','required'],
            'amount' => ['nullable'],
            'mobile_no' => ['nullable'],
            'security_pin' => ['required'],
            'variation_id' => ['nullable'],
        ]);

        if ($validator->fails())
            return MobileResponseService::json(
                message: 'Validation error',
                error: $validator->errors()->all(),
                status: 422
            );

        $request['network_module'] = $request->product_id;
        $request['dataplanloader'] = $request->variation_id;

        $data = $this->transaction->processBuy($request);
            
        if (!empty($data)) {
            return MobileResponseService::json(
                message: $data['message'] ?? 'Purchase error',
                data: $data,
                status: 200
            );
        }
    }

    public function processResetPin(Request $request)
    {
        $validator = validator($request->all(), [
            'old_pin' => ['required'],
        ]);

        if ($validator->fails())
            return MobileResponseService::json(
                message: 'Validation error',
                error: $validator->errors()->all(),
                status: 422
            );

        $request['network_module'] = $request->product_id;
        $request['dataplanloader'] = $request->variation_id;

        return $this->home->processResetPin($request);
            dd($data);
        if (!empty($data)) {
            return MobileResponseService::json(
                message: $data['message'] ?? 'Purchase error',
                data: $data,
                status: 200
            );
        }
    }
    
    
}
