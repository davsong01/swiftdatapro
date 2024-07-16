<?php

namespace App\Http\Controllers;

use DB;
use PDO;
use DateTime;
use DateTimeZone;
use App\Models\Api;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\AutomatedDeposit;
use App\Models\DepositTransaction;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentgateways = PaymentGateway::orderBy('created_at', 'DESC')->get();

        return view('admin.paymentgateways.index', compact('paymentgateways'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.paymentgateways.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentGateway $paymentGateway)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentGateway $paymentgateway)
    {
        return view('admin.paymentgateways.edit', compact('paymentgateway'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentGateway $paymentgateway)
    {
        $this->validate($request, [
            "name" => "required",
            "slug" => "required",
            "reserved_account_payment_charge_type" => 'required',
            "reserved_account_payment_charge" => "nullable",
            "password" => "nullable",
            "api_key" => "nullable",
            "secret_key" => "nullable",
            "public_key" => "nullable",
            "contract_id" => "nullable",
            "merchant_email" => "nullable",
            "live_base_url" => "nullable",
            "sandbox_base_url" => 'nullable',
            "base_url" => "nullable",
            "status" => "nullable",
            "card_payment_charge" => "nullable",
            "status" => "required",
        ]);
        
        $paymentgateway->update([
            "name" => $request->name,
            "slug" => $request->slug,
            "card_payment_charge" => $request->card_payment_charge,
            'reserved_account_payment_charge_type' => $request->reserved_account_payment_charge_type,
            "reserved_account_payment_charge" => $request->reserved_account_payment_charge,
            "api_key" => $request->api_key,
            "secret_key" => $request->secret_key,
            "public_key" => $request->public_key,
            "contract_id" => $request->contract_id,
            "merchant_email" => $request->merchant_email,
            "live_base_url" => $request->live_base_url,
            "sandbox_base_url" => $request->sandbox_base_url,
            "status" => $request->status,
            "charge" => $request->charge,
        ]);

        return back()->with('message', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentGateway $paymentGateway)
    {
        //
    }

    public function createPayVesselReservedAccount($gateway, $data){
        $api_key = $gateway->api_key;
        $public_key = $gateway->public_key;
        $secret_key = $gateway->secret_key;

        $payload = json_encode([
            "name" => auth()->user()->full_name,
            "email" => auth()->user()->email,
            "phoneNumber" => auth()->user()->mobile,
            "bankcode" => ["101", "120001"],
            "businessid" => $gateway->merchant_id
        ]);

        $headers = [
            'api-key:' . $api_key,
            'api-secret:' . 'Bearer ' . $secret_key,
            'Content-Type:application/json'
        ];

        if (env('ENT') == 'local') {
            $url = "https://api.payvessel.com/api/external/request/customerReservedAccount/";
        } else {
            $url = "https://api.payvessel.com/api/external/request/customerReservedAccount/";
        }

        $res = $this->advancedApiCall($url, $payload, $headers);
        $res = json_decode($res, true);
        $status = !empty($res['banks']) && $res['status'] == true ? 'success' : 'failed';
        
        if($status == 'success'){
            return [
                'status' => $status,
                'response' => $res,
                'accounts' => $res['banks'],
                'payment_gateway_id' => $gateway->id
            ];
        }else{
            \Log::info([
                'Reserved account error' => [
                    'status' => $status,
                    'response' => $res,
                    'payment_gateway_id' => $gateway->id
                ]
            ]);
            return [
                'status' => $status,
                'response' => $res,
                'payment_gateway_id' => $gateway->id
            ];
        }
    }

    public function createMonnifyReservedAccount($gateway, $data){
        $monnify_hashkeys = $gateway->public_key . ':' . $gateway->secret_key;
        $stringk = base64_encode($monnify_hashkeys);
        $base_url = env('ENT') == 'local' ? $gateway->sandbox_base_url : $gateway->live_base_url;
        
        $url = $base_url.'v1/auth/login';
        $headers = [
            "Authorization: Basic " . $stringk . "",
        ];
        
        $response1 = $this->advancedApiCall($url, [], $headers);
        
        $result = json_decode($response1, true);

        $accessToken = $result['responseBody']['accessToken'];
        $data['contractCode'] = $gateway->contract_id;
        
        $headers2 = [
            "Content-Type: application/json",
            "Authorization:Bearer " . $accessToken,
        ];

        $url2 = $base_url.'v2/bank-transfer/reserved-accounts';

        $response2 = $this->advancedApiCall($url2, json_encode($data), $headers2);
        
        $res = json_decode($response2, true);

        if ($res && $res['responseCode'] == 0 && $res['responseMessage'] == 'success'){
            $status = 'success';
        }else{
            $status = 'failed';
        }
        
        if ($status == 'success' && isset($res['responseBody']['accounts'])) {
            return [
                'status' => $status,
                'response' => $res,
                'accounts' => $res['responseBody']['accounts'],
                'payment_gateway_id' => $gateway->id
            ];
        } else {
            \Log::info([
                'Reserved account error' => [
                    'status' => $status,
                    'response' => $res,
                    'payment_gateway_id' => $gateway->id
                ]
            ]);
            return [
                'status' => $status,
                'response' => $res,
                'payment_gateway_id' => $gateway->id
            ];
        }
    }

    public function analyzeWebhookPayment(Request $request){
        $type = $request->type;

        if ($type == 2) {
            $api = PaymentGateway::where('id', 2)->first();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // CSRF exemption
                $payload = file_get_contents('php://input');
                $payvessel_signature = $_SERVER['HTTP_PAYVESSEL_HTTP_SIGNATURE'] ?? $request->header('HTTP_PAYVESSEL_HTTP_SIGNATURE'); 
                $api = PaymentGateway::where('id', 2)->first();
                $ip_address = ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['REMOTE_HOST'];
            
                // $ip_address = $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'];
                $hashkey = hash_hmac('sha512', $payload, $api->secret_key);
                
                if (env('ENT') == 'local') {
                    $ip_address = '162.246.254.36';
                    $payvessel_signature = $hashkey;
                }

                if ($payvessel_signature == $hashkey && $ip_address == "162.246.254.36") {
                    if (env('ENT') == 'local') {
                        $data = json_decode('{"transaction":{"date":"2023-10-14T19:36:29","reference":"100033231014183603000986812789","sessionid":"100033231014183603000986812789"},"order":{"currency":"NGN","amount":500,"settlement_amount":470,"fee":30,"description":"Inbound Transfer From PALMPAY - TAOFEEK PHILIP ADEBOLA/Palmpay to ADEGLOBAL-Admin/5121073865-9Payment Service Bank"},"customer":{"email":"adeglobaltelecom@gmail.com","phone":"09057566532"},"virtualAccount":{"virtualAccountNumber":"5121073865","virtualBank":"120001"},"sender":{"senderAccountNumber":"7046734407","SenderBankCode":"100033","senderBankName":"Palmpay","senderName":"PALMPAY - TAOFEEK PHILIP ADEBOLA"},"message":"Success","code":"00"}', true);
                    }else{
                        $data = json_decode($payload, true);
                    }
                    
                    $provider_charges = floatval($data['order']['fee'] ?? 0);
                    $charges = $this->calculateGatewayCharge($data['order']['amount'], $api);
                    $final_amount = floatval($data['order']['amount']) - $charges;
                    $api_reference = $data['transaction']['reference'];
                    $sys_reference = $this->generateRequestId('SWFT');
                    $description = $data['order']['description'];
                    $customer_email = $data['customer']['email'];
                    $account_number = $data['virtualAccount']['virtualAccountNumber'];
                
                    $user = User::where('email', $customer_email)->first();
                    if(!$user){
                        die();
                    }
                    
                    if ($final_amount > 0 && $data['message'] == 'Success') {
                        // check if reference exists
                        $cx_trx = DepositTransaction::where('user_amount', $final_amount)->where('api_ref', $api_reference)->count();
                        $account = AutomatedDeposit::where('account_number', $account_number)->where('payment_gateway_id', $api->id)->first();
                        
                        if(!$account){
                            die();
                        }
                        
                        if ($cx_trx < 1) {
                            $cbal = walletBalance($user);
                            $walletBal = $cbal;

                            $NewBal = $walletBal + $final_amount;
                            $order_descr = 'Wallet funding of N' . $final_amount . ' was successful';

                            // Update user balance
                            $user->wallets = $NewBal;
                            $user->save();

                            try {

                                \DB::beginTransaction();

                                $deposit = DepositTransaction::create([
                                    'user_id' => $user->id,
                                    'user_service' => 'DEPOSIT',
                                    'user_amount' => $final_amount,
                                    'user_prebal' => $walletBal,
                                    'user_postbal' => $NewBal,
                                    'user_descr' => $order_descr,
                                    'user_status' => 'SUCCESSFUL',
                                    'sys_ref' => $sys_reference,
                                    'api_ref' => $api_reference,
                                    'payment_gateway_id' => $api->id,
                                    'account_number' => $account_number,
                                ]);

                                if ($deposit) {
                                    $data = [
                                        'mobile_number' => $user->mobile,
                                        'unit_price' => $final_amount,
                                        'buyer_amount' => $final_amount,
                                        'buyer_prebal' => $walletBal,
                                        'provider_charge' => $provider_charges,
                                        'buyer_postbal' => $NewBal,
                                        'buyer_descr' => $order_descr,
                                        'buyer_status' => 'SUCCESSFUL',
                                        'buyer_service' => 'DEPOSIT',
                                        'sys_ref' => $sys_reference,
                                        'api_ref' => $api_reference,
                                        'api_report' => 'DONE',
                                        'buyer_quantity' => 1,
                                        'discount' => 0,
                                        'src_price' => 0,
                                        'channel' => 'website',
                                        'wallet_funding_provider' => $api->id,
                                        'billersCode' => 'WALLET-FUNDING',
                                        'profit' => 0,
                                        'type' => 'credit',
                                        'user_id' => $user->id,
                                        'charges' => $charges,
                                        'user_type' => $user->level,
                                        'api_response' => json_encode($request->all()),
                                    ];

                                    $this->logTransaction($data);
                                }

                                DB::commit();
                            } catch (\Throwable $th) {
                                DB:: rollback();
                                \Log::info(['Payment Webhook Error: ' => $th->getMessage().' Line: '.$th->getLine(). ' File: '.$th->getFile() ]);
                                //throw $th; 
                            }
                        } else {
                            die();
                        }
                    }
                } else {
                    exit();
                }
            } else {
                exit();
            }
        } elseif ($type == 1) {
            $api = PaymentGateway::where('id', 1)->first();
            $ip = ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['REMOTE_HOST'];
            
            if(env('ENT') != 'local'){
                if ($ip != "35.242.133.146") die("Invalid IP");
            }
    
            $MONNIFY_SIGNATURE = $_SERVER['HTTP_MONNIFY_SIGNATURE'] ?? $request->header('HTTP_MONNIFY_SIGNATURE'); 

            $DEFAULT_MERCHANT_CLIENT_SECRET = "$api->secret_key"; // SK
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            $amountPaid = $data->eventData->amountPaid;
            $api_reference = $data->eventData->transactionReference;
            $sys_reference = $data->eventData->product->reference;
            $type = $data->eventData->product->type;
            $paymentStatus = $data->eventData->paymentStatus;
            $customer_email = $data->eventData->customer->email;
            $account_number = $data->eventData->destinationAccountInformation->accountNumber;

            $provider_charges = $charges = $this->calculateGatewayCharge($amountPaid, $api);

            $final_amount = $amountPaid - $charges;
            
            $user = User::where('email', $customer_email)->first();
            if(!$user){
                die();
            }
            
            $data_decod = $json;
            $computedHash = $this->computeSHA512TransactionHash($data_decod, $DEFAULT_MERCHANT_CLIENT_SECRET);
            
            if(env('ENT') == 'local'){
                $MONNIFY_SIGNATURE = $computedHash;
            }

            if ($MONNIFY_SIGNATURE == $computedHash && $final_amount > 0 && $paymentStatus == 'PAID') {
                $account = AutomatedDeposit::where('account_number', $account_number)->first();
                
                if (!$account) {
                    die();
                }

                $cx_trx = DepositTransaction::where('user_amount', $final_amount)->where('api_ref', $api_reference)->count();
                
                if ($cx_trx < 1) {
                    $cbal = walletBalance($user);
                    $walletBal = $cbal;

                    $NewBal = $walletBal + $final_amount;
                    $order_descr = 'Wallet funding of N' . $final_amount . ' was successful';
                    
                    // Update user balance
                    $user->wallets = $NewBal;
                    $user->save();

                    try {

                        \DB::beginTransaction();
                        $deposit = DepositTransaction::create([
                            'user_id' => $user->id, 
                            'user_service' => 'DEPOSIT', 
                            'user_amount' => $final_amount, 
                            'user_prebal' => $walletBal, 
                            'user_postbal' => $NewBal, 
                            'user_descr' => $order_descr , 
                            'user_status' => 'SUCCESSFUL', 
                            'sys_ref' => $sys_reference, 
                            'api_ref' => $api_reference,
                            'payment_gateway_id' => $api->id,
                            'account_number' => $account_number,
                        ]);

                        if($deposit){
                            $data = [
                                'mobile_number' => $user->mobile,
                                'buyer_email' => $user->email,
                                'buyer_username' => $user->username,
                                'unit_price' => $final_amount,
                                'buyer_amount' => $final_amount,
                                'buyer_prebal' => $walletBal,
                                'provider_charge' => $charges,
                                'buyer_postbal' => $NewBal,
                                'buyer_descr' => $order_descr,
                                'buyer_status' => 'SUCCESSFUL',
                                'buyer_service' => 'DEPOSIT',
                                'user_type' => $user->level,
                                'sys_ref' => $sys_reference,
                                'api_ref' => $api_reference,
                                'api_report' => 'DONE',
                                'buyer_quantity' => 1,
                                'discount' => 0,
                                'src_price' => 0,
                                'channel' => 'website',
                                'wallet_funding_provider' => $api->id,
                                'billersCode' => 'WALLET-FUNDING',
                                'profit' => 0,
                                'type' => 'credit',
                                'user_id' => $user->id,
                                'charges' => $charges,
                                'api_response' => json_encode($request->all()),
                            ];
        
                            $this->logTransaction($data);
                        }
                        DB::commit();
                    }catch (\Throwable $th) {
                        DB:: rollback();
                        \Log::info(['Payment Webhook Error: ' => $th->getMessage().' Line: '.$th->getLine(). ' File: '.$th->getFile() ]);
                        //throw $th; 
                    }
                } else {
                    die();
                }
            } else {
                exit();
            }
        }
    }

    private function computeSHA512TransactionHash($stringifiedData, $clientSecret)
    {
        $computedHash = hash_hmac('sha512', $stringifiedData, $clientSecret);
        return $computedHash;
    }


    private function calculateGatewayCharge($amount, $gateway){
        $reserved_account_payment_charge_type = $gateway->reserved_account_payment_charge_type;
        $reserved_account_payment_charge = $gateway->reserved_account_payment_charge;

        $charge = 50;
        if($reserved_account_payment_charge_type == 'flat'){
            $charge = !empty($reserved_account_payment_charge) ? $reserved_account_payment_charge : $charge;
        }else{
            $charge = !empty($reserved_account_payment_charge) ? ($reserved_account_payment_charge / 100) * $amount : $charge;
        }
        
        return $charge;
    }

    public function deleteMonnifyReservedAccount(string $account_reference)
    {
        $api = PaymentGateway::where('slug', 'monnify')->first();

        $token = $this->login($api);
    
        if (empty($token)) {
            return [
                'status' => 'failed',
                'status_code' => 0,
            ];
        } else {
            $base_url = env('ENT') == 'local' ? $api->sandbox_base_url : $api->live_base_url;
            $url = $base_url . 'v1/bank-transfer/reserved-accounts/reference/' . $account_reference;

            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer " . $token . "",
            ];
            
            $response = $this->basicApiCall($url, [], $headers, 'DELETE');
            $response = json_decode($response, true);
            
            if (
                $response && $response['responseCode'] == "0" &&
                $response['responseMessage'] == 'success'
            ) {
                AutomatedDeposit::where('account_reference', $account_reference)->delete();
                $data = [
                    'status' => 'success',
                    'data' => '',
                ];
            } else {
                $data = [
                    'status' => 'failed',
                    'data' => $response['responseBody'] ?? $response['responseMessage'],
                ];
            }
            
            return $data;
        }
    }

    public function pullMonnifyReservedAccountByReference(string $account_reference)
    {
        $api = PaymentGateway::where('slug', 'monnify')->first();

        $token = $this->login($api);

        if (empty($token)) {
            return [
                'status' => 'failed',
                'status_code' => 0,
            ];
        } else {
            $url = $api->base_url . 'v1/bank-transfer/reserved-accounts/reference/' . $account_reference;
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer " . $token . "",
            ];

            $response = $this->basicApiCall($url, [], $headers, 'DELETE');
            
            if (
                $response && $response['responseCode'] == 0 &&
                $response['responseMessage'] == 'success'
            ) {
                AutomatedDeposit::where('account_reference', $account_reference)->delete();
                $data = [
                    'status' => 'success',
                    'data' => '',
                ];
            } else {
                $data = [
                    'status' => 'failed',
                    'data' => $response['responseBody'] ?? $response['responseMessage'],
                ];
            }

            return $data;
        }
    }


    public function login($api)
    {        
        $headers = [
            "Authorization: Basic " . base64_encode($api->api_key . ':' . $api->secret_key)
        ];

        $base_url = env('ENT') == 'local' ? $api->sandbox_base_url : $api->live_base_url;
        $url = $base_url . 'v1/auth/login';
        
        $response = $this->advancedApiCall($url, [], $headers, 'POST');
        $response = json_decode($response, true);
        
        $accessToken = $response['responseBody']['accessToken'] ?? null;

        return $accessToken;
    }
}
