<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $title = 'All Customers';
        $customers = User::orderby('id', 'DESC')->get();
        return view('admin.customers.index', compact('customers', 'title'));
    }

    public function activeCustomers()
    {
        $title = 'Active Customers';
        $customers = User::orderby('id', 'DESC')->where('status', 'active')->get();
        return view('admin.customers.index', compact('customers','title'));
    }

    public function inActiveCustomers()
    {
        $title = 'InActive Customers';
        $customers = User::orderby('id', 'DESC')->where('status', 'inactive')->get();
        return view('admin.customers.index', compact('customers', 'title'));
    }

    public function edit($id)
    {
        $customer = User::with('automated_accounts', 'transactions')->where('id', $id)->first();
        $products = Product::all();
        // $downlines = ReferralEarning::where('customer_id', $customer)
        //     ->latest()
        //     ->groupBy('referred_customer_id')
        //     ->get(['*', DB::raw('sum(amount) as total')]);
        $customerLevels = CustomerLevel::all();

        return view('admin.customers.edit', compact('customer', 'customerLevels', 'products'));
    }

    public function updateCustomerAccount(Request $request, $user){
        $user = User::where('id', $user)->first();
    
        $user->update([
            "full_name" => $request->full_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "bvn" => $request->bvn,
            "nin" => $request->nin,
            "ref_code" => $request->ref_code,
            "gender" => $request->gender,
            "status" => $request->status,
            "level" => $request->customerlevel,
            "mobile" => $request->mobile,
        ]);

        return back()->with('message', 'Update succesful');
    }

    public function updateCustomerProducts(Request $request, $user){
        $user = User::where('id', $user)->first();
        
        if(!empty($request->product_access_all)){
            $request['product_access'] = Product::pluck('id')->toArray();
        }
        
        $user->update([
            'product_access' => $request->product_access,
        ]);

        return back()->with('message', 'Update succesful');
    }

    public function updateCustomerOtherActions(Request $request){
        $user = User::where('id', $request->customer)->first();
        
        if(!empty($request->type)){
            if($request->type == 'password-change'){
                $user->update([
                    'pass' => Hash::make($request->password),
                ]);
            }
           
            if ($request->type == 'transaction-pin-change') {
                $user->update([
                    'transaction_pin' =>  base64_encode(base64_encode(base64_encode($request->transaction_pin))),
                ]);
            }

            if ($request->type == 'verify-email') {
                $user->update([
                    'email_verified_at' => Carbon::now(),
                ]);
            }

            if ($request->type == 'kyc-status') {
                $user->update([
                    'kyc_status' => $request->kyc_status,
                ]);
            }
        }

        return back()->with('message', 'Update succesful');
    }
}
