<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;

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
}
