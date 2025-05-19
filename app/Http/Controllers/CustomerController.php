<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KycData;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Services\ExcelService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\KycVerificationApiService;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::select('*', \DB::raw('(wallets + cashback) as total_balance'))
            ->orderByRaw("
                CASE 
                    WHEN kyc_status = 1 AND status = 'active' THEN 1 
                    ELSE 2 
                END
            ")
            ->orderByDesc('total_balance')
            ->orderByDesc('id');


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

        if (!empty($request->download) && $request->download == 'yes') {
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

        $customers = $customers->paginate(40);

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

        return view('admin.customers.index', compact('customers', 'title', 'customer_levels'));
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

    
    public function pendingKYC(Request $request)
    {
        $title = 'Pending KYC';
        $customers = User::whereHas('kycData')->orderby('id', 'DESC')->whereIn('kyc_status', [0, 2]);

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

        return view('admin.customers.pending-kyc', compact('customers', 'title'));
    }

    public function viewPendingKYC($id)
    {
        $customer = User::with('kycData')->where('id', $id)->first();
        return view('admin.customers.view-single-pending-kyc', compact('customer'));
    }

    public function updatePendingKYC(Request $request, $id)
    {
        $kyc = KycData::where('id', $id)->first();

        if (!empty($request->means_of_identification_document)) {
            $means_of_identification_document  = $this->uploadFile($request->means_of_identification_document, 'ukycs');
        } else {
            $means_of_identification_document = $kyc->means_of_identification_document;
        }

        KycData::updateOrCreate([
            'user_id' => $kyc->user_id,
        ], [
            'user_id' => $kyc->user_id,
            'bvn' => $request->bvn ?? $kyc->bvn,
            'nin' => $request->nin ?? $kyc->nin,
            'means_of_identification_document' => $means_of_identification_document,
            'means_of_identification_type' => $request->means_of_identification_type ?? $kyc->means_of_identification_type
        ]);

        return back()->with('message', 'Update successful');
    }

    public function approvePendingKYC(Request $request, $id = null)
    {
        $kyc = KycData::where('id', $id)->first();
        $user = User::where('id', $kyc->user_id)->first();
        $settings = settings();

        if ($settings->kyc_verification_cost > 0) {

            try {
                DB::beginTransaction();
                $user->lockForUpdate();

                $balance = walletBalance($user);
                $report = 'KYC Verification Charge of ' . $settings->kyc_verification_cost;
                $api_reference = $this->generateRequestId('KVC');

                $transactionData = [
                    'mobile_number' => $user->mobile,
                    'unit_price' => $settings->kyc_verification_cost,
                    'buyer_amount' => $settings->kyc_verification_cost,
                    'buyer_prebal' => $balance,
                    'buyer_postbal' => $balance - $settings->kyc_verification_cost,
                    'buyer_descr' => $report,
                    'type' => 'debit',
                    'buyer_status' => 'KYC-VERIFICATION-CHARGE',
                    'customer_status' => 'KYC-VERIFICATION-CHARGE',
                    'buyer_service' => 'KYC-VERIFICATION-CHARGE',
                    'charge_from' => 'WALLET',
                    'user_type' => $user->level,
                    'sys_ref' => $api_reference,
                    'api_ref' => $api_reference,
                    'api_report' => 'DONE',
                    'buyer_quantity' => 1,
                    'provider_id' => null,
                    'variation_id' => null,
                    'product_id' => null,
                    'discount' => 0,
                    'src_price' => 0,
                    'channel' => 'website',
                    'provider' => null,
                    'billersCode' => $user->mobile,
                    'category_id' => null,
                    'profit' => 0,
                    'api_report' => 'DONE',
                    'user_id' => $user->id,
                ];

                $this->logTransaction($transactionData);
                // log transaction

                $user->wallets -= $settings->kyc_verification_cost;
                $user->save();

                $response = [
                    'status' => 'success'
                ];

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $response = [
                    'status' => 'failed',
                    'message' => $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile(),
                ];
            }
        } else {
            $response = [
                'status' => 'success'
            ];
        }

        if ($response['status'] == 'success') {
            $data = [
                'kyc_data_id' => $kyc->id,
                'reason' => $request->reason,
                'kyc_status' => 1,
                'email' => $user->email,
                'name' => $user->name,
            ];
            $this->logKycAction($data);

            $user->update([
                'kyc_status' => 1,
            ]);

            $this->sendkycMail($data);
        }

        return redirect(route('pending.kyc'))->with('message', 'Operation Successful');
    }

    public function declinePendingKYC(Request $request, $id = null)
    {
        $kyc = KycData::where('id', $id)->first();
        $user = User::where('id', $kyc->user_id)->first();

        $data = [
            'kyc_data_id' => $kyc->id,
            'reason' => $request->reason,
            'kyc_status' => 2,
            'email' => $user->email,
            'name' => $user->name,
        ];

        $this->logKycAction($data);

        $user->update([
            'kyc_status' => 2,
        ]);

        $this->sendkycMail($data);

        return redirect(route('pending.kyc'))->with('message', 'Operation Successful');
    }

    public function logKycAction($data)
    {
        $log = new KycLogController();
        return $log->logKycAction($data);
    }

    public function sendkycMail($data)
    {
        $settings = settings();

        if ($data['kyc_status'] == 1) {
            $subject = "Your KYC Submission Has Been Approved – Welcome to Unrestricted Access!";
            $message = "Dear {$data['name']}, <br><br>
                We're pleased to inform you that your KYC (Know Your Customer) submission has been successfully approved! You can now log in to your account and begin transactions without any restrictions. <br><br>We understand the importance of your security and privacy and are committed to safeguarding your information. By completing your KYC, you have added an additional layer of protection, allowing us to serve you better and ensuring a safe and seamless experience. <br><br>Thank you for choosing {$settings->sitename} as your trusted partner. If you have any questions, please do not hesitate to reach out to us on whatsapp at: {$settings->whatsapp_no}<br><br>Best regards,<br><br>
                {$settings->sitename}
            ";
        }

        if ($data['kyc_status'] == 2) {
            $subject = "KYC Submission Update – Action Required";
            $message = "Dear {$data['name']}, <br><br>Thank you for your recent KYC submission. After careful review, we regret to inform you that your submission has not been approved.";

            if (!empty($data['reason'])) {
                $message .= "<br><br>The reason for this decision is as follows:<br><br><strong>{$data['reason']}</strong>";
            }

            $message .= "<br><br>To continue using our services, we kindly ask you to review and update your information as needed. Once updated, please resubmit your KYC documents for further review. <br><br>We value your security and privacy and are here to support you through this process. If you have any questions or need assistance with resubmission, please do not hesitate to reach out to us on whatsapp at: {$settings->whatsapp_no}<br><br>Thank you for choosing {$settings->sitename} as your trusted partner. <br><br>Best regards,<br><br>{$settings->sitename}
            ";
        }

        $this->sendEmail($data['email'], $subject, $message);

        return;
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

    public function updateCustomerAccount(Request $request, $user)
    {
        $validator =  Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $user,
            'mobile' => 'required|unique:users,mobile,' . $user,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $user = User::where('id', $user)->first();
        $user->update([
            "full_name" => $request->full_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "kyc_status" => $request->kyc_status,
            "ref_code" => $request->ref_code,
            "gender" => $request->gender,
            "status" => $request->status,
            "level" => $request->customerlevel,
            "mobile" => $request->mobile,
        ]);

        return back()->with('message', 'Update succesful');
    }

    public function updateCustomerProducts(Request $request, $user)
    {
        $user = User::where('id', $user)->first();

        if (!empty($request->product_blacklist_all)) {
            $request['product_blacklist'] = Product::pluck('id')->toArray();
        }

        $user->update([
            'product_blacklist' => $request->product_blacklist,
        ]);

        return back()->with('message', 'Blacklisted Product Update succesful');
    }

    public function updateCustomerOtherActions(Request $request)
    {
        $user = User::where('id', $request->customer)->first();

        if (!empty($request->type)) {
            if ($request->type == 'password-change') {
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

    public function customersBulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required',
            'customer_id' => 'required|array',
        ]);
        $action = $request->action;
        $customerIds = $request->customer_id;

        if ($action === 'delete') {
            User::whereIn('id', $customerIds)->delete();
            return back()->with('message', 'Selected customers have been deleted.');
        }
        
        if ($action === 'change_customer_level') {
            $this->changeCustomerLevelMass($request->all());
            return back()->with('message', 'Selected customers have been moved to level '.$request->customer_level);
        }

        // if ($action === 'activate') {
        //     Customer::whereIn('id', $customerIds)->update(['status' => 'active']);
        //     return back()->with('success', 'Selected customers have been activated.');
        // }

        // if ($action === 'deactivate') {
        //     Customer::whereIn('id', $customerIds)->update(['status' => 'inactive']);
        //     return back()->with('success', 'Selected customers have been deactivated.');
        // }

        return back()->with('error', 'Invalid action selected.');
    }

    public function changeCustomerLevelMass($request)
    {
        set_time_limit(3600);
        $customer_ids = $request['customer_id'];
        
        if (!empty($customer_ids)) {
            User::whereIn('id', $customer_ids)->update(['level' => $request['customer_level']]);
        }

        return true;
    }

    public function verifyKycData($id, $type, $value)
    {
        $kyc = KycData::where('id', $id)->first();

        if (!$kyc) {
            return back()->with('message', 'KYC record not found');
        }

        $response = KycVerificationApiService::verify($type, $value);
        $status = !empty($response) ? ($response->status ?? $response->success) : null;
        
        $existingVerificationData =  $this->deepObjectToArray($kyc->verification_data ?? []);
        if (!is_array($existingVerificationData)) {
            $existingVerificationData = json_decode($existingVerificationData, true) ?? [];
        }

        if ($status) {
            $verificationData = $existingVerificationData;

            $newRawData = isset($response->data->data) ? (array) $response->data->data : [];

            if ($type == 'nin') {
                $newRawData = isset($response->data->data) ? $this->deepObjectToArray($response->data->data) : [];

                $verificationData['nin'] = array_merge(
                    (array) ($existingVerificationData['nin'] ?? []),
                    [
                        'status' => $existingVerificationData['nin']['status'] ?? 'verified',
                        'method' => $existingVerificationData['nin']['method'] ?? 'api',
                        'date_verified' => $existingVerificationData['nin']['date_verified'] ?? Carbon::now(),
                        'raw_data' => array_merge(
                            $this->deepObjectToArray($existingVerificationData['nin']['raw_data'] ?? []),
                            $newRawData
                        ),
                    ]
                );
            }

            if (
                $type == 'bvn'
            ) {
                $newRawData = isset($response->data) ? $this->deepObjectToArray($response->data) : [];
                $verificationData['bvn'] = array_merge(
                    ($existingVerificationData['bvn'] ?? []),
                    [
                        'status' => $existingVerificationData['bvn']['status'] ?? 'verified',
                        'method' => $existingVerificationData['bvn']['method'] ?? 'api',
                        'date_verified' => $existingVerificationData['bvn']['date_verified'] ?? Carbon::now(),
                        'raw_data' => array_merge(($existingVerificationData['bvn']['raw_data'] ?? []), $newRawData),
                    ]
                );
            }

            // dd($verificationData);
            $kyc->verification_data = $verificationData;
            $kyc->save();

            return back()->with('message', 'Verification successful');
        }

        return back()->with('error', 'Verification failed, Error: ' . $response->message ?? 'N/A');
    }

    function deepObjectToArray($data)
    {
        if (is_object($data)) {
            return array_map([$this, 'deepObjectToArray'], (array) $data);
        } elseif (is_array($data)) {
            return array_map([$this, 'deepObjectToArray'], $data);
        }
        return $data;
    }
}
