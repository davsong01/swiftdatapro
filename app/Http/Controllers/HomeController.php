<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\WebSetting;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\CustomerReview;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use App\Models\DepositTransaction;
use App\Models\EarnedTransactions;
use App\Models\VtuappManualFunding;
use Illuminate\Support\Facades\Auth;
use App\Services\JsonResponseService;
use Illuminate\Support\Facades\Session;
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

                if (array_key_exists('content', $result)) {
                    $customer_name = $result["customerName"] ?? null;
                    $customer_address = $result["customerAddress"] ?? null;

                    $mainTokenUnits = $result["mainTokenUnits"] ? '<strong>Units:</strong> ' . $result["mainTokenUnits"] . '<br>' : '';
                    $mainTokenTax = $result["mainTokenTax"] ? '<strong>Token Tax:</strong> ' . $result["mainTokenTax"] . '<br>' : '';

                    $BSSTTOKEN = $result["bonusToken"] ? '<strong>BSST Token: </strong> ' . $result["bonusToken"] . '<br>' : '';
                    $BSSTTOKENDESCRIPTION = $result["bonusTokenDescription"] ? '<strong>BSST Token Description Tax:</strong> ' . $result["bonusTokenDescription"] . '<br>' : '';
                    $BSSTTOKENUNIT = $result["bonusTokenUnits"] ? '<strong>BSST Token Unit: </strong> ' . $result["bonusTokenUnits"] . '<br>' : '';
                    $BSSTTOKENTAX = $result["bonusTokenTax"] ? '<strong>BSST Token Tax: </strong> ' . $result["bonusTokenTax"] . '<br>' : '';
                    $BSSTTOKENAMOUNT = $result["bonusTokenAmount"] ? '<strong>BSST Token Amount: </strong> ' . $result["bonusTokenAmount"] . '<br>' : '';
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
        // dd($transactions->get());
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
}
