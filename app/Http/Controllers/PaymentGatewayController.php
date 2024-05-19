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
}
