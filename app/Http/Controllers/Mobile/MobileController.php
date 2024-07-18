<?php

namespace App\Http\Controllers\Mobile;

use LDAP\Result;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Services\MobileResponseService;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VariationController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\TransactionsController;

class MobileController extends Controller
{
    private $home;
    private $variation;
    private $transaction;

    public function __construct()
    {
        $this->home = new HomeController();
        $this->variation = new VariationController();
        $this->transaction = new TransactionsController();
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
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

        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
        
        if (!$user) {
            return MobileResponseService::json(
                message: 'Incorrect Username or Password',
                error: $validator->errors()->all(),
                status: 401
            );
        }
        
        if ($user->status == 0 || $user->status == 'inactive') {
            return MobileResponseService::json(message: 'This account has been deactivated', status: 401);
        }
        
        if(empty($request->password)){
            if (md5($request->password) == $user->password) {
                $user->update([
                    'pass' => Hash::make($request->password),
                ]);
            } else {
                return MobileResponseService::json(message: 'Incorrect Username or Password', status: 401);
            }
        }
        
        if (!$user || !Hash::check($request->password, $user->pass)) {
            return MobileResponseService::json(message: 'Incorrect Username or Password', status: 401);
        } else {
            if ($request->channel != 'mobile')
                return MobileResponseService::json(
                    message: 'Invalid channel',
                    error: null,
                    status: 403
                );

            $token = $user->createToken($request->channel)->plainTextToken;
            $user->update([
                'last_login' => now(),
            ]);

            $homeController = new HomeController();
            $homeController->createReservedAccount($user);

            return MobileResponseService::json(
                message: 'Operation Successful',
                data: $token,
                error: null,
                status: 200
            );
        }
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
