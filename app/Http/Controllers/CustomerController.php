<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Services\ExcelService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::orderby('id', 'DESC');
        
        if (!empty($request->email)) {
            $customers = $customers->where('email', 'like', $request->email);
        }
        if (!empty($request->username)) {
            $customers = $customers->where('username', 'like', $request->username);
        }

        if (!empty($request->mobile)) {
            $customers = $customers->where('mobile', 'like', $request->mobile);
        }

        if (!empty($request->level)) {
            $customers = $customers->where('level', $request->level);
        }

        if (isset($request->kyc_status)) {
            $customers = $customers->where('kyc_status', $request->kyc_status);
        }

        if (isset($request->status)) {
            $customers = $customers->where('status', $request->status);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $customers = $customers->whereBetween('created_at', [$from, $to]);
        }

        if(!empty($request->download) && $request->download == 'yes'){
            $customers = $customers->select(
                DB::raw("CONCAT(COALESCE(full_name, ''), ' ', COALESCE(middle_name, ''), ' ', COALESCE(last_name, '')) as name"),
                'email',
                'mobile',
                'level',
                'username',
                'gender',
                'created_at'
            )->get()->toArray();
                
            $headings = ['Name', 'Email', 'Phone Number', 'Level', 'Username', 'Gender', 'Joined']; 
            return ExcelService::download($customers, $headings);
        }

        $customers = $customers->paginate(20);

        $title = 'All Customers';
        $customer_levels = CustomerLevel::all();
        return view('admin.customers.index', compact('customers', 'title', 'customer_levels'));
    }

    public function activeCustomers(Request $request)
    {
        $title = 'Active Customers';
        $customers = User::orderby('id', 'DESC')->where('status', 'active');

        if (!empty($request->email)) {
            $customers = $customers->where('email', 'like', $request->email);
        }

        if (!empty($request->username)) {
            $customers = $customers->where('username', 'like', $request->username);
        }

        if (!empty($request->mobile)) {
            $customers = $customers->where('mobile', 'like', $request->mobile);
        }

        if (!empty($request->level)) {
            $customers = $customers->where('level', $request->level);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $customers = $customers->whereBetween('created_at', [$from, $to]);
        }

        $customers = $customers->paginate(20);
        $customer_levels = CustomerLevel::all();

        return view('admin.customers.index', compact('customers','title','customer_levels'));
    }

    public function inActiveCustomers(Request $request)
    {
        $title = 'InActive Customers';
        $customers = User::orderby('id', 'DESC')->where('status', 'inactive');

        if (!empty($request->email)) {
            $customers = $customers->where('email', 'like', $request->email);
        }

        if (!empty($request->username)) {
            $customers = $customers->where('username', 'like', $request->username);
        }

        if (!empty($request->mobile)) {
            $customers = $customers->where('mobile', 'like', $request->mobile);
        }

        if (!empty($request->level)) {
            $customers = $customers->where('level', $request->level);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $customers = $customers->whereBetween('created_at', [$from, $to]);
        }

        $customers = $customers->paginate(20);

        $customer_levels = CustomerLevel::all();

        return view('admin.customers.index', compact('customers', 'title', 'customer_levels'));
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
            // "bvn" => $request->bvn,
            // "nin" => $request->nin,
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
        
        if(!empty($request->product_blacklist_all)){
            $request['product_blacklist'] = Product::pluck('id')->toArray();
        }
        
        $user->update([
            'product_blacklist' => $request->product_blacklist,
        ]);
        
        return back()->with('message', 'Blacklisted Product Update succesful');
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
