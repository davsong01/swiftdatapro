<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\Product;
use App\Models\WebSetting;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Models\PasswordReset;
use App\Models\CustomerReview;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use App\Models\AutomatedDeposit;
use App\Models\DepositTransaction;
use App\Models\EarnedTransactions;
use Illuminate\Support\Facades\DB;
use App\Models\VtuappManualFunding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\JsonResponseService;
use App\Services\MobileResponseService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\PaymentGatewayController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function kycForm(){
        $customer_levels = CustomerLevel::where('id', '>', auth()->user()->level)->get();
        $kyc_status = auth()->user()->kyc_status;
        // return view('customer.kyc_form', compact('customer_levels'));
        return view('customer.kyc_form', compact('kyc_status'));
    }

    public function kycUpdate(Request $request){

        auth()->user()->update([
            'nin' => $request->nin,
            'bvn' => $request->bvn,
        ]);

        if(!empty($request->bvn) || !empty($request->nin)){
            auth()->user()->update([
                'kyc_status' => 1,
            ]);
        }

        if (\Request::is('mobile/*')) {
            return MobileResponseService::json(
                message: 'Operation successful',
                data: [],
                status: 200
            );
        }

        return redirect(route('dashboard'))->with('message', 'Operation successful');
    }

    public function dashboard()
    {
        $this->createReservedAccount(auth()->user());
        $customer_levels = CustomerLevel::where('id', '>', auth()->user()->level)->get();

        return view('customer.dashboard', compact('customer_levels'));
    }

    public function customerReview(Request $request, JsonResponseService $response){
        $setting = WebSetting::first();
        CustomerReview::create([
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'comment' => $request->rate_comment,
            'date' => Carbon::now(),
        ]);

        $body = auth()->user()->email . " Sent You A Review (" . $request->rate_comment . ')<br/><br/>';
        $body .= '<p>Kindly attend to this review.' . '<br/></p>';
        $subject = "New Review Has Arrived";

        $this->sendEmail($setting->admin_email, $subject,$body);

        $responseX = [
            'status_code' => 200,
            'status' => 'success',
            'message' => 'Thank you for your review. We always happy to hear from you !!!',
        ];
        return json_encode($responseX);
    }

    public function upgradeLevel(Request $request, JsonResponseService $response){
        $validator = Validator::make($request->all(), [
            'level' => [
                'required',
                'exists:customer_levels,id',
                function ($attribute, $value, $fail) {
                    if ($value <= auth()->user()->level) {
                        $fail('The ' . $attribute . ' must be greater than current level.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => $validator->messages()->first(),
            ];

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: $validator->messages()->first(),
                    status: 422
                );
            }
            
            return json_encode($responseX);
        }

        $level = CustomerLevel::where('id', $request->level)->first();
        $reference = $this->generateRequestId('SWFTUPGRD');

        $dateTime = new DateTime('now', new DateTimeZone('Africa/Lagos'));
        $timestring = time();

        $refb = $level->upgrade_amount - $level->admin_inch;
        $admb = $level->admin_inch;
        $amount = $level->upgrade_amount;

        $adinfo = User::where('username', 'ADMIN')->first();
        $refinfo = auth()->user();
        $balanceBefore = walletBalance(auth()->user());
        $package = $level->name;
        
        if ($balanceBefore >= $amount && $amount > 0) {
            // Update user wallet and balance
            $newbal = $balanceBefore - $amount;
            
            $descr1 = 'Activation of level ' . $level->name . ' was successful';
            $descr2 = 'Referral bonus of N' . $refb . ' landed from ' . auth()->user()->username;
            $descr3 = 'Admin bonus of N' . $admb . ' landed from ' . auth()->user()->username;
            $upline = $refinfo->ref_by;
            
            if ($upline !== 'ADMIN') {
                // Credit referral
                $uplineDetails = User::where('ref_code', $upline)->orwhere('username', $upline)->first();
                
                if ($uplineDetails) {
                    // Update cash back balance
                    $uplineDetails->update([
                        'cashback' => $uplineDetails->cashback + $refb,
                    ]);

                    // Update referral earning transactions
                    EarnedTransactions::create([
                        'user_id' => $uplineDetails->id,
                        'ref_code' => $refinfo->ref_code,
                        'amount' => $refb,
                        'type' => 'credit',
                        'sys_reference' => $reference,
                        'description' => $descr2,
                        'created_at' => Carbon::now(),
                    ]);
                    
                    /****
                     * Update bonus to admin
                     */
                    // Update cash back balance for balance
                    $adinfo->update([
                        'cashback' => $adinfo->cashback + $admb,
                    ]);

                    // Update earninigs for admin
                    EarnedTransactions::create([
                        'user_id' => $adinfo->id,
                        'ref_code' => $refinfo->ref_code,
                        'amount' => $refb,
                        'type' => 'credit',
                        'sys_reference' => $reference,
                        'description' => $descr3,
                        'created_at' => Carbon::now(),
                    ]);
                    
                    $data = [
                        'mobile_number' => auth()->user()->mobile,
                        'buyer_email' => auth()->user()->email,
                        'user_id' => auth()->user()->id,
                        'buyer_service' => 'UPGRADE',
                        'buyer_amount' => $amount,
                        'buyer_prebal' => $balanceBefore,
                        'buyer_postbal' => $newbal,
                        'buyer_descr' => $descr1,
                        'buyer_status' => 'SUCCESSFUL',
                        'type' => 'debit',
                        'charge_from' => 'WALLET',
                        'user_type' => $package,
                        'sys_ref' => $reference,
                        'api_ref' => $reference,
                        'api_report' => 'GOOD',
                        'src_price' => '0',
                        'profit' => '0',
                        'databyte' => '0',
                        'billersCode' => auth()->user()->ussername,
                        'unit_price' => $amount,
                    ];

                    if (\Request::is('mobile/*')) {
                        $data['channel'] = 'mobile';
                    }

                    $transaction = $this->logTransaction($data);
                    auth()->user()->wallets = $transaction->buyer_postbal;
                    auth()->user()->save();

                    $responseX = [
                        'status_code' => 200,
                        'status' => 'success',
                        'message' => $descr1,
                    ];

                    if (\Request::is('mobile/*')) {
                        return MobileResponseService::json(
                            message: $descr1,
                            data: $responseX,
                            status: 200
                        );
                    }

                    return json_encode($responseX);
        
                }
            } else {
                // credit admin only with full money
                // Update final transactions
                $new_ad_bonus = $adinfo->wallet + $amount;
                $descr3 = 'Admin bonus of N' . $amount . ' landed from ' . auth()->user()->username;

                // Update earnings
                $earned = EarnedTransactions::create([
                    'user_id' => $adinfo->id,
                    'ref_code' => $refinfo->ref_code,
                    'amount' => $amount,
                    'type' => 'credit',
                    'sys_reference' => $reference,
                    'description' => $descr3,
                    'created_at' => Carbon::now(),
                ]);
            
                // Update cash back balance for balance
                $adinfo->update([
                    'cashback' => $adinfo->cashback + $amount,
                ]);

                // User upgrade transaction history
                $data = [
                    'mobile_number' => auth()->user()->mobile, 
                    'buyer_email' => auth()->user()->email,
                    'user_id' => auth()->user()->id, 
                    'buyer_service' => 'UPGRADE', 
                    'buyer_amount' => $amount, 
                    'buyer_prebal' => $balanceBefore, 
                    'buyer_postbal' => $newbal, 
                    'buyer_descr' => $descr1, 
                    'buyer_status' => 'SUCCESSFUL', 
                    'charge_from' => 'WALLET', 
                    'user_type' => $package, 
                    'sys_ref' => $reference, 
                    'api_ref' => $reference, 
                    'api_report' => 'GOOD',
                    'type' => 'debit',
                    'src_price' => '0', 
                    'profit' => '0',
                    'databyte' => '0',
                    'type' => 'debit',
                    'billersCode' => auth()->user()->ussername,
                    'unit_price' => $amount,
                ];

                $this->logTransaction($data);
                $responseX = [
                    'status_code' => 200,
                    'status' => 'success',
                    'message' => $descr1,
                ];

                if (\Request::is('mobile/*')) {
                    return MobileResponseService::json(
                        message: $descr1,
                        data: $responseX,
                        status: 200
                    );
                }

                return json_encode($responseX);

            }

            auth()->user()->level = $level->id;
            auth()->user()->save();
            
        }else{
            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => 'You do not have enough balance to upgrade!!!',
            ];

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: 'You do not have enough balance to upgrade!!!',
                    status: 422
                );
            }

            return json_encode($responseX);
        }
    }

    public function withdrawCashbackEarned(JsonResponseService $response){
        $user = auth()->user();
        $referral_balance = referralBalance($user);
        $wallet_balance = walletBalance($user);

        if ($wallet_balance < 1) {
            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => 'You do not have commission to withdraw',
            ];

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: 'You do not have commission to withdraw',
                    status: 422
                );
            }

            return json_encode($responseX);
        }

        $amount = $referral_balance;
        $reference = $this->generateRequestId('SWFTCBW');
        $descr = 'Withdrawal of '.settings()->currency.number_format($amount) .' from Commissions to Wallet was successful';
        // Debit Earned transactions
        try {
            \DB::beginTransaction();
            $wallet_balance = walletBalance($user);

            EarnedTransactions::create([
                'user_id' =>  $user->id,
                'ref_code' => $user->ref_code,
                'amount' => $amount,
                'type' => 'debit',
                'sys_reference' => $reference,
                'description' => $descr,
            ]);

            // Log transaction
            $data['mobile_number'] = $user->mobile;
            $data['buyer_email'] = $user->email;
            $data['unit_price'] = $amount;
            $data['buyer_amount'] = $amount;
            $data['buyer_prebal'] = $wallet_balance;
            $data['buyer_postbal'] = $wallet_balance + $amount;
            $data['buyer_descr'] = $descr;
            $data['buyer_status'] = 'SUCCESSFUL';
            $data['buyer_service'] = 'COMMISSION WITHDRAWAL';
            $data['charge_from'] = 'COMMISSIONS-WALLET';
            $data['user_type'] = $user->level;
            $data['sys_ref'] = $reference;
            $data['api_ref'] = $reference;
            $data['api_report'] = 'DONE';
            $data['buyer_quantity'] = 1;
            $data['discount'] = 0;
            $data['src_price'] = 0;
            $data['channel'] = 'website';
            $data['provider'] = null;
            $data['billersCode'] = 'COMMISSIONS-WALLET';
            $data['profit'] = 0;
            $data['type'] = 'credit';
            $data['user_id'] = $user->id;

            $this->logTransaction($data);
            // Update Customer cashback
            $user->update([
                'cashback' => $referral_balance - $amount,
                'wallets' => $wallet_balance + $amount,
            ]);

            \DB::commit();
            $responseX = [
                'status_code' => 200,
                'status' => 'success',
                'message' => $descr,
            ];

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    message: $descr,
                    data: $responseX,
                    status: 200
                );
            }

            return json_encode($responseX);
            //code...
        } catch (\Throwable $th) {
            \DB::rollback();
            \Log::info(['Withdraw from Commissions error' => $th->getMessage().' Line: '. $th->getLine(). ' File: '.$th->getFile()]);
            $responseX = [
                'status_code' => 100,
                'status' => 'failed',
                'message' => 'An error occured while processing withdrawal, please try again',
            ];

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: 'An error occured while processing withdrawal, please try again',
                    status: 503
                );
            }

            return json_encode($responseX);
        }
    }

    public function createReservedAccount($user){
        
        if($user->kyc_status != 1){
            return;
        }
        
        $payvesel = PaymentGateway::where('slug', 'payvessel')->first();
        $monnify = PaymentGateway::where('slug', 'monnify')->first();
        
        $data = [
            'accountName' => $user->full_name,
            'accountReference' => 'SWIFT-' . uniqid() . rand(1000, 9000) . time(),
            'currencyCode' => "NGN",
            'customerEmail' => $user->email,
            'customerName' => $user->full_name,
            "getAllAvailableBanks" => false,
            "preferredBanks" => ["035", "232", "50515", "058"]
        ];

        if(!empty($user->bvn)){
            $data['bvn'] =  $user->bvn;
        }

        if (!empty($user->nin)) {
            $data['nin'] =  $user->nin;
        }
        
        if ($payvesel->status == 'active' && $user->filter_accounts($payvesel->id)->count() < 1) {
            $account = new PaymentGatewayController();
            $accounts = $account->createPayVesselReservedAccount($payvesel, $data);

            if (!empty($accounts) && $accounts['status'] == 'success') {
                foreach ($accounts['accounts'] as $account) {
                    AutomatedDeposit::create([
                        'account_reference' => $account['trackingReference'] ?? $accounts['response']['responseBody']['accountReference'] ?? null,
                        'account_number' => $account['accountNumber'] ?? null,
                        'account_name' => $account['accountName'] ?? null,
                        'bank_name' => $account['bankName'] ?? null,
                        'bank_code' => $account['bankCode'] ?? null,
                        'payment_gateway_id' => $accounts['payment_gateway_id'] ?? null,
                        'status' => 'active',
                        'bvn' => $data['bvn'] ?? null,
                        'nin' => $data['nin'] ?? null,
                        'raw_response' => json_encode($accounts['response']),
                        'user_id' => $user->id,
                    ]);
                }
                return 'success';
            }
        }

        if ($monnify->status == 'active' && $user->filter_accounts($monnify->id)->count() < 1) {
            $account = new PaymentGatewayController();
            $accounts = $account->createMonnifyReservedAccount($monnify, $data);
            
            if (!empty($accounts) && $accounts['status'] == 'success') {
                
                foreach ($accounts['accounts'] as $account) {
                    AutomatedDeposit::create([
                        'account_reference' => $account['trackingReference'] ?? $accounts['response']['responseBody']['accountReference'] ?? null,
                        'account_number' => $account['accountNumber'] ?? null,
                        'account_name' => $account['accountName'] ?? null,
                        'bank_name' => $account['bankName'] ?? null,
                        'bank_code' => $account['bankCode'] ?? null,
                        'payment_gateway_id' => $monnify->id,
                        'status' => 'active',
                        'bvn' => $data['bvn'] ?? null,
                        'nin' => $data['nin'] ?? null,
                        'raw_response' => json_encode($accounts['response']),
                        'user_id' => $user->id,
                    ]);
                }
                return 'success';
            }
        }
    }

    public function automatedTransfer(){
        $activeGateways = PaymentGateway::where('status', 'active')->pluck('id');
        $accounts = AutomatedDeposit::where(['user_id' => auth()->user()->id, 'status' => 'active'])->whereIn('payment_gateway_id', $activeGateways)->get();
        
        if(\Request::is('mobile/*')){
            return [
                'active_gateways' => $activeGateways,
                'accounts' => $accounts,
            ];
        }

        return view('customer.automated_pay', compact('accounts'));
    }

    public function depositPayment(){
        $list_acctno = VtuappManualFunding::all();
        return view('customer.deposit_payment', compact('list_acctno'));
    }

    public function transactions(Request $request){
        $transactions = Transactions::select(['id','product_id','variation_id', 'category_id', 'user_id', 'buyer_status', 'buyer_service', 'type','buyer_amount', 'unit_price', 'charges', 'discount','buyer_quantity', 'channel', 'buyer_prebal', 'buyer_postbal', 'buyer_descr', 'sys_ref', 'api_ref', 'billersCode', 'extra', 'created_at'])->with(['product', 'variation'])->where('user_id', auth()->user()->id)->where('buyer_status', '!=', 'initiated');
        
        if (!empty($request->service)) {
            $transactions = $transactions->where('product_id', $request->service);
        }

        if (!empty($request->transaction_id)) {
            $transactions = $transactions->where('sys_ref', $request->transaction_id);
        }

        if (!empty($request->status)) {
            $transactions = $transactions->where('buyer_status', $request->status);
        }

        if (!empty($request->billersCode)) {
            $transactions = $transactions->where('billersCode', 'LIKE', "%" . $request->billersCode . "%");
        }

        if (!empty($request->type)) {
            $transactions = $transactions->where('type', $request->type);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $from = $request->from . " 00:00:00";
            $to = $request->to . " 23:59:59";
            $transactions = $transactions->whereBetween('created_at', [$from, $to]);
        }

        $transactions = $transactions->orderBy('created_at', 'DESC')->paginate(50);

        $products = Product::select('id', 'category_id','type', 'display_name')->where('status', 'active')->get();

        if (\Request::is('mobile/*')) {
            return [
                'products' => $products,
                'transactions' => $transactions,
            ];
        }
        return view('customer.transactions', compact('products','transactions'));
    }

    public function cashbackEarned(Request $request)
    {
        $transactions = EarnedTransactions::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC');

        if (!empty($request->sys_reference)) {
            $transactions = $transactions->where('sys_reference', $request->sys_reference);
        }

        if (!empty($request->type)) {
            $transactions = $transactions->where('type', $request->type);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $from = $request->from . " 00:00:00";
            $to = $request->to . " 23:59:59";
            $transactions = $transactions->whereBetween('created_at', [$from, $to]);
        }

        $transactions = $transactions->orderBy('created_at', 'DESC')->paginate(50);

        if (\Request::is('mobile/*')) {
            return $transactions;
        }

        return view('customer.cashback', compact('transactions'));
    }

    public function walletSummary(){
        $transaction = "";
        $lettr = DepositTransaction::where('user_username', auth()->user()->username)->orWhere('user_email', auth()->user()->email)->orderBy('id','desc')->get();
        
        if(empty($lettr)){
            $transaction = '<div class="alert" style="background-color: #fff;border-radius: 10px 5px;color:black">No Funding Record Yet !!!</div>';
        }else{
            foreach ($lettr as $l) {
                if ($l->user_status == "SUCCESSFUL") {
                    $actmode = "background-color: lightseagreen";
                } else {
                    $actmode = "background-color: palevioletred";
                }

                $transaction .= '<div class="alert" style="' . $actmode . ';color:#fff;font-size:10px;" id="' . $l->id . '" onclick="caller(this.id)">

                <p style="color:white;">' . $l->id . '</p><br/>
                <span>Refrence No. : ' . $l->sys_ref . '</span><br/>
                <span>Order: ' . $l->user_descr . '</span><br/>
                <span>Date: ' . $l->user_date . '</span><br/>
                <span>Amount: ₦' . $l->user_amount . '</span><br/>

                <span>Prev Bal:</span> <span style="cursor: pointer;">₦' . $l->user_prebal . '</span>

                <span style="float:right">Post Bal: ₦' . $l->user_postbal . '</span>
                    </p>
                        
                </div>';

            }
        }
    
        return view('customer.wallets', compact('transaction'));
    }

    public function salesAnalysis(Request $request, JsonResponseService $response){
        if(empty($request->start) || empty($request->end)){
            $analysis = $this->analizeSales();
        }else{
            $analysis = $this->analizeSales($request->start, $request->end);

        }

        if ($request->ajax()) {
            return response()->json($analysis);
        }

        return view('customer.sales_analysis', compact('analysis'));

    }

    public function analizeSales($start = null, $end = null){
        $start = empty($start) ? Carbon::today() : Carbon::parse($start);
        $end = empty($end) ? Carbon::now() : Carbon::parse($end);

        $transaction = "";

        // sme data
        $mtnsmedatabyte = $this->getSingleAnalysis('mtn-sme', $start, $end);
        $total_smedatacost = $mtnsmedatabyte['cost'];
        $total_smedata = $mtnsmedatabyte['data'];

        ////MTN-CG
        $mtncgdatabyte = $this->getSingleAnalysis('mtn-cg', $start, $end);
        $total_mtncgdatacost = $mtncgdatabyte['cost'];
        $total_mtncgdata = $mtncgdatabyte['data'];
        ///

        ////AIRTEL-CG
        $airtelcgdatabyte = $this->getSingleAnalysis('airtel-cg', $start, $end);
        $total_airtelcgdata = $airtelcgdatabyte['data'];
        $total_airtelcgdatacost = $airtelcgdatabyte['cost'];

        ////AIRTEL
        $airteldatabyte = $this->getSingleAnalysis('airtel', $start, $end);
        $total_airteldata = $airteldatabyte['data'];
        $total_airteldatacost = $airteldatabyte['cost'];
       
        ////GLO
        $glodatabyte = $this->getSingleAnalysis('glo', $start, $end);
        $total_glodata = $glodatabyte['data'];
        $total_glodatacost = $glodatabyte['cost'];
        
        ////9MOBILE
        $mobiledatabyte = $this->getSingleAnalysis('9mobile', $start, $end);
        $total_mobiledata = $mobiledatabyte['data'];
        $total_mobiledatacost = $mobiledatabyte['cost'];

        ////AIRTIME-VTU
        $vtudatabyte = $this->getSingleAnalysis('vtu', $start, $end, 'vtu');
        $total_vtucost = $vtudatabyte['cost'];

        ////AIRTIME-SNS
        $sns = $this->getSingleAnalysis('sns', $start, $end, 'sns');
        $total_snscost = $sns['cost'];

        ////BILLS
        $bill = $this->getSingleAnalysis('electric', $start, $end, 'electric');
    
        $total_billscost = $bill['cost'];
    
        ////CABLETV
        $seltdatabyte1 = $this->getSingleAnalysis('gotv', $start, $end, 'gotv')['cost'];
        $seltdatabyte2 = $this->getSingleAnalysis('dstv', $start, $end, 'dstv')['cost'];
        $seltdatabyte3 = $this->getSingleAnalysis('showmax', $start, $end, 'showmax')['cost'];
        $total_cablecost = $seltdatabyte1 + $seltdatabyte2 + $seltdatabyte3;

        ////E-PINS
        $seltdatabyte1 = $this->getSingleAnalysis('neco', $start, $end, 'neco')['cost'];
        $seltdatabyte2 = $this->getSingleAnalysis('waec', $start, $end, 'waec')['cost'];
        $seltdatabyte3 = $this->getSingleAnalysis('nabteb', $start, $end, 'nabteb')['cost'];
        $total_pincost = $seltdatabyte1 + $seltdatabyte2 + $seltdatabyte3;

        ////WITHDRAW
        $total_withdraw  = $this->getSingleAnalysis('WITHDRAWTOWALLET', $start, $end, 'WITHDRAWTOWALLET')['cost'];

        // GLO CG
        $seltdatabyte  = $this->getSingleAnalysis('glo-cg', $start, $end);
        
        $total_glo_cg = $seltdatabyte['data'];
        $total_glo_cg_cost = $seltdatabyte['cost'];

        // MTN DATA CARD
        $seltdatabyte  = $this->getSingleAnalysis('mtn_data_card', $start, $end);
        $total_mtn_data_card = $seltdatabyte['data'];
        $total_mtn_data_card_cost = $seltdatabyte['cost'];

        //9MOBILE SME
        $seltdatabyte  = $this->getSingleAnalysis('apimobile-sme', $start, $end);
        $total_mobile_sme_data = $seltdatabyte['data'];
        $total_mobile_sme_cost = $seltdatabyte['cost'];

        $transaction = '
        <div>
        MTN SME DATA => ' . $total_smedata . 'GB <span style="float: right;">N' . number_format($total_smedatacost, 2) . '</span>
        </div>
        <hr/>
        <div>
        MTN CG DATA => ' . $total_mtncgdata . 'GB <span style="float: right;">N' . number_format($total_mtncgdatacost, 2) . '</span>
        </div>
        <hr/>
        <div>
        MTN DATA CARD => ' . $total_mtn_data_card . 'GB <span style="float: right;">N' . number_format($total_mtn_data_card_cost, 2) . '</span>
        </div>
        <hr/>
        
        <div>
        AIRTEL GIFT. DATA => ' . $total_airteldata . 'GB <span style="float: right;">N' . number_format($total_airteldatacost, 2) . '</span>
        </div>
        <hr/>
        <div>
        AIRTEL CG DATA => ' . $total_airtelcgdata . 'GB <span style="float: right;">N' . number_format($total_airtelcgdatacost, 2) . '</span>
        </div>
        <hr/>
        <div>
        GLO GIFT. DATA => ' . $total_glodata . 'GB <span style="float: right;">N' . number_format($total_glodatacost, 2) . '</span>
        </div>
        <hr/>
        <div>
        GLO CG => ' . $total_glo_cg . 'GB <span style="float: right;">N' . number_format($total_glo_cg_cost, 2) . '</span>
        </div>
        <hr/>
        
        <div>
        9MOBILE GIFT. DATA => ' . $total_mobiledata . 'GB <span style="float: right;">N' . number_format($total_mobiledatacost, 2) . '</span>
        </div>
        <hr/>
        <div>
        9MOBILE SME => ' . $total_mobile_sme_data . 'GB <span style="float: right;">N' . number_format($total_mobile_sme_cost, 2) . '</span>
        </div>
        <hr/>
        <div>
        AIRTIME VTU => <span style="float: right;">N' . number_format($total_vtucost, 2) . '</span>
        </div>
        <hr/>
        <div>
        AIRTIME SNS => <span style="float: right;">N' . number_format($total_snscost, 2) . '</span>
        </div>
        <hr/>
        <div>
        BILLS PAYMENT => <span style="float: right;">N' . number_format($total_billscost, 2) . '</span>
        </div>
        <hr/>
        <div>
        CABLETV PAYMENT => <span style="float: right;">N' . number_format($total_cablecost, 2) . '</span>
        </div>
        <hr/>
        <div>
        E-PINS PURCHASE => <span style="float: right;">N' . number_format($total_pincost, 2) . '</span>
        </div>
        <hr/>
        <div>
        BONUS WITHDRAW => <span style="float: right;">N' . number_format($total_withdraw, 2) . '</span>
        </div>';
        
        return $transaction;
        
    }

    public function getSingleAnalysis($buyer_service, $start, $end, $like = null){
        
        if(!empty($like)){
            // if($like == 'electric'){
            //     $result = Transactions::where(['buyer_status' => 'SUCCESSFUL', 'buyer_email' => auth()->user()->email])
            //     ->where('buyer_service', 'LIKE', '%' . $like . '%')
            //     ->whereBetween('created_at', [$start, $end])
            //     ->get();
            // }
            $result = Transactions::where(['buyer_status' => 'SUCCESSFUL', 'buyer_email' => auth()->user()->email])
            ->where('buyer_service', 'LIKE','%'.$like.'%')
            ->whereBetween('created_at', [$start, $end])
            ->get();
        }else{
            $result = Transactions::where(['buyer_status' => 'SUCCESSFUL', 'buyer_service' => $buyer_service, 'buyer_email' => auth()->user()->email])->whereBetween('created_at', [$start, $end])->get();
        }

        try {
            //code...
            $total_cost = $result->sum('buyer_amount');
            $total_data = $result->sum('databyte') > 0 ? $result->sum('databyte') / 1000 : 0;
        } catch (\Throwable $th) {
            dd($th->getMessage(), $buyer_service);
        }
        
        return [
            'cost' => $total_cost ?? 0,
            'data' => $total_data ?? 0,
        ];
    }

    public function downlines(Request $request){

        $referral_code = strtoupper($request->referral_code);
        $amount = 0;
        $transaction = '<div class="col-md-12">';

        $lettr = User::select('id','ref_by','username','full_name','middle_name','gender', 'last_name', 'email', 'level')->where('ref_by', $referral_code)->orWhere('ref_by', auth()->user()->username)->orderBy('id', 'desc')->paginate(20);

        if (\Request::is('mobile/*')) {
            return $lettr;
        }

        return view('customer.downlines', compact('lettr'));

    }

    public function profile(){
        return view('customer.profile');
    }

    public function updateProfile(Request $request){
        auth()->user()->update([
            'full_name' => $request->full_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
        ]);

        if (\Request::is('mobile/*')) {
            return MobileResponseService::json(
                message: 'Update successful',
                data: [],
                status: 200
            );
        }

        return back()->with('message', 'Update successful');
    }

    public function passwordChange(){
        return view('customer.password_change');
    }

    public function processPasswordChange(Request $request, JsonResponseService $response){
        $web_settings = WebSetting::first();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
        ]);

        if ($validator->fails()) {
            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => $validator->messages()->first(),
            ];

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: $validator->messages()->first(),
                    status: 422
                );
            }
            
            return json_encode($responseX);
        }

        $request['email'] = auth()->user()->email;
        $credentials = request()->validate(['email' => 'required|email']);
        // Confirm password first
        $user = User::where('email', $request->email)->orWhere('username', $request->username)->first();

        if (!Hash::check($request->old_password, auth()->user()->pass)) {
            $response = array(
                'status' => 'fail',
                'message' => '<div class="alert alert-danger text-left">Incorrect password !!!</div>',
            );

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: 'Incorrect password !!!',
                    status: 422
                );
            }
            
            return json_encode($response);
        }

        $check = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        if ($check) {
            $tk = $check->token;
        } else {
            $tk = Str::random(60);

            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $tk,
                'created_at' => Carbon::now()
            ]); //Get the token just created above
        }

        $subject = "Your Password Recovery Link Has Arrived";
        $body = '<p>Hello! ' . auth()->user()->full_name . '</p>';

        if (\Request::is('mobile/*')) {
            $reset_link =  url('/') . "/reset-password?token=" . $tk;
        }else{
            $reset_link =  url('/') . "/reset-password?token=" . $tk;
        }

        $body .= '<p style="line-height: 2.0;">Please click the button below to reset your password <a class="btn btn-info" target="_blank" href="'. $reset_link. '" style="position: relative;color: #fff;font-weight: 500;padding: 8px 20px;font-size: 13px;line-height: 24px;letter-spacing: 0.01em;border-radius: 4px;border: 1px solid;transition: all .4s ease;background:#950eb3;text-align: center;white-space: nowrap;vertical-align: middle;text-decoration:none">RESET PASSWORD</a><br/>If you did not request for Password Recovery, kindly notify us via whatsapp on ' . $web_settings->whatsapp_no . ' Immediately.<b><hr/><br>Warm Regards. (' . config('app.name') . ')<br/></p>';

        $this->sendEmail($user->email, $subject, $body);

        $responseX = [
            'title' => 'COMPLETED',
            'icon' => 'success',
            'status' => 'success',
            'message' => 'Please check your email for a password reset link!!!',
        ];

        return MobileResponseService::json(
            message: 'Please check your email for a password reset link!!!',
            data: $responseX,
            status: 200
        );

        return json_encode($responseX);
    }

    public function resetPin($type=null){
        return view('customer.reset_pin', compact('type'));
    }

    public function processResetPin(Request $request, JsonResponseService $response = null){
        $web_settings = WebSetting::first();
        
        $validator = Validator::make($request->all(), [
            'old_pin' => 'required',
        ]);

        if ($validator->fails()) {

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: $validator->messages()->first(),
                    status: 422
                );
            }

            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => $validator->messages()->first(),
            ];

            return json_encode($responseX);

        }
        
        if (!Hash::check($request->old_pin, auth()->user()->pass)) {
            $response = array(
                'status' => 'fail',
                'message' => '<div class="alert alert-danger text-left">Incorrect password !!!</div>',
            );

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: 'Incorrect password !!!',
                    status: 403
                );
            }

            return json_encode($response);
        }

        $token = Str::random(60);
        $hashedToken = base64_encode(base64_encode($token.time()));
        $expiry = Carbon::now()->addMinute(20);

        auth()->user()->pin_reset_token = $hashedToken;
        auth()->user()->pin_exp_date = $expiry;
        auth()->user()->save();

        $subject = !empty($request->type) ? "You requested to set your Transaction PIN": "You requested to reset your Transaction PIN";
        $body = '<p>Hello! ' . auth()->user()->full_name . '</p>';

        if (\Request::is('mobile/*')) {
            $reset_link =  url('/') . "/confirm_reset_pin?token=" . $hashedToken;
        }else{
            $reset_link =  url('/') . "/confirm_reset_pin?token=" . $hashedToken;
        }
        
        $body .= '<p style="line-height: 2.0;">Please <strong>login</strong> to your account on ' . config('app.name') . ' and click the button below to set your transaction pin. <br> <a class="btn btn-info" target="_blank" href="' . $reset_link . '" style="position: margin:5px; relative;margin-bottom:50px;color: #fff;font-weight: 500;padding: 8px 20px;font-size: 13px;line-height: 24px;letter-spacing: 0.01em;border-radius: 4px;border: 1px solid;transition: all .4s ease;background:#950eb3;text-align: center;white-space: nowrap;vertical-align: middle;text-decoration:none">SET PIN</a><br/><br><strong>Please note that this link expires after ' .$expiry->format('jS F, Y, h:iA').'.</strong><br><br>If you did not request for a PIN set/reset, Kindly notify us via WhatsApp us via whatsapp( ' . $web_settings->whatsapp_no . ') immediately.<b><hr/><br>Warm Regards. (' . config('app.name') . ')<br/></p>';
        
        $this->sendEmail(auth()->user()->email, $subject, $body);

        // \Log::info($body);
        $responseX = [
            'title' => 'COMPLETED',
            'icon' => 'success',
            'status' => 'success',
            'message' => '<div class="alert alert-success text-left">Thank you for reaching to us. Transaction PIN link has been sent to your email!!!</div>',
        ];

        if (\Request::is('mobile/*')) {
            return MobileResponseService::json(
                message: 'Thank you for reaching to us. Transaction PIN link has been sent to your email!!!',
                data: ['reset_link' => $reset_link],
                status: 200
            );
        }
    
        return json_encode($responseX);
    }

    public function resetPin2(Request $request){
        $decode = base64_decode(base64_decode($request->token));
        $userdecode = base64_decode(base64_decode(auth()->user()->pin_reset_token));
        
        if(auth()->user()->pin_exp_date < Carbon::now()){
            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: 'PIN reset link has expired, please try to reset PIN again!!!',
                    status: 503
                );
            }

            return redirect(route('pin.reset'))->with('error', 'PIN reset link has expired, please try to reset PIN again!!!');
        }
        
        if($decode !== $userdecode){
            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: 'Invalid token entered, please check your email and click the PIN reset email we sent you!!!',
                    status: 503
                );
            }
            return redirect(route('pin.reset'))->with('error', 'Invalid token entered, please check your email and click the PIN reset email we sent you!!!');

        }

        if (\Request::is('mobile/*')) {
            return MobileResponseService::json(
                message: 'Please Enter your new Transaction PIN',
                data: [
                    'submission_link' => route('mobile.final.pin.reset')
                ],
                status: 200
            );
        }
        return view('customer.reset-pin-view');
    }

    public function finalProcessPin(Request $request,JsonResponseService $response){

        $validator = Validator::make($request->all(), [
            'new_pin' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array(
                'status' => 'fail',
                'message' => '<div class="alert alert-danger text-left">'. $validator->messages()->first() .'</div>',
            );

            if (\Request::is('mobile/*')) {
                return MobileResponseService::json(
                    error: $validator->messages()->first(),
                    status: 422
                );
            }

            return json_encode($response);
            
        }
        
        $new_pin = base64_encode(base64_encode(base64_encode($request->new_pin)));
        auth()->user()->transaction_pin = $new_pin;
        auth()->user()->security_pin = $request->new_pin;
        auth()->user()->save();

        // return redirect(route('dashboard'))->with('message', 'Transaction Transaction PIN changed successfully');
        $responseX = [
            'title' => 'COMPLETED',
            'icon' => 'success',
            'status' => 'success',
            'message' => '<div class="alert alert-success text-left">Transaction PIN changed successfully</div>',
        ];

        if (\Request::is('mobile/*')) {
            return MobileResponseService::json(
                message: 'Transaction PIN changed successfully',
                data: $responseX,
                status: 200
            );
        }
        return json_encode($responseX);
    }

    public function generateApi(){
        return view('customer.generate_api');
    }
    
}
