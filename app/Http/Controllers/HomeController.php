<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Support\Str;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\CustomerReview;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use App\Models\DepositTransaction;
use App\Models\EarnedTransactions;
use Illuminate\Support\Facades\DB;
use App\Models\VtuappManualFunding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\JsonResponseService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

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
    public function dashboard()
    {
        $web_settings = WebSetting::first();
        $this->createReservedAccount();
        $baseurl = url('/');
        return view('customer.dashboard', compact('web_settings','baseurl'));
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
        $response->handler($responseX);

    }

    public function upgradeLevel(Request $request, JsonResponseService $response){
        $validator = Validator::make($request->all(), [
            'level' => 'required',
        ]);

        if ($validator->fails()) {
            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => $validator->messages()->first(),
            ];
            $response->handler($responseX);
        }

        $setting = WebSetting::first();
        $reference = strtoupper(time() . rand(1000, 9000) . rand(20000000, 90000000));
        $reference2 = strtoupper(rand(1000, 9000) . rand(200000, 900000) . uniqid());
        $level = $request->level;
        $Account_Balance = auth()->user()->wallets;
        $dateTime = new DateTime('now', new DateTimeZone('Africa/Lagos'));
        $time = $dateTime->format("d-M-y  h:i A");
        $timestring = time();

        if ($level == "2") {
            $amount = $setting->level2_charge;
            $refb = $setting->level2_charge - $setting->level2_adminch;
            $admb = $setting->level2_adminch;
            $package = 2;
        }

        if ($level == "3") {
            $amount = $setting->level3_charge;
            $refb = $setting->level3_charge - $setting->level3_adminch;
            $admb = $setting->level3_adminch;
            $package = 3;
        }

        if ($level == "4") {
            $amount = $setting->level4_charge;
            $refb = $setting->level4_charge - $setting->level4_adminch;
            $admb = $setting->level4_adminch;
            $package = 4;
        }

        $adinfo = User::where('username', 'ADMIN')->first();
        $refinfo = auth()->user();
        
        if ($Account_Balance >= $amount && $amount > 0) {
            // Update user wallet and balance
            $newbal = $Account_Balance - $amount;
            auth()->user()->level = $package;
            auth()->user()->wallets = $newbal;
            auth()->user()->save();

            $descr1 = 'Activation of level ' . $package . ' was successful';
            
            $descr2 = 'Referral bonus of N' . $refb . ' landed from ' . auth()->user()->username;
            $descr3 = 'Admin bonus of N' . $admb . ' landed from ' . auth()->user()->username;
            $new_ad_bonus = $adinfo->wallets + $admb;

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
                        'description' => $descr3,
                        'created_at' => Carbon::now(),
                    ]);

                    $data = [
                        'mobile_number' => auth()->user()->mobile,
                        'buyer_email' => auth()->user()->email,
                        'buyer_username' => auth()->user()->username,
                        'buyer_service' => 'UPGRADE',
                        'buyer_amount' => $amount,
                        'buyer_prebal' => $Account_Balance,
                        'buyer_postbal' => $newbal,
                        'buyer_descr' => $descr1,
                        'buyer_status' => 'SUCCESSFUL',
                        'buyer_date' => $time,
                        'timestring' => $timestring,
                        'charge_from' => 'WALLET',
                        'user_type' => $package,
                        'sys_ref' => $reference,
                        'api_ref' => $reference2,
                        'api_report' => 'GOOD',
                        'src_price' => '0',
                        'profit' => '0',
                        'databyte' => '0',
                    ];

                    $this->logTransaction($data);

                    $responseX = [
                        'status_code' => 200,
                        'status' => 'success',
                        'message' => $descr1,
                    ];
                    $response->handler($responseX);
        
                }
            } else {
                // credit admin only with full money
                // Update final transactions
                $new_ad_bonus = $adinfo->wallet + $amount;
                $descr3 = 'Admin bonus of N' . $amount . ' landed from ' . auth()->user()->username;

                // Update earnings
                EarnedTransactions::create([
                    'user_id' => $adinfo->id,
                    'ref_code' => $refinfo->ref_code,
                    'amount' => $amount,
                    'type' => 'credit',
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
                    'buyer_username' => auth()->user()->username, 
                    'buyer_service' => 'UPGRADE', 
                    'buyer_amount' => $amount, 
                    'buyer_prebal' => $Account_Balance, 
                    'buyer_postbal' => $newbal, 
                    'buyer_descr' => $descr1, 
                    'buyer_status' => 'SUCCESSFUL', 
                    'buyer_date' => $time, 
                    'timestring' => $timestring, 
                    'charge_from' => 'WALLET', 
                    'user_type' => $package, 
                    'sys_ref' => $reference, 
                    'api_ref' => $reference2, 
                    'api_report' => 'GOOD', 
                    'src_price' => '0', 
                    'profit' => '0', 
                    'databyte' => '0',
                ];

                $this->logTransaction($data);
                $responseX = [
                    'status_code' => 200,
                    'status' => 'success',
                    'message' => $descr1,
                ];
                $response->handler($responseX);
            }
        }else{
            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => 'You do not have enough balance to upgrade !!!',
            ];
            $response->handler($responseX);
        }
       
    }

    public function createReservedAccount(){
        $payvesel = PaymentGateway::where('id', 2)->first();
        $monnify = PaymentGateway::where('id', 1)->first();
        
        if ($payvesel->status == 'ON' && auth()->user()->payvessel_reserved_account == "") {
            $api_key = $payvesel->api_key;
            $public_key = $payvesel->public_key;
            $secret_key = $payvesel->secret_key;

            $payload = json_encode([
                "name" => auth()->user()->full_name,
                "email" => auth()->user()->email,
                "phoneNumber" => auth()->user()->mobile,
                "bankcode" => ["101", "120001"],
                "businessid" => $payvesel->merchant_id
            ]);

            $headers = [
                'api-key:' . $api_key,
                'api-secret:' . 'Bearer ' . $secret_key,
                'Content-Type:application/json'
            ];
           
            if (env('ENT') == 'local') {
                // $url = "https://sandbox.payvessel.com/api/external/request/customerReservedAccount/";
                $url = "https://api.payvessel.com/api/external/request/customerReservedAccount/";
            } else {
                $url = "https://api.payvessel.com/api/external/request/customerReservedAccount/";
            }

            $response = $this->advancedApiCall($url, $payload, $headers);
           
            if($response){
                auth()->user()->payvessel_reserved_account = $response;
                auth()->user()->save();
            }
        }

        if ($monnify->status == 'ON' && auth()->user()->reserved_account == "") {
            $monnify_hashkeys = $monnify->public_key.':'.$monnify->secret_key;
            $stringk = base64_encode($monnify_hashkeys);
            
            $url = 'https://api.monnify.com/api/v1/auth/login';
            $headers = [
                "Authorization: Basic " . $stringk . "",
            ];

            $response1 = $this->advancedApiCall($url, [], $headers);
            $result = json_decode($response1, true);

            $accessToken = $result['responseBody']['accessToken'];

            $postdata = [
                'accountName' => auth()->user()->full_name,
                'accountReference' => uniqid(). rand(1000, 9000). time(),
                'currencyCode' => "NGN",
                'customerEmail' => auth()->user()->email,
                'contractCode' => $monnify->api_key,
                'customerName' => auth()->user()->full_name,
                "getAllAvailableBanks" => false,
                "preferredBanks" => ["035", "232", "50515", "058"]
            ];

            $headers2 = [
                "Content-Type: application/json",
                "Authorization:Bearer " . $accessToken,
            ];

            $url2 = 'https://api.monnify.com/api/v2/bank-transfer/reserved-accounts';
         
            $response2 = $this->advancedApiCall($url2, json_encode($postdata), $headers2);
            
            if ($response2) {
                auth()->user()->reserved_account = $response2;
                auth()->user()->save();
            }

        }
    }


    public function automatedTransfer(){
        $payvesel = PaymentGateway::where('id', 2)->first();
        $monnify = PaymentGateway::where('id', 1)->first();

        return view('customer.automated_pay', compact('payvesel', 'monnify'));

    }

    public function depositPayment(){
        $list_acctno = VtuappManualFunding::all();
        return view('customer.deposit_payment', compact('list_acctno'));
    }

    public function transactions($type=null){
        return view('customer.transactions', compact('type'));
    }

    public function customerTransactions(Request $request, JsonResponseService $response){

        if(!empty($request->search_data)){
            $transactionx = '<div class="row">';

            $transactions = Transactions::where(function($query) use ($request) {
                $query->where('buyer_username', auth()->user()->username)->where('buyer_descr', 'LIKE', $request->search_data);
            })->orWhere(function($query) use ($request) {
                $query->where('buyer_email', auth()->user()->email)->where('buyer_descr', 'LIKE', $request->search_data);
            })->orderBy('id', 'desc')->get();
            
            
            if ($transactions->count() < 1) {
                $transactionX = '<div class="alert" style="background-color: #fff;border-radius: 10px 5px;color:black">No Transaction Record Yet</div>';
            } else {
                foreach($transactions as $transaction){
                    if ($transaction->buyer_status == "SUCCESSFUL") {
                        $actmode = "background-color: lightseagreen";
                    } else {
                        $actmode = "background-color: palevioletred";
                    }

                    $transactionX .= '<div class="col-md-6" style="' . $actmode . ';color:#fff;font-size:10px;cursor:pointer;" id="' . $transaction->sys_ref . '" onclick="rceipt(this.id)">

                    <span>Refrence No. : ' . $transaction->sys_ref . '</span><br/>
                    <span>Order: ' . $transaction->buyer_descr . '</span><br/>
                    <span>Date: ' . $transaction->buyer_date . '</small><br/>
                    <span>Amount: ₦' . $transaction->buyer_amount . '</span> <span style="float:right"><i class="fa fa-eye"></i></span><br/>

                    <span>Prev Bal:</span> <span style="cursor: pointer;">₦' . $transaction->buyer_prebal . '</span>

                    <span style="float:right">Post Bal: ₦' . $transaction->buyer_postbal . '</span>
                    </p></div>';
                }

                $transactionx .= '</div>';

                $responseX = [
                    'status' => 'success',
                    'message' => $transactionx,
                ];
                $response->handler($responseX);
            }
        }
        $load_id = $request->load_id;
        $load_amount = $load_id + 200;
        $load_next = $load_amount;

        $nextbtn = "";
        $transaction = "";

        if(!empty($request->reference)){
            $receipt = "";
            $buttons = "<a href='transactions'><button class='btn btn-danger'>Go Back </button></a> <button class='btn btn-success' id='PdfDial'>Print Slip </button>";
            $reference = $request->reference;
            $l = Transactions::where('sys_ref', $reference)->first();

            if(!empty($l)){
                $result = !empty($l->api_response) ? json_decode($l->api_response, true) : [];

                $customer_name = '';
                $customer_address = '';

                $mainTokenUnits = '';
                $mainTokenTax =  '';

                $BSSTTOKEN =  '';
                $BSSTTOKENDESCRIPTION =  '';
                $BSSTTOKENUNIT =  '';
                $BSSTTOKENTAX = '';
                $BSSTTOKENAMOUNT = '';
                // dd($result);
                if (array_key_exists('content', $result)) {
                    $customer_name = $result["customerName"] ?? null;
                    $customer_address = $result["customerAddress"] ?? null;

                    $mainTokenUnits = isset($result["mainTokenUnits"]) ? '<strong>Units:</strong> ' . $result["mainTokenUnits"] . '<br>' : '';
                    $mainTokenTax = isset($result["mainTokenTax"]) ? '<strong>Token Tax:</strong> ' . $result["mainTokenTax"] . '<br>' : '';

                    $BSSTTOKEN = isset($result["bonusToken"]) ? '<strong>BSST Token: </strong> ' . $result["bonusToken"] . '<br>' : '';
                    $BSSTTOKENDESCRIPTION = isset($result["bonusTokenDescription"]) ? '<strong>BSST Token Description Tax:</strong> ' . $result["bonusTokenDescription"] . '<br>' : '';
                    $BSSTTOKENUNIT = isset($result["bonusTokenUnits"]) ? '<strong>BSST Token Unit: </strong> ' . $result["bonusTokenUnits"] . '<br>' : '';
                    $BSSTTOKENTAX = isset($result["bonusTokenTax"]) ? '<strong>BSST Token Tax: </strong> ' . $result["bonusTokenTax"] . '<br>' : '';
                    $BSSTTOKENAMOUNT = isset($result["bonusTokenAmount"]) ? '<strong>BSST Token Amount: </strong> ' . $result["bonusTokenAmount"] . '<br>' : '';
                }

                if ($l->buyer_status == "SUCCESSFUL") {
                    $actmode = "background-color: lightseagreen";
                } else {
                    $actmode = "background-color: palevioletred";
                }

                $receipt = '<div class="row "><div class="col-12 QA_section"><div class="card QA_table "><div class="card-header">Invoice<strong>' . $l->buyer_date . '</strong><span class="float-end"> <strong>Status:</strong> ' . $l->buyer_status . '</span></div><div class="card-body"><div class="row mb-4"><div class="col-sm-6">
                <h6 class="mb-3">From:</h6><div><strong>Order 1</strong></div><div>' . $l->sys_ref . '</div><div>Buyer Email: ' . $l->buyer_email . '</div><div>Customer Name: ' . $customer_name . '</div><div>Customer Address: ' . $customer_address . '</div><div>Buyer Phone: ' . $l->mobile_number . '<br></div></div><div class="table-responsive-sm"><table class="table table-striped"><thead><tr><th class="center">#</th><th>Item</th><th>Description</th><th class="right">Cost</th></tr></thead><tbody><tr><td class="center">1</td><td class="left strong">' . strtoupper($l->buyer_service) . '</td><td class="left">' . $l->buyer_descr . '<br><strong style="color:blue">' . (isset($l->extra) && !empty($l->extra) ? $l->extra : '') . '</strong><br>' . $mainTokenUnits . '' . $mainTokenTax . '' . $BSSTTOKEN . '' . $BSSTTOKENDESCRIPTION . '' . $BSSTTOKENUNIT . '' . $BSSTTOKENTAX . '' . $BSSTTOKENAMOUNT . '</td><td class="right">' . $l->buyer_amount . '</td></tr><tr><td class="center"></td><td></td>' . isset($cu) . '.</td></tr></tbody></table></div><div class="row"><div class="col-lg-4 col-sm-5"></div><div class="col-lg-4 col-sm-5 ms-auto QA_section"><table class="table table-clear QA_table"><tbody><tr><td class="left"><strong>Amount</strong></td><td class="right">N ' . $l->buyer_amount . '</td></tr><tr><td class="left"><strong>Prev Bal.</strong></td><td class="right">N ' .$l->buyer_prebal . '</td></tr><tr><td class="left"><strong>Post Bal.</strong></td><td class="right">N ' . $l->buyer_postbal . '</td></tr></tbody></table></div></div>';

            }else{
                $receipt = '<div class="media_thumb ml_25"><br><br><br><br><br><br><br><br><center><h3 style="font-weight: bold;font-size: 20px;">Sorry !!! Transaction Not Found</h3><p>There is no reference no supply for this transaction !!! </p></center><br><br><br><br><br><br><br><br></div>';
            }
            return view('customer.transactions_invoice', compact('receipt','buttons'));

        }else{
            if(!empty($request->type)){
                $type = $request->type == 'success' ? 'SUCCESSFUL' : 'FAILED';
                
                $transactions = Transactions::where(function ($query) use ($type) {
                    $query->where('buyer_username', auth()->user()->username)->where('buyer_status', $type);
                })->orWhere(function ($query) use ($type) {
                    $query->where('buyer_email', auth()->user()->email)->where('buyer_status', $type);
                })->orderBy('id', 'desc');
    
            }else{
                $transactions = Transactions::where('buyer_username', auth()->user()->username)->orWhere('buyer_email', auth()->user()->email)->orderBy('id', 'desc');
            }
        }

        
        if ($transactions->count() > 200 && $transactions->count() > $load_next) {
            $nextbtn = '<button class="btn btn-primary" id="' . $load_next . '"  onclick="showMore(this.id)">Load More >>> </button>';
        }else{
            $nextbtn = '';
        }
    
        if($transactions->count() < 1){
            $transactionx = '<div class="alert" style="background-color: #fff;border-radius: 10px 5px;color:black">No Transaction Record Yet</div>';
        }else{
            $transactions = $transactions->limit($load_amount)->get();
            $transactionx = '<div class="row">';
            
            foreach($transactions as $transaction){
                if ($transaction->buyer_status == "SUCCESSFUL") {
                    $actmode = "background-color: #199263";
                }else{
                    $actmode = "background-color: #DB1A22";
                }

                $transactionx .= '<div class="col-md-6" style="' . $actmode . ';color:#fff;font-size:10px;cursor:pointer;padding:10px" id="' . $transaction->sys_ref . '" onclick="rceipt(this.id)">

                <span>Refrence No. : ' . $transaction->sys_ref . '</span><br/>
                <span>Order: ' . $transaction->buyer_descr . '</span><br>' . (isset($transaction->extra) && !empty($transaction->extra) ? '
                <strong style="margin:5px 0; background: black;color: yellow;padding: 8px;font-size: medium;display: inline-flex;"><br>Extras: ' . $transaction->extra . '<br>' : '') . '</strong><br/>
                <span>Date: ' . $transaction->buyer_date . '</small><br/>
                <span>Amount: ₦' . $transaction->buyer_amount . '</span> <span style="float:right"><i class="fa fa-eye"></i></span><br/>

                <span>Prev Bal:</span> <span style="cursor: pointer;">₦' . $transaction->buyer_prebal . '</span>

                <span style="float:right">Post Bal: ₦' . $transaction->buyer_postbal . '</span>
                    </p>
                        
                </div>';
            }

            $transactionx .= '</div>';
        }
        
        $responseX = [
            'status' => 'success',
            'message' => $transactionx,
            'ctrlButton' => $nextbtn,

        ];
        $response->handler($responseX);
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
        $seltdatabyte = $this->getSingleAnalysis('mtn-sme', $start, $end);
        $total_smedatacost = $seltdatabyte['cost'];
        $total_smedata = $seltdatabyte['data'];

        ////MTN-CG
        $seltdatabyte = $this->getSingleAnalysis('mtn-cg', $start, $end);
        $total_mtncgdatacost = $seltdatabyte['cost'];
        $total_mtncgdata = $seltdatabyte['data'];
        ///

        ////AIRTEL-CG
        $seltdatabyte = $this->getSingleAnalysis('airtel-cg', $start, $end);
        $total_airtelcgdata = $seltdatabyte['data'];
        $total_airtelcgdatacost = $seltdatabyte['cost'];

        ////AIRTEL
        $seltdatabyte = $this->getSingleAnalysis('airtel', $start, $end);
        $total_airteldata = $seltdatabyte['data'];
        $total_airteldatacost = $seltdatabyte['cost'];
       
        ////GLO
        $seltdatabyte = $this->getSingleAnalysis('glo', $start, $end);
        $total_glodata = $seltdatabyte['data'];
        $total_glodatacost = $seltdatabyte['cost'];
        
        ////9MOBILE
        $seltdatabyte = $this->getSingleAnalysis('9mobile', $start, $end);
        $total_mobiledata = $seltdatabyte['data'];
        $total_mobiledatacost = $seltdatabyte['cost'];

        ////AIRTIME-VTU
        $seltdatabyte = $this->getSingleAnalysis('vtu', $start, $end, 'vtu');
        $total_vtucost = $seltdatabyte['cost'];

        ////AIRTIME-SNS
        $seltdatabyte = $this->getSingleAnalysis('sns', $start, $end, 'sns');
        $total_snscost = $seltdatabyte['cost'];

        ////BILLS
        $seltdatabyte = $this->getSingleAnalysis('electric', $start, $end, 'electric');
        $total_billscost = $seltdatabyte['cost'];
       
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
            $seltdatabyte = Transactions::where(['buyer_status' => 'SUCCESSFUL', 'buyer_email' => auth()->user()->email])->where('buyer_service', 'LIKE','%'.$like.'%')->whereBetween('created_at', [$start, $end])->get();
        }else{
            $seltdatabyte = Transactions::where(['buyer_status' => 'SUCCESSFUL', 'buyer_service' => $buyer_service, 'buyer_email' => auth()->user()->email])->whereBetween('created_at', [$start, $end])->get();
        }

        try {
            //code...
            $total_smedatacost = $seltdatabyte->sum('buyer_amount+0');
            $total_smedata = $seltdatabyte->sum('databyte+0') > 0 ? $seltdatabyte->sum('databyte') / 1000 : 0;
        } catch (\Throwable $th) {
            dd($th->getMessage(), $buyer_service);
        }

        return [
            'cost' => $total_smedatacost ?? 0,
            'data' => $total_smedata ?? 0,
        ];
    }

    public function downlines(Request $request, JsonResponseService $response){

        $referral_code = strtoupper($request->referral_code);
        $amount = 0;
        $transaction = '<div class="col-md-12">';

        $lettr = User::where('ref_by', $referral_code)->orWhere('ref_by', auth()->user()->username)->orderBy('id', 'desc')->paginate(20);

        return view('customer.downlines', compact('lettr'));

    }

    public function cashbackEarned(){
        // cashback_earn
        $lettr = EarnedTransactions::where('user_id', auth()->user()->id)->orderBy('created_at','DESC')->paginate(20);
        return view('customer.cashback', compact('lettr'));
    }

    public function profile(){
        return view('customer.profile');
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

            $response->handler($responseX);
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
            return json_encode($response);

        }

        $tk = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => auth()->user()->email,
            'token' => $tk,
            'created_at' => Carbon::now()
        ]); //Get the token just created above
        
        DB::table('password_reset_tokens')
        ->where('email', auth()->user()->email)->first();

        $subject = "Your Password Recovery Link Has Arrived";
        $body = '<p>Hello! ' . auth()->user()->full_name . '</p>';
        $reset_link =  url('/') . "/reset-password?token=". $tk;
        $body .= '<p style="line-height: 2.0;">Please click the button below to reset your password <a class="btn btn-info" target="_blank" href="'. $reset_link. '" style="position: relative;color: #fff;font-weight: 500;padding: 8px 20px;font-size: 13px;line-height: 24px;letter-spacing: 0.01em;border-radius: 4px;border: 1px solid;transition: all .4s ease;background:#950eb3;text-align: center;white-space: nowrap;vertical-align: middle;text-decoration:none">RESET PASSWORD</a><br/>If you did not request for Password Recovery, kindly notify us via whatsapp on ' . $web_settings->whatsapp_no . ' Immediately.<b><hr/><br>Warm Regards. (' . config('app.name') . ')<br/></p>';

        $this->sendEmail($user->email, $subject, $body);

        $responseX = [
            'title' => 'COMPLETED',
            'icon' => 'success',
            'status' => 'success',
            'message' => 'Please check your email for a password reset link!!!',
        ];

        $response->handler($responseX);
    }

    public function resetPin(){
        return view('customer.reset_pin');
    }

    public function processResetPin(Request $request, JsonResponseService $response){
        $web_settings = WebSetting::first();

        $validator = Validator::make($request->all(), [
            'old_pin' => 'required',
        ]);

        if ($validator->fails()) {
            $responseX = [
                'status_code' => 100,
                'status' => 'fail',
                'message' => $validator->messages()->first(),
            ];

            $response->handler($responseX);
        }

        if (!Hash::check($request->old_pin, auth()->user()->pass)) {
            $response = array(
                'status' => 'fail',
                'message' => '<div class="alert alert-danger text-left">Incorrect password !!!</div>',
            );
            return json_encode($response);
        }

        $token = Str::random(60);
        $hashedToken = base64_encode(base64_encode($token.time()));
        $expiry = Carbon::now()->addMinute(20);

        auth()->user()->pin_reset_token = $hashedToken;
        auth()->user()->pin_exp_date = $expiry;
        auth()->user()->save();

        $subject = "You requested a PIN Reset Token";
        $body = '<p>Hello! ' . auth()->user()->full_name . '</p>';
        $reset_link =  url('/') . "/confirm_reset_pin?token=" . $hashedToken;
        $body .= '<p style="line-height: 2.0;">Please <strong>login</strong> to your account on ' . config('app.name') . ' and click the button below to reset your security pin. <br> <a class="btn btn-info" target="_blank" href="' . $reset_link . '" style="position: margin:5px; relative;margin-bottom:50px;color: #fff;font-weight: 500;padding: 8px 20px;font-size: 13px;line-height: 24px;letter-spacing: 0.01em;border-radius: 4px;border: 1px solid;transition: all .4s ease;background:#950eb3;text-align: center;white-space: nowrap;vertical-align: middle;text-decoration:none">RESET PIN</a><br/><br><strong>Please note that this link expires after ' .$expiry->format('jS F, Y, h:iA').'.</strong><br><br>If you did not request for a PIN change, Kindly notify us via WhatsApp us via whatsapp( ' . $web_settings->whatsapp_no . ') immediately.<b><hr/><br>Warm Regards. (' . config('app.name') . ')<br/></p>';

        $this->sendEmail(auth()->user()->email, $subject, $body);
        // \Log::info($body);
        $responseX = [
            'title' => 'COMPLETED',
            'icon' => 'success',
            'status' => 'success',
            'message' => '<div class="alert alert-success text-left">Thank you for reaching to us. PIN reset link has been forwarded to your email!!!</div>',
        ];

        $response->handler($responseX);
    }

    public function resetPin2(Request $request){
        $decode = base64_decode(base64_decode($request->token));
        $userdecode = base64_decode(base64_decode(auth()->user()->pin_reset_token));
        
        if(auth()->user()->pin_exp_date < Carbon::now()){
            return redirect(route('pin.reset'))->with('error', 'PIN reset link has expired, please try to reset PIN again!!!');
        }
       
        if($decode !== $userdecode){
            return redirect(route('pin.reset'))->with('error', 'Invalid token entered, please check your email and click the PIN reset email we sent you!!!');

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
            return json_encode($response);
            
        }
      
        $new_pin = base64_encode(base64_encode(base64_encode($request->new_pin)));
        auth()->user()->transaction_pin = $new_pin;
        auth()->user()->security_pin = $request->new_pin;
        auth()->user()->save();

        // return redirect(route('dashboard'))->with('message', 'Security PIN changed successfully');
        $responseX = [
            'title' => 'COMPLETED',
            'icon' => 'success',
            'status' => 'success',
            'message' => '<div class="alert alert-success text-left">Security PIN changed successfully</div>',
        ];

        $response->handler($responseX);
       
    }
    
}
