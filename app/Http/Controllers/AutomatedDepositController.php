<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\AutomatedDeposit;

class AutomatedDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $accounts = AutomatedDeposit::with('customer')->orderby('created_at', 'DESC');

        if (!empty($request->email)) {
            $user = User::where('email', $request->email)->first();
            $id = $user->id;
            $accounts = $accounts->where('user_id', $id);
        }

        if (!empty($request->gateway)) {
            $accounts = $accounts->where('payment_gateway_id', $request->gateway);
        }

        if (!empty($request->reference)) {
            $accounts = $accounts->where('account_reference', $request->reference);
        }

        if (!empty($request->bank)) {
            $accounts = $accounts->where('bank_code', $request->bank);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $accounts = $accounts->whereBetween('created_at', [$from, $to]);
        }
        
        $accounts = $accounts->paginate(50);
        $gateways = PaymentGateway::all();
        $banks = AutomatedDeposit::select('bank_code', 'bank_name')->distinct()->get();
        
        return view('admin.reserved_accounts.index', compact('accounts', 'gateways','banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(AutomatedDeposit $automatedDeposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AutomatedDeposit $automatedDeposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AutomatedDeposit $automatedDeposit)
    {
        //
    }

    public function pullMonnifyReservedAccountByReference(Request $request){
        $customer = User::where('id', $request->customer_id)->first();
        $pull = app('App\Http\Controllers\PaymentGatewayController')->pullMonnifyReservedAccountByReference($request->account_reference);
        dd($customer, $pull);
        if ($delete['status'] == 'success') {
            return back()->with('message', 'Reserved Accounts Spooled Successfully');
        } else {
            return back()->with('error', $delete['data']);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $account = AutomatedDeposit::with('deposit_transactions')->where('id', $id)->first();
        
        if ($account->payment_gateway_id == 1) {
            $delete = app('App\Http\Controllers\PaymentGatewayController')->deleteMonnifyReservedAccount($account->account_reference);
        }else{
            return back()->with('error', 'Not a Monnify account');
        }

        if ($delete['status'] == 'success') {
            return back()->with('message', 'Reserved Account Deleted successfully');
        } else {
            return back()->with('error', $delete['data']);
        }
        // dd($automatedDeposi);
    }
}
