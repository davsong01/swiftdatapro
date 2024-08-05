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

        if (!empty($request->account_number)) {
            $accounts = $accounts->where('account_number', $request->account_number);
        }

        if (!empty($request->bank)) {
            $accounts = $accounts->where('bank_code', $request->bank);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $accounts = $accounts->whereBetween('created_at', [$from, $to]);
        }
        
        $accounts = $accounts->paginate(20);
        $gateways = PaymentGateway::all();

        $banks = getBanks();
        
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
        $this->validate($request, [
            "account_reference" => "required",
            "account_name" => "required",
            "account_number" => "required",
            "bank_name" => "required",
            "payment_gateway_id" => "required",
            "customer_email" => "required",
            "customer_id" => "required"
        ]);

        $bank_code = getBanks()->where("bank_name", $request->bank_name)->first();
        $bank_code = $bank_code->bank_code;
        $data = $request->all();
        $data['bank_code'] = $bank_code;
        $user = User::where('id', $request->customer_id)->first();
        
        AutomatedDeposit::updateOrCreate([
            'user_id' => $data["customer_id"] ?? null,
            'account_number' => $account['account_number'] ?? null,
            'account_name' => $account['account_name'] ?? null,
            'bank_name' => $account['bank_name'] ?? null,
            'bank_code' => $account['bank_code'] ?? null,
        ], [
            'user_id' => $data["customer_id"],
            'admin_id' => auth()->guard('admin')->user()->id,
            'account_reference' => $data['account_reference'],
            'account_number' => $data['account_number'],
            'account_name' => $data['account_name'],
            'bank_name' => $data['bank_name'],
            'bank_code' => $data['bank_code'],
            'payment_gateway_id' => $data['payment_gateway_id'],
            'status' => 'active',
            'purpose' => 'WALLET-FUNDING',
            'bvn' => $user->bvn,
            'raw_response' => ''
        ]);

        return back()->with('message', 'Operation Successful');
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
        $pull = app('App\Http\Controllers\PaymentGatewayController')->pullMonnifyReservedAccountByReference($request->account_reference, $customer);
        
        if ($pull['status'] == 'success') {
            return back()->with('message', 'Reserved Accounts Spooled Successfully');
        } else {
            return back()->with('error', $pull['data']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $account = AutomatedDeposit::with('deposit_transactions')->where('id', $id)->first();
        
        if ($account->payment_gateway_id == 1) {
            if (!empty($account->account_reference)) {
                $delete = app('App\Http\Controllers\PaymentGatewayController')->deleteMonnifyReservedAccount($account->account_reference);

                if ($delete['status'] == 'success') {
                    $account->delete();
                    return back()->with('message', 'Reserved Account Deleted successfully');
                } else {
                    return back()->with('error', $delete['data'] ?? 'There was an error deallocating account');
                }
            } else {
                $account->delete();
                return back()->with('message', 'Reserved Account Deleted successfully');
            }
        } else {
            $account->delete();
            return back()->with('message', 'Reserved Account Deleted successfully');
        }
    }
}