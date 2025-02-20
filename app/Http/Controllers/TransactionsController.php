<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blacklist;
use App\Models\Variation;
use App\Models\WebSetting;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Services\ExcelService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DepositTransaction;
use Illuminate\Support\Facades\DB;
use App\Services\JsonResponseService;
use App\Services\MobileResponseService;
use App\Models\TransactionStatusChangeTrack;


class TransactionsController extends Controller
{
    private $jsonresponseservice;
    
    public function __construct(){
        $this->jsonresponseservice = new JsonResponseService();
    }

    public function initialize($service){
        $productBlacklist = productBlacklist(auth()->user()); 
        $category = Category::with([
            'products' => function ($query) use ($productBlacklist)  {
                return $query->where('status', 'active')->whereNotIn('id', $productBlacklist)->get();
            }
        ])->where('status', 'active')->where('slug', $service)->first();
        
        if (\Request::is('mobile/*')) {
            return $category->products;
        }
        
        if(!$category){
            return redirect(route('dashboard'));
        }

        // return view('customer.airtime', compact('service','category'));
        return view('customer.'.$service, compact('service','category'));
    }

    public function getCustomerDiscount(Request $request){
        return response()->json(getProductDiscount($request->amount, $request->product_id,$request->quantity ?? 1));
    }

    public function getCustomerCharges(Request $request)
    {
        return response()->json(getProductCharges($request->amount, $request->product_id, $request->quantity ?? 1));
    }

    public function transactionStatus($transaction_id)
    {
        $transaction = Transactions::select(['product_id', 'variation_id', 'category_id', 'user_id', 'buyer_status','buyer_service', 'type', 'buyer_amount', 'unit_price', 'charges', 'discount', 'buyer_quantity', 'channel', 'buyer_prebal', 'buyer_postbal', 'buyer_descr', 'sys_ref', 'api_ref', 'billersCode', 'extra','created_at'])->where('id', $transaction_id)->first();

        if (\Request::is('mobile/*')) {
            return $transaction;
        }

        return view('customer.transaction_status', compact('transaction'));
    }

    public function verify(Request $request, $mobile=false){   
        $request['product'] = Product::where('id', $request->product_id)->first();
        $request['variation'] = Variation::where('id', $request->variation_id)->where('product_id', $request->product_id)->first();
        $api = $request['variation']->api;
    
        $verify = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($api))->verify($request, $api);
        $response = [];
        
        if ($request['variation']->category->slug == 'electricity') {
            $button = '<button class="btn btn-success btn-block" onclick="pay4Bills()">Make Payment</button>';
            
            $response = array(
                'status' => 'success',
                'status_code' => '200',
                'message' => $verify['message'] ?? '',
                'title' => $verify['title'] ?? '',
                'desc' => $verify['message'] ?? '',
            );
        }
    
        if ($request['variation']->category->slug == 'cable') {
            $response = array(
                'status' => $verify['status'] ?? '',
                'status_code' => $verify['status_code'] ?? '100',
                'message' => $verify['message'] ?? '',
                'title' => $verify['title'] ?? '',
                'desc' => $verify['message'] ?? '',
                'renewal_amount' => !empty($request->request_type) && $request->request_type == 'renewal' ? ($verify['renewal_amount'] ?? $request['variation']->system_price) : ($variations['price'] ?? 0),
            );
        }

        if ($request['variation']->category->slug == 'exam-epins') {
            $response = array(
                'status' => $verify['status'] ?? '',
                'status_code' => $verify['status_code'] ?? '100',
                'message' => $verify['message'] ?? '',
                'title' => $verify['title'] ?? '',
                'desc' => $verify['message'] ?? '',
                'renewal_amount' => !empty($request->request_type) && $request->request_type == 'renewal' ? ($verify['renewal_amount'] ?? $request['variation']->system_price) : ($variations['price'] ?? 0),
            );
        }

        if($mobile){
            return $verify ;
        }
        
        return response()->json($response);

    }

    // public function processBuy(Request $request)
    // {
    //     $user = auth()->user();
    //     $product_id = $request->product_id ?? $request->network_module;
    //     $product = Product::where('id', $product_id)->first();
    //     $request->variation = $request->variation ?? $request->variation_id;
    //     $var = $request->variation ?? $request->dataplanloader;

    //     $variation = Variation::where('id', $var)->where('product_id', $product->id)->first();

    //     $quantity = $request->quantity ?? 1;
    //     $request['security_pin'] = $this->updateTransactionPinToNewFormat($request);

    //     $api_reference = $this->generateRequestId();
    //     $request_id = $api_reference;
    //     $request['request_id'] = $request_id;

    //     $request['amount'] = $this->getAmount($product, $variation, $request);
    //     $request['unit_price'] = $request['amount'];
    //     $api = $variation->api ?? $product->api;

    //     if ($product->has_variations == 'yes') {
    //         if(empty($variation)){
    //             return [
    //                 'status' => 'fail',
    //                 'status_code' => '100',
    //                 'message' => 'Please select a valid variation',
    //             ];
    //         }

    //         $resource = $variation;
    //         $discount = getVariationDiscount($request['amount'], $variation->id, $request['quantity'] ?? 1);
    //     } else {
    //         $resource = $product;
    //         $discount = getProductDiscount($request['amount'], $product->id, $request['quantity'] ?? 1);
    //     }

    //     if (in_array($resource->category->slug, ['electricity', 'cable'])) {
    //         $request['billersCode'] = $request->billersCode;
    //     } else {
    //         $request['billersCode'] = $request['account_id'] ?? $request->mobile_no;
    //     }

    //     $checks = $this->runChecks($request, $product, $api, $resource);

    //     if (!empty($checks) && $checks['status'] == 'fail') {
    //         DB::rollBack();
    //         if (\Request::is('mobile/*')) {
    //             return $checks;
    //         }
    //         return $this->jsonresponseservice->build($checks);
    //     }

    //     $discountedAmount = $discount['new_amount'];
    //     $disCountApplied = $discount['discount_applied'];

    //     if ($product->category->slug == 'cable' && in_array($product->slug, ['dstv', 'gotv']) && !empty($request->renewal_amount && $request->renewal_amount > 0 && $request->request_type == 'renewal')) {
    //         $variations['price'] = $request->renewal_amount ?? $discount;
    //     }

    //     $request['quantity'] = $request->quantity ?? 1;
    //     $request['total_amount'] = $discountedAmount;
    //     $request['discount'] = $disCountApplied;

    //     $sourcePriceAndProfit = getSourcePriceAndCalculateProfit($product, $variation, $request);

    //     $balance = walletBalance($user);

    //     if ($balance < $request['total_amount']) {
    //         $response = array(
    //             'status' => 'fail',
    //             'status_code' => '100',
    //             'message' => 'Insufficient Wallet Balance, Please fund wallet and retry again',
    //         );

    //         if (\Request::is('mobile/*')) {
    //             return $response;
    //         }

    //         return $this->jsonresponseservice->build($response);
    //     }

    //     $request['package_name'] = $resource->display_name;
    //     $request['serviceId'] = $resource->slug ?? null;
    //     $request['variation_code'] = $variation->variation_code ?? null;
    //     $request['quantity'] = $quantity;

    //     // Log transaction
    //     $data = [
    //         'mobile_number' => $request->mobile_no,
    //         'unit_price' => $request['unit_price'],
    //         'buyer_amount' => $request['total_amount'],
    //         'buyer_prebal' => $balance,
    //         'buyer_postbal' => $balance - $request['total_amount'],
    //         'buyer_descr' => '',
    //         'type' => 'debit',
    //         'buyer_status' => 'INITIATED',
    //         'buyer_service' => $resource->slug,
    //         'charge_from' => 'WALLET',
    //         'user_type' => $user->level,
    //         'sys_ref' => $request_id,
    //         'api_ref' => $api_reference,
    //         'api_report' => 'PENDING',
    //         'buyer_quantity' => $request->quantity,
    //         'provider_id' => $api->id ?? null,
    //         'variation_id' => $variation->id ?? null,
    //         'product_id' => $product->id ?? null,
    //         'discount' => $disCountApplied,
    //         'src_price' => $sourcePriceAndProfit['source_price'] ?? 0,
    //         'channel' => 'website',
    //         'provider' => $api->name,
    //         'billersCode' => $request['billersCode'] ?? null,
    //         'category_id' => $resource->category->id,
    //         'profit' => $sourcePriceAndProfit['profit'] ?? 0,
    //     ];

    //     if (\Request::is('mobile/*')) {
    //         $data['channel'] = 'mobile';
    //     }

    //     $transaction = $this->logTransaction($data);

    //     $data['resource'] = $resource;
    //     $data['product'] = $product;
    //     $data['billersCode'] = $request['billersCode'] ?? null;
    //     $data['request_type'] = $request->request_type;
    //     $data['business_line'] = $request->mobile_no;
    //     $data['business_email'] = $request->business_email;
    //     $data['business_content'] = $request->business_content;
    //     $data['business_name'] = $request->business_name;

    //     DB::beginTransaction();

    //     try {

    //         $balance = walletBalance($user);
    //         if ($balance < $request['total_amount']) {
    //             $response = array(
    //                 'status' => 'fail',
    //                 'status_code' => '100',
    //                 'message' => 'Insufficient Wallet Balance, Please fund wallet and retry again',
    //             );

    //             if (\Request::is('mobile/*')) {
    //                 return $response;
    //             }

    //             return $this->jsonresponseservice->build($response);
    //         }

    //         $api_response = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($api))->query($data, $api);

    //         if ($api_response['status'] == 'success') {
    //             $report = $this->formatOderReport($resource, $request, $transaction, $api_response);
    //             $commission = getAndUpdateReferralCommission(auth()->user(), $product, $variation, $transaction);

    //             $transaction->update([
    //                 'buyer_status' => 'SUCCESSFUL',
    //                 'buyer_descr' => $report['order_report'],
    //                 'buyer_postbal' => $balance - $request['total_amount'],
    //                 'api_response' => $api_response['api_response'],
    //                 'api_report' => 'DONE',
    //                 'api_status' => $api_response['status'],
    //                 'extra' => $api_response['extra'] ?? null,
    //                 'post_fields' => $api_response['postfields'],
    //                 'commission' => $commission
    //             ]);

    //             $order_report = $report['order_report'] ?? '';
    //             $final_response = $report['final_response'];

    //             $body = 'Dear ' . auth()->user()->full_name . '<br> The transaction below was carried out on your account on ' . config('app.name') . '<br><br>
    //             Amount Charged : ₦' . number_format($transaction->buyer_amount) . '<br>
    //             Previous Balance : ₦' . number_format($transaction->buyer_prebal) . '<br>
    //             Current Balance : ₦' . number_format($transaction->buyer_postbal) . '<br>
    //             Description : ' . $order_report . '<br>
    //             Transaction Reference : ' . $transaction->sys_ref . '<br>
    //             Transaction Date : ' . $transaction->created_at . '<br>
    //             Transaction Status : Success<br>';

    //             if (isset($api_response['extra']) && !empty($api_response['extra'])) {
    //                 $body .= 'Extra Information : ' . $api_response['extra'];
    //             }

    //             $body .= '<br><br><br>Warm Regards<br>' . config('app.name');
    //             $subject = "Transaction Notification";
    //         } else if ($api_response['status'] == 'attention-required') {
    //             $report = $this->formatOderReport($resource, $request, $transaction, $api_response);
    //             $order_report = $report['order_report'] ?? '';
    //             $final_response = $report['final_response'];

    //             $transaction->update([
    //                 'buyer_status' => 'ATTENTION-REQUIRED',
    //                 'buyer_descr' => $order_report,
    //                 'buyer_postbal' => $balance - $request['total_amount'],
    //                 'api_response' => $api_response['api_response'],
    //                 'api_report' => 'pending',
    //                 'failure_reason' => $api_response['error'],
    //                 'extra' => $api_response['extra'] ?? null,
    //                 'post_fields' => $api_response['postfields'],
    //             ]);

    //             $body = 'Dear ' . auth()->user()->full_name . ' The transaction below was carried out on your account on ' . config('app.name') . '<br><br>
    //             Amount Charged : ' . number_format($transaction->buyer_amount) . '<br>
    //             Previous Balance : ' . number_format($transaction->buyer_prebal) . '<br>
    //             Current Balance : ' . number_format($transaction->buyer_postbal) . '<br>
    //             Description : ' . $transaction->$order_report . '<br>
    //             Transaction Reference : ' . $transaction->sys_ref . '<br>
    //             Transaction Date : ' . $transaction->created_at . '<br>
    //             Extra Information : ' . $transaction->created_at . '<br><br><br>Warm Regards<br>' . config('app.name') . '
    //             Transaction Status : Pending<br>';
    //             $subject = "Transaction Notification";
    //         } else if ($api_response['status'] == 'failed') {
    //             $report = $this->formatOderReport($resource, $request, $transaction, $api_response);

    //             $order_report = $report['order_report'];
    //             $final_response = $report['final_response'];

    //             $transaction->update([
    //                 'buyer_status' => 'FAILED',
    //                 'buyer_postbal' => $balance,
    //                 'post_fields' => $api_response['postfields'],
    //                 'buyer_descr' => $order_report,
    //                 'buyer_postbal' => $transaction->buyer_prebal,
    //                 'api_response' => $api_response['api_response'],
    //                 'api_report' => 'FAILED',
    //                 'failure_reason' => $api_response['error'] ?? ($api_response['response'] ?? 'unknown error'),
    //             ]);

    //             $body = 'Dear ' . auth()->user()->full_name . ' The transaction below failed on your account on ' . config('app.name') . '<br><br>
    //             Amount Charged : ' . number_format($transaction->buyer_amount) . '<br>
    //             Previous Balance : ' . number_format($transaction->buyer_prebal) . '<br>
    //             Current Balance : ' . number_format($transaction->buyer_postbal) . '<br>
    //             Description : ' . $transaction->$order_report . '<br>
    //             Transaction Reference : ' . $transaction->sys_ref . '<br>
    //             Transaction Date : ' . $transaction->created_at . '<br>
    //             Transaction Status : Failed<br>';
    //             $subject = "Transaction Notification";
    //         }

    //         $user->wallets = $transaction->buyer_postbal;
    //         $user->save();

    //         if (in_array('transaction-email', settings()->allowed_email_notifications)) {
    //             $this->sendEmail(auth()->user()->email, $subject, $body);
    //         }

    //         DB::commit();

    //         $final_response['id'] = $transaction->id;
    //         $final_response['system_reference'] = $transaction->sys_ref;

    //         if (\Request::is('mobile/*')) {
    //             return $final_response;
    //         }

    //         return response()->json($final_response);
    //     } catch (\Exception $e) {

    //         DB::rollBack();
    //         $final_response = array(
    //             'status' => 'fail',
    //             'status_code' => '100',
    //             'old_balance' => $transaction->buyer_prebal, 
    //             'new_balance' => $transaction->buyer_postbal, 
    //             'amountPaid' => $transaction->buyer_amount,
    //             'message' => 'A File Transaction error occured, please try again later',
    //         );

    //         \Log::error('Transaction failed: ' . $e->getMessage().' File: '. $e->getFile() .' Line: ' . $e->getLine());

    //         if (\Request::is('mobile/*')) {
    //             return $final_response;
    //         }

    //         return response()->json($final_response);
    //     }
    // }

    public function processBuy(Request $request)
    {
        DB::beginTransaction();
        try {
            // Authenticate the user
            $user = auth()->user();

            // Lock user record for updates
            $user->lockForUpdate();
            
            // Initialize product and variation information
            $product_id = $request->product_id ?? $request->network_module;
            $product = Product::where('id', $product_id)->first();
            $request->variation = $request->variation ?? $request->variation_id;
            $var = $request->variation ?? $request->dataplanloader;

            $variation = Variation::where('id', $var)->where('product_id', $product->id)->first();

            $quantity = $request->quantity ?? 1;
            $request['security_pin'] = $this->updateTransactionPinToNewFormat($request);

            $api_reference = $this->generateRequestId();
            $request_id = $api_reference;
            $request['request_id'] = $request_id;

            $request['amount'] = $this->getAmount($product, $variation, $request);
            $request['unit_price'] = $request['amount'];
            $api = $variation->api ?? $product->api;

            if ($product->has_variations == 'yes') {
                if(empty($variation)){
                    return [
                        'status' => 'fail',
                        'status_code' => '100',
                        'message' => 'Please select a valid variation',
                    ];
                }

                $resource = $variation;
                $discount = getVariationDiscount($request['amount'], $variation->id, $request['quantity'] ?? 1);
            } else {
                $resource = $product;
                $discount = getProductDiscount($request['amount'], $product->id, $request['quantity'] ?? 1);
            }

            if (in_array($resource->category->slug, ['electricity', 'cable'])) {
                $request['billersCode'] = $request->billersCode;
            } else {
                $request['billersCode'] = $request->billersCode ?? ($request['account_id'] ?: $request->mobile_no);
            }
            
            $checks = $this->runChecks($request, $product, $api, $resource);

            if (!empty($checks) && $checks['status'] == 'fail') {
                DB::rollBack();
                if (\Request::is('mobile/*')) {
                    return $checks;
                }
                return $this->jsonresponseservice->build($checks);
            }

            $discountedAmount = $discount['new_amount'];
            $disCountApplied = $discount['discount_applied'];

            if ($product->category->slug == 'cable' && in_array($product->slug, ['dstv', 'gotv']) && !empty($request->renewal_amount && $request->renewal_amount > 0 && $request->request_type == 'renewal')) {
                $variations['price'] = $request->renewal_amount ?? $discount;
            }

            $request['quantity'] = $request->quantity ?? 1;
            $request['total_amount'] = $discountedAmount;
            $request['discount'] = $disCountApplied;

            $sourcePriceAndProfit = getSourcePriceAndCalculateProfit($product, $variation, $request);

            $balance = walletBalance($user);

            if ($balance < $request['total_amount']) {
                $response = array(
                    'status' => 'fail',
                    'status_code' => '100',
                    'message' => 'Insufficient Wallet Balance, Please fund wallet and retry again',
                );

                if (\Request::is('mobile/*')) {
                    return $response;
                }

                return $this->jsonresponseservice->build($response);
            }

            $request['package_name'] = $resource->display_name;
            $request['serviceId'] = $resource->slug ?? null;
            $request['variation_code'] = $variation->variation_code ?? null;
            $request['quantity'] = $quantity;
            //end intialization

            $data = [
                'mobile_number' => $request->mobile_no,
                'unit_price' => $request['unit_price'],
                'buyer_amount' => $request['total_amount'],
                'buyer_prebal' => $balance,
                'buyer_postbal' => $balance - $request['total_amount'],
                'buyer_descr' => '',
                'type' => 'debit',
                'buyer_status' => 'INITIATED',
                'buyer_service' => $resource->slug,
                'charge_from' => 'WALLET',
                'user_type' => $user->level,
                'sys_ref' => $request_id,
                'api_ref' => $api_reference,
                'api_report' => 'PENDING',
                'buyer_quantity' => $request->quantity,
                'provider_id' => $api->id ?? null,
                'variation_id' => $variation->id ?? null,
                'product_id' => $product->id ?? null,
                'discount' => $disCountApplied,
                'src_price' => $sourcePriceAndProfit['source_price'] ?? 0,
                'channel' => 'website',
                'provider' => $api->name,
                'billersCode' => $request['billersCode'] ?? null,
                'category_id' => $resource->category->id,
                'profit' => $sourcePriceAndProfit['profit'] ?? 0,
            ];

            if (\Request::is('mobile/*')) {
                $data['channel'] = 'mobile';
            }


            // Log transaction before calling the API
            $transaction = $this->logTransaction($data);

            $data['resource'] = $resource;
            $data['product'] = $product;
            $data['billersCode'] = $request['billersCode'] ?? null;
            $data['request_type'] = $request->request_type;
            $data['business_line'] = $request->mobile_no;
            $data['business_email'] = $request->business_email;
            $data['business_content'] = $request->business_content;
            $data['business_name'] = $request->business_name;

            // Deduct wallet balance and save
            $user->wallets -= $request['total_amount'];
            $user->save();

            // Commit the wallet deduction and transaction log
            DB::commit();

            // API call and further processing
            DB::beginTransaction();

            // Initiate the API call with updated transaction data
            // $api_response = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($data, $api))
            // ->query($data);
            $api_response = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($api))->query($data, $api);
            
            // Handle API response
            if ($api_response['status'] === 'success') {
                $report = $this->formatOderReport($product, $request, $transaction, $api_response);
                $commission = getAndUpdateReferralCommission($user, $product, $variation, $transaction);

                $transaction->update([
                    'buyer_status' => 'SUCCESSFUL',
                    'buyer_descr' => $report['order_report'],
                    'buyer_postbal' => $user->wallets,
                    'api_response' => $api_response['api_response'],
                    'api_report' => 'DONE',
                    'api_status' => $api_response['status'],
                    'extra' => $api_response['extra'] ?? null,
                    'post_fields' => $api_response['postfields'],
                    'commission' => $commission
                ]);

                $order_report = $report['order_report'] ?? '';
                $final_response = $report['final_response'];

                $body = 'Dear ' . auth()->user()->full_name . '<br> The transaction below was carried out on your account on ' . config('app.name') . '<br><br>
                Amount Charged : ₦' . number_format($transaction->buyer_amount) . '<br>
                Previous Balance : ₦' . number_format($transaction->buyer_prebal) . '<br>
                Current Balance : ₦' . number_format($transaction->buyer_postbal) . '<br>
                Description : ' . $order_report . '<br>
                Transaction Reference : ' . $transaction->sys_ref . '<br>
                Transaction Date : ' . $transaction->created_at . '<br>
                Transaction Status : Success<br>';

                if (isset($api_response['extra']) && !empty($api_response['extra'])) {
                    $body .= 'Extra Information : ' . $api_response['extra'];
                }

                $body .= '<br><br><br>Warm Regards<br>' . config('app.name');
                $subject = "Transaction Notification";
            } elseif ($api_response['status'] === 'attention-required') {
                $report = $this->formatOderReport($resource, $request, $transaction, $api_response);
                $order_report = $report['order_report'] ?? '';
                $final_response = $report['final_response'];

                $transaction->update([
                    'buyer_status' => 'ATTENTION-REQUIRED',
                    'buyer_descr' => $order_report,
                    'buyer_postbal' => $balance,
                    'api_response' => $api_response['api_response'],
                    'api_report' => 'pending',
                    'failure_reason' => $api_response['error'] ?? 'attention-required',
                    'extra' => $api_response['extra'] ?? null,
                    'post_fields' => $api_response['postfields'],
                ]);

                $body = 'Dear ' . auth()->user()->full_name . ' The transaction below was carried out on your account on ' . config('app.name') . '<br><br>
                Amount Charged : ' . number_format($transaction->buyer_amount) . '<br>
                Previous Balance : ' . number_format($transaction->buyer_prebal) . '<br>
                Current Balance : ' . number_format($transaction->buyer_postbal) . '<br>
                Description : ' . $transaction->$order_report . '<br>
                Transaction Reference : ' . $transaction->sys_ref . '<br>
                Transaction Date : ' . $transaction->created_at . '<br>
                Extra Information : ' . $transaction->created_at . '<br><br><br>Warm Regards<br>' . config('app.name') . '
                Transaction Status : Pending<br>';

                $subject = "Transaction Notification";
            } elseif ($api_response['status'] === 'failed') {
                $report = $this->formatOderReport($resource, $request, $transaction, $api_response);
                $order_report = $report['order_report'];
                $final_response = $report['final_response'];
                
                // Handle API failure
                $transaction->update([
                    'buyer_status' => 'FAILED',
                    'buyer_postbal' => $balance,
                    'post_fields' => $api_response['postfields'],
                    'buyer_descr' => $order_report,
                    'api_response' => $api_response['api_response'],
                    'api_report' => 'FAILED',
                    'failure_reason' => $api_response['error'] ?? ($api_response['response'] ?? 'unknown error'),
                ]);

                // Roll back the wallet deduction
                $user->wallets += $request->total_amount;
                $user->save();

                $body = 'Dear ' . $user->full_name . ' The transaction below failed on your account on ' . config('app.name') . '<br><br>
                Amount Charged : ' . number_format($transaction->buyer_amount) . '<br>
                Previous Balance : ' . number_format($transaction->buyer_prebal) . '<br>
                Current Balance : ' . number_format($transaction->buyer_postbal) . '<br>
                Description : Transaction failed<br>
                Transaction Reference : ' . $transaction->sys_ref . '<br>
                Transaction Date : ' . $transaction->created_at . '<br>
                Transaction Status : Failed<br>';
                $subject = "Transaction Notification";

                $final_response['id'] = $transaction->id;
                $final_response['system_reference'] = $transaction->sys_ref;

            } 

            // Commit API transaction
            DB::commit();
            
            if (in_array('transaction-email', settings()->allowed_email_notifications)) {
                $this->sendEmail(auth()->user()->email, $subject, $body);
            }
            
            if (\Request::is('mobile/*')) {
                return $final_response;
            }

            return response()->json($final_response);
        } catch (\Exception $e) {
            DB::rollBack();

            $transaction->update([
                'failure_reason' => 'Transaction failed: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine(),
                'buyer_status' => 'ATTENTION-REQUIRED',
            ]);

            $final_response = array(
                'status' => 'fail',
                'status_code' => '100',
                'old_balance' => $transaction->buyer_prebal, 
                'new_balance' => $transaction->buyer_postbal, 
                'amountPaid' => $transaction->buyer_amount,
                'message' => 'A File Transaction error occured, please try again later',
            );

            \Log::error('Transaction failed: ' . $e->getMessage().' File: '. $e->getFile() .' Line: ' . $e->getLine());

            if (\Request::is('mobile/*')) {
                return $final_response;
            }

            return response()->json($final_response);
        }
    }

    public function initializeAirtime2CashTransaction(Request $request)
    {
        $product = Product::where('id', $request->product)->first();
        $variation = null;
    
        if (empty($product)) {
            return back()->with('message', 'The selected product/service does not seem to exist, kindly check your selection');
        }

        $user = auth()->user();
        $quantity = $request->quantity ?? 1;
        
        $request['security_pin'] = $this->updateTransactionPinToNewFormat($request);

        $sys_reference = $this->generateRequestId('SWFTEXC');
        $api_reference = $sys_reference;

        $request['amount'] = $this->removeCharsInAmount($request->amount);

        $request['unit_price'] = $request['amount'];
        $api = $product->api;

        if ($product->has_variations == 'yes') {
            $resource = $variation;
            $src_price = $variation->api_price * ($request['quantity'] ?? 1);
            $discount = getProductCharges($request['amount'], $variation->id, $request['quantity'] ?? 1);
        } else {
            $resource = $product;
            $src_price = !empty($product->system_price) ? $product->system_price * ($request['quantity'] ?? 1) : 0;
            $discount = getProductCharges($request['amount'], $product->id, $request['quantity'] ?? 1);
        }
        
        // checks
        $request['billersCode'] = $request->mobile_no;
        $checks = $this->runChecks($request, $product, $api);
        
        if (!empty($checks) && $checks['status'] == 'fail') {
            return $this->jsonresponseservice->build($checks);
        }

        $request['quantity'] = $request->quantity ?? 1;
        $request['total_amount'] = $request->amount;
        $amount_to_pay = $request->amount;
        $amount_to_recieve = $request->amount - $discount['charge'];

        $string = 'Hello Admin, I want to convert Airtime to cash on ' . config("app.name") . '. Please find below details of the transaction below: 
        
        *Name:* ' . $user->full_name . '
        *Charge:* ' .  settings()->currency . number_format($discount['charge']) . '
        *Amount to Receive:* ' . settings()->currency . number_format($amount_to_recieve, 2) . '
        *Total Credit to transfer:* ' . settings()->currency . number_format($amount_to_pay, 2) . '
        *Phone Number:* ' . $request->mobile_no . '
        *Network:* ' . $product->name . '
        *Transaction Date:* ' . date("M jS, Y g:iA", strtotime(now())) . '';
        
        $string = "https://api.whatsapp.com/send?phone=" . settings()->whatsapp_no . "&text=" . urlencode($string);
       
        return redirect()->away($string);
    }


    public function updateTransactionPinToNewFormat($request){
        $user = auth()->user();
        if(empty($user->transaction_pin)){
            // check if current pin matched
            if($request->security_pin == $user->security_pin){
                $user->transaction_pin = base64_encode(base64_encode(base64_encode($request->security_pin)));
                $user->save();
            }
        }
        
        return base64_encode(base64_encode(base64_encode($request->security_pin)));
    }

    public function formatOderReport($resource, $request, $transaction,$api_response){
        $api_resX = null;

        if (isset($api_response['code'])) {
            if (!in_array($api_response['code'], ['018', '011', '012', '013', '014', '015', '017', '019', '027', '028'])) {
                $api_resX = '. ERROR: '. $api_response['response'] ?? null;
            }else{
                $api_resX = 'Please Contact Admin. CODE:'. $api_response['code'];
            }
        }

        if ($api_response['status'] == 'success') {
            $order_report = strtoupper('Purchase Successful on ' . $request['billersCode']) .'. '. $api_response['response'];
            $extra = $api_response['extra'] ?? '';
            
            if ($resource->slug == 'smile-data') {
                $order_report = strtoupper($resource->name. settings()->currency . $transaction->buyer_amount . ' Purchase Successful on ' . $request['billersCode']);
            } elseif ($resource->slug == 'spectranet-data') {
                $order_report = strtoupper($resource->name. settings()->currency . $transaction->buyer_amount . ' Purchase Successful on ' . $request['billersCode'] . ' [' . $extra . ']');
            } 

            $final_response = [
                'status' => 'success',
                'status_code' => '200',
                'old_balance' => $transaction->buyer_prebal, ////// 
                'new_balance' => $transaction->buyer_postbal, ////// 
                'amountPaid' => $transaction->buyer_amount,
                'message' => $order_report,
                'extra' => $api_response['extra'] ?? null,
            ];
        }else if($api_response['status'] == 'attention-required'){
            $order_report = strtoupper('Purchase successful on ' .$request['billersCode'] ?? $resource->system_name . ' on ' . $request['billersCode']);
            $final_response = [
                'status' => 'success',
                'status_code' => '200',
                'old_balance' => $transaction->buyer_prebal, ////// 
                'new_balance' => $transaction->buyer_postbal, ////// 
                'amountPaid' => $transaction->buyer_amount,
                'message' => $order_report,
            ];
        }else{
            if(!empty($api_resX)){
                $order_report = strtoupper('Purchase failed on ' .$request['billersCode'] . $api_resX);
            }else{
                $order_report = strtoupper('Purchase failed on ' . $request['billersCode'] ?? $resource->name);
            }

            if ($resource->slug == 'smile-data') {
                $order_report = strtoupper(settings()->currency . $transaction->buyer_amount . ' Purchase failed on ' . $request['billersCode']) . '. ' . $api_resX ?? '';
            } elseif ($resource->slug == 'spectranet-data') {
                $order_report = strtoupper(settings()->currency . $transaction->buyer_amount . ' E-pin Purchase failed on ' . $request['billersCode']) . '. ' . $api_resX ?? '';
            }

            $final_response = array(
                'status' => 'fail',
                'status_code' => '100',
                'old_balance' => $transaction->buyer_prebal, ////// 
                'new_balance' => $transaction->buyer_postbal, ////// 
                'amountPaid' => $transaction->buyer_amount,
                'message' => $order_report
            );
        }
        
        return [
            'order_report' => $order_report,
            'final_response' => $final_response
        ];
        
    }

    public function removeCharsInAmount($code)
    {
        $chars = str_split($code);
        $str2 = "";
        foreach ($chars as $char) {
            if ($char != '#') {
                $str2 .= $char;
            }
        }
        $code = trim(preg_replace('/[^0-9\.]+/i', '', $str2));
        return $code;
    }

    // public function discounts(Request $request)
    // {
    //     $webSetting = webSetting::first(); 
    //     $service = strtolower($request->services);
    //     $discount = 0;
    //     if ($service == "mtn-vtu") {
    //         $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->mtn_discount;
    //     }

    //     if ($service == "glo-vtu") {
    //         $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->glo_discount;
    //     }

    //     if ($service == "airtel-vtu") {
    //         $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->airtel_discount;
    //     }

    //     if ($service == "9mobile-vtu") {
    //         $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->mobile_discount;
    //     }
    
    //     if ($service == "smile-vtu") {
    //         $discount = $webSetting->smile_vtu_discount;
    //     }
    //     ///AIRTIME VTU-END

    //     //AIRTIME SNS-START

    //     if ($service == "mtn-sns") {
    //         $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->mtn_discount;
    //     }

    //     if ($service == "glo-sns") {
    //         $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->glo_discount;
    //     }

    //     if ($service == "airtel-sns") {
    //         $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->airtel_discount;
    //     }

    //     if ($service == "9mobile-sns") {
    //         $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->mobile_discount;
    //     }

    //     //AIRTIME SWAP-START
    //     if ($service == "mtn-swap") {
    //         $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->mtn_rate;
    //     }

    //     if ($service == "glo-swap") {
    //         $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->glo_rate;
    //     }

    //     if ($service == "airtel-swap") {
    //         $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->airtel_rate;
    //     }

    //     if ($service == "9mobile-swap") {
    //         $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->mobile_rate;
    //     }

    //     // Electricity
    //     if ($service == "aba-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->aba_discount;
    //     }

    //     if ($service == "abuja-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->abuja_discount;
    //     }

    //     if ($service == "eko-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->eko_discount;
    //     }

    //     if ($service == "ibadan-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->ibadan_discount;
    //     }
        
    //     if ($service == "ikeja-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->ikeja_discount;
    //     }

    //     if ($service == "kaduna-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->kaduna_discount;
    //     }

    //     if ($service == "kano-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->kano_discount;
    //     }

    //     if ($service == "jos-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->jos_discount;
    //     }

    //     if ($service == "portharcourt-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->portharcourt_discount;
    //     }

    //     if ($service == "benin-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->benin_discount;
    //     }

    //     if ($service == "enugu-electric") {
    //         $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->enugu_discount;
    //     }
    
    //     // Gotv
    //     if(!empty($request->ajax)){
    //         // $echo = '<option value="">xdvxc</option>';
    //         $echo = $this->reFormatDiscount($service, $discount);
    //         return $echo;
    //         // return response()->json($this->reFormatDiscount($service, $discount));
    //         // return response()->json($discount);
    //     }else{
    //         return $discount;
    //     }
    // }

    // public function reFormatDiscount($getservices, $discount){
    //     if(empty($discount) || $discount < 1){
    //         $message = '';
    //     }
    //     if ($getservices == "abuja-electric") {
    //         if ($discount > 0) {
    //             $message = '<div class="alert alert-success alert-dismissible"><strong class="discount-text">' . $discount . '% Discount applies for you</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>';
    //         }
    //     }

    //     if ($getservices == "ibadan-electric") {
    //         if ($discount > 0) {
    //             $message =  '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "eko-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //             <div class="alert alert-success alert-dismissible">
    //                 <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                 <button type="button" class="btn-close" 
    //                 data-bs-dismiss="alert"
    //                 aria-label="Close">
    //                 </button>
    //                 </div>
    //             </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "jos-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "kaduna-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "kano-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }
        
    //     if ($getservices == "ikeja-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "portharcourt-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "benin-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "enugu-electric") {
    //         if ($discount > 0) {
    //             $message = '
    //                 <div class="alert alert-success alert-dismissible">
    //                     <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                     <button type="button" class="btn-close" 
    //                     data-bs-dismiss="alert"
    //                     aria-label="Close">
    //                     </button>
    //                     </div>
    //                 </div>
    //             ';
    //         }
    //     }

    //     if ($getservices == "aba-electric") {
    //         if ($discount > 0) {
    //             $message =  '
    //             <div class="alert alert-success alert-dismissible">
    //                 <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
    //                 <button type="button" class="btn-close" 
    //                 data-bs-dismiss="alert"
    //                 aria-label="Close">
    //                 </button>
    //                 </div>
    //             </div>
    //         ';
    //         }
    //     }
    //     return $message;

    // }

    public function commissions($type, $product){
        $product = strtoupper($product);
        $webSetting = WebSetting::first();
        $service_comm = 0;
        //DATA COMMISSION
        if ($type == 'data' && ($product == "MTN")) {
            $service_comm = $webSetting->mtn_discount;
        }

        if ($type == 'data' && ($product == "GLO")) {
            $service_comm = $webSetting->gdata_comm;
        }

        // AIRTIME COMMISSION
        if ($type == 'airtime' && ($product == "MTN-VTU" || $product == "MTN")) {
            $service_comm = $webSetting->mair_comm;
        }

        if ($type == 'airtime' && ($product == "GLO-VTU" || $product == "GLO")) {
            $service_comm = $webSetting->gair_comm;
        }

        if ($type == 'airtime' && ($product == "AIRTEL-VTU" || $product == "AIRTEL")) {
            $service_comm = $webSetting->aair_comm;
        }

        if ($type == 'airtime' && ($product == "9MOBILE-VTU" || $product == "9MOBILE" || $product == "ETISALAT-VTU" || $product == "ETISALAT")) {
            $service_comm = $webSetting->moair_comm;
        }

        return $service_comm;
    }

    // work on this later when working on apis
    function getPlanId($plan_name, $network, $plan_id)
    {
        $pId = $plan_id;
        $type = "data";
        $product = strtolower($network);

        if ($network == "MTN") {
            $api = $api_mtn;
        } else if ($network == "MTN-DATACARD") {
            $api = $mtn_data_card;
        } else if ($network == "MTN-CG" || $network == "GIFTING") {
            $product = "mtn-cg";
            $api = $api_gift;
        } else if ($network == "9MOBILE" || $network == "ETISALAT") {
            $api = $api_mobile;
        } else if ($network == "AIRTEL") {
            $api = $api_airtel;
        } else if ($network == "AIRTEL-CG") {
            $product = 'airtel-cg';
            $api = $api_airtelcg;
        } else if ($network == "GLO") {
            $api = $api_glo;
        } else if ($network == "GLO-CG") {
            $api = $api_glocg;
        } else if ($network == "9MOBILE-CG" || $network == "ETISALAT") {
            $api = $api_mobile;
        } else if ($network == "MOBILE-SME" || $network == "MOBILE SME") {
            $api = $api_mobilesme;
            $type = "sme";
            $product = "apimobile-sme";
        }

        if ($api == 'easy_access_data.php') {
            require("easy_access_pricing.php");
        } else if ($api == 'gongzo_data.php') {
            require("gongzo_pricing.php");
        } else if ($api == 'gongzo.php') {
            require("gongzo_pricing.php");
        } else {
            require("generic_pricing.php");
        }

        if (isset($response) && !empty($response)) {
            foreach ($response as $res) {
                if (isset($res['name']) && !empty($res['name'])) {
                    $needle = str_replace(' ', '', $res['name']);
                    $haystack = str_replace(' ', '', $plan_name);
                    // check if name matches plan_name
                    if (strpos($haystack, $needle) !== false) {
                        $pId = $res['code'];
                    }
                }
            }
        }
        return $pId;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Transactions $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transactions $transactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transactions $transactions)
    {
        //
    }

    function debitCustomerPage()
    {
        $users = User::select('id', 'username', 'full_name', 'email')->get();
        return view('admin.transactions.debit_customer', compact('users'));
    }

    function creditCustomerPage()
    {
        $users = User::select('id','username','full_name','email')->get();
        return view('admin.transactions.credit_customer', compact('users'));
    }

    function processCreditDebit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'type' => 'required',
            'reason' => 'required'
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Lock the user row for update
            $user = User::where('email', $request->email)->lockForUpdate()->first();

            if (!$user) {
                DB::rollBack();
                return back()->with('error', 'Account not found!');
            }

            $amount = $request->amount;
            $currAmount = walletBalance($user);

            if ($request->type == 'credit') {
                $balance_after = $currAmount + $amount;
            } else {
                $balance_after = $currAmount - $amount;
            }

            $requestId = $this->generateRequestId();
            $reason = $request->type == 'debit' ? 'ADMIN-DEBIT' : 'ADMIN-CREDIT';

            $reason = $reason . ' was successful';

            $data['mobile_number'] = $user->mobile;
            $data['buyer_email'] = $user->email;
            $data['unit_price'] = $amount;
            $data['buyer_amount'] = $amount;
            $data['buyer_prebal'] = $currAmount;
            $data['buyer_postbal'] = $balance_after;
            $data['buyer_descr'] = $reason;
            $data['buyer_status'] = 'SUCCESSFUL';
            $data['buyer_service'] = 'ADMIN-' . strtoupper($request->type);
            $data['charge_from'] = 'WALLET';
            $data['sys_ref'] = $requestId;
            $data['api_ref'] = $requestId;
            $data['api_report'] = 'DONE';
            $data['buyer_quantity'] = 1;
            $data['discount'] = 0;
            $data['src_price'] = 0;
            $data['channel'] = 'website';
            $data['provider'] = null;
            $data['billersCode'] = 'WALLET';
            $data['profit'] = 0;
            $data['type'] = strtolower($request->type);
            $data['user_id'] = $user->id;
            $data['api_response'] = $request->reason;

            $currency = settings()->currency;

            $transaction = $this->logTransaction($data);

            $this->updateWalletBalance($user, $amount, $request->type);

            // Commit the transaction
            DB::commit();

            return back()->with('message', "Customer wallet has been {$request->type}ed with {$currency}" . number_format($amount));
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
        }
    }

    public function adminTransactions(Request $request){
        // $transactions = Transactions::with('customer:id,full_name,middle_name,last_name,email,username,level,mobile', 'product:id,display_name', 'variation:id,system_name')->orderBy('resolved_transaction_id')->latest();

        $transactions = Transactions::with(
            'customer:id,full_name,middle_name,last_name,email,username,level,mobile',
            'product:id,display_name',
            'variation:id,system_name'
        )
        ->orderBy('created_at','DESC')
        ->orderByRaw('CASE WHEN resolved_transaction_id IS NULL THEN 1 ELSE 0 END, resolved_transaction_id');

        $products = Product::all();
        
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            
            if(!empty($user)){
                $transactions = $transactions->where('user_id', $user->id);
            }
        }

        if ($request->phone) {
            $transactions = $transactions->where('customer_phone', $request->phone);
        }

        if ($request->service) {
            if(is_numeric($request->service)){
                $transactions = $transactions->where('product_id', $request->service);
            }else{
                $transactions = $transactions->where('buyer_service', $request->service);
            }
        }

        if ($request->sys_reference) {
            $transactions = $transactions->where('sys_ref', $request->sys_reference)->orwhere('api_ref', $request->sys_reference);
        }

        if ($request->billersCode) {
            $transactions = $transactions->where('billersCode', $request->billersCode);
        }

        if ($request->status) {
            $transactions = $transactions->where('buyer_status', $request->status);
        }

        if ($request->from && $request->to) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';
            $transactions = $transactions->whereBetween('created_at', [$from, $to]);
        }

        if ($request->channel) {
            $transactions = $transactions->where('channel', $request->channel);
        }

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }
        
        if ($request->download && $request->download == 'on') {
            $transactions = $transactions->get();
            $data = [];
            
            foreach($transactions as $transaction){
                if(!empty($transaction->customer)){
                    $data[] = [
                        'Full Name' => $transaction->customer->full_name . ' ' . $transaction->customer->middle_name . ' ' . $transaction->customer->last_name,
                        'Email' => $transaction->customer->email,
                        'Mobile' => $transaction->mobile_number,
                        'Username' => $transaction->customer->username,
                        'Biller' => $transaction->customer->billersCode,
                        'Level' => $transaction->customer->level,
                        'Status' => $transaction->buyer_status,
                        'Sys Ref' => $transaction->sys_ref,
                        'Type' => $transaction->type,
                        'Service' => $transaction->product?->display_name,
                        'Variation' => $transaction->variation?->system_name,
                        'Quantity' => $transaction->buyer_quantity,
                        'Unit Price' => $transaction->unit_price,
                        'Discount' => $transaction->discount,
                        'Total Amount' => $transaction->total_amount,
                        'Buyer Prebal' => $transaction->buyer_prebal,
                        'Buyer Postbal' => $transaction->buyer_postbal,
                        'Buyer Descr' => $transaction->buyer_descr,
                        'Charge From' => $transaction->charge_from,
                        'Date' =>  date("M jS, Y g:iA", strtotime($transaction->created_at)),
                    ];
                }
            }
            
            $headings = ['Name', 'Email', 'Phone Number', 'Username', 'Biller Code','Level','Status','Sys Ref','Type','Service ','Variation','Quantity','Unit Price','Discount','Total Amount', 'Pre Balance','Post Balance'];
            return ExcelService::download($data, $headings);
        }

        $count = 1;
        $transactions = $transactions->paginate(50)->withQueryString();;

        return view('admin.transactions.index', compact('transactions','products','count'));
    }

    public function adminDepositTransactions(Request $request)
    {
        $transactions = DepositTransaction::latest();
        
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            $transactions = $transactions->where('user_id', $user->id);

            if (!empty($user)) {
                $transactions = $transactions->where('user_id', $user->id);
            }
        }

        if ($request->payment_gateway_id) {
            $transactions = $transactions->where('payment_gateway_id', $request->payment_gateway_id);
        }

        if ($request->sys_reference) {
            $transactions = $transactions->where('sys_ref', $request->sys_reference)->orwhere('api_ref', $request->sys_reference);
        }

        if ($request->reference) {
            $transactions = $transactions->where('api_ref', $request->api_ref);
        }

        if ($request->account_number) {
            $transactions = $transactions->where('account_number', $request->account_number);
        }

        if ($request->user_status) {
            $transactions = $transactions->where('user_status', $request->user_status);
        }

        if ($request->user_service) {
            $transactions = $transactions->where('user_service', $request->user_service);
        }

        if ($request->from && $request->to) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';
            $transactions = $transactions->whereBetween('created_at', [$from, $to]);
        }

        $count = 1;
        $transactions = $transactions->paginate(50)->withQueryString();;

        return view('admin.transactions.deposit_transactions', compact('transactions', 'count'));
    }

    public function singleTransactionView(Transactions $transaction)
    {
        return view('admin.transactions.single_transaction', compact('transaction'));
    }

    public function transactionReceipt($transaction_id)
    {
        $transaction = Transactions::with(['product', 'category', 'variation','customer','biller'])->where('id', $transaction_id)->first()->toArray();
    
        $pdf = Pdf::loadView('customer.receipts.transaction_receipt', ['transaction' => $transaction])->setPaper('a4', 'portrait');
        return $pdf->download($transaction['sys_ref'] . '.pdf');
        // return view('customer.receipts.transaction_receipt', compact('transaction'));
    }

    public function getAmount($product, $variation, $request){
        if ($product->has_variations == 'yes') {
            if(empty($variation)){
                if (\Request::is('mobile/*')) {
                    return MobileResponseService::json(
                        message: "Please select a valid variation",
                        error: "Please select a valid variation",
                        status: 412
                    );
                }
            
                return $response = [
                    'status' => 'fail',
                    'status_code' => '100',
                    'message' => 'Please select a valid variation',
                ];
            }
            if ($variation->fixed_price == 'yes') {
                $request['amount'] = $variation->system_price;
            } elseif ($product->allow_subscription_type == 'yes' && $variation->category->unique_element == 'iuc_number') {
                if (!empty($request->bouquet) && $request->bouquet == 'renew') {
                    $req = new Request([
                        'unique_element' => $request['unique_element'],
                        'variation' => $variation->id,
                    ]);
                    $res = $this->verify($req);
                    if (isset($res['renewal_amount'])) {
                        $request['amount'] = $res['renewal_amount'];
                    } else {
                        $request['amount'] = $variation->system_price;
                    }
                } else {
                    $request['amount'] = $variation->system_price;
                }
            } else {
                $request['amount'] = $this->removeCharsInAmount($request->amount);
            }
        } else {
            if ($product->fixed_price == 'yes') {
                $request['amount'] = $product->system_price;
            } else {
                $request['amount'] = $this->removeCharsInAmount($request->amount);
            }
        }
        
        return $request['amount'];
    }

    public function runChecks($request, $product, $api, $resource){
        $response = null;

        $transaction_restriction_settings = settings()->transaction_restriction_settings;

        if (isset($transaction_restriction_settings['restrict_transactions_from']) && isset($transaction_restriction_settings['restrict_transactions_till'])) {
            $restrictFrom = $transaction_restriction_settings['restrict_transactions_from'];
            $restrictTill = $transaction_restriction_settings['restrict_transactions_till'];
            $currentTime = date('H:i'); 

            // Check if the current time falls within the restricted range
            if ($currentTime >= $restrictFrom && $currentTime <= $restrictTill) {
                $response = array(
                    'status' => 'fail',
                    'status_code' => '100',
                    'message' => 'Transaction cannot be carried out at the moment, please try again later',
                );

            }
        }

        $checkBlacklist = Blacklist::where('biller', $request->billersCode)->first();
        
        $user = auth()->user();
        
        if (!$user) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Basic Authorization Failed! Unable to fetch user record',
            );
        }

        if ($user->kyc_status != 1) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'KYC is not approved, please contact admin',
            );
        }

        // Check min/max of resource
        if(!empty($resource) && $resource->fixed_price == 'no' && !empty($resource->min) && !empty($resource->max)){
            if($request->unit_price < $resource->min  ){
                $response = array(
                    'status' => 'fail',
                    'status_code' => '100',
                    'message' => 'You cannot purchase below '. settings()->currency.number_format($resource->min). ' for this service',
                );
            }

            if ($request->unit_price > $resource->max) {
                $response = array(
                    'status' => 'fail',
                    'status_code' => '100',
                    'message' => 'You cannot purchase above ' . settings()->currency . number_format($resource->min) . ' for this service',
                );
            }
        
        
        }
        // Product access 
        $productBlacklist = productBlacklist(auth()->user());
        
        if(in_array($product->id, $productBlacklist)){
            $response = [
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Product not enabled for customer, please contact support',
            ];
        }
        
        if ($request->security_pin !== $user->transaction_pin) {
            $response = [
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Incorrect transaction pin !!!',
            ];
        }
        
        if($product->category->slug != 'graphic'){
            if (strlen($request->mobile_no) !== 11 || !is_numeric($request->mobile_no)) {
                $response = [
                    'status' => 'fail',
                    'status_code' => '100',
                    'message' => 'Please use a valid mobile no !!!',
                ];
            }
        }

        if (empty($request->billersCode)) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Unique element is not present !!!',
            );
        }

        if (!empty($checkBlacklist) && !empty($request->billersCode)) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Biller cannot carry out transactions, Please contact admin !!!',
            );
        }
        
        if (!$product || $product->status == 'inactive') {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => $product->name . ' purchase is not permitted for this account !!!',
            );
        }

        if ($product->has_variations == 'yes') {
            // $variation = Variation::where('id', $request->variation_id)->where('product_id', $product->id)->first();
            // if(empty($variation)){
            //     $response = array(
            //         'status' => 'fail',
            //         'status_code' => '100',
            //         'message' => $product->name . 'Invalid Variation Selected!!!',
            //     );
            // }
            // dd($variation, $request->all(), $product->id);
            // if ($variation->status == 'inactive') {
            //     $response = array(
            //         'status' => 'fail',
            //         'status_code' => '100',
            //         'message' => $variation->system_name . ' is currently inactive!!!',
            //     );
            // }
        }

        if (!$api || $api->status == 'inactive') {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Service not available for selected product at the moment, please try again later!!!',
            );

        }

        \Log::info(['checks: ' => $response, 'request' => $request->all()]);

        return $response;
    }


    public function requery(Transactions $transaction)
    {
        if (!$transaction) return ['status' => 'failed', 'message' => 'Transaction not found!'];

        if ($transaction->charge_from == 'DEPOSIT') {
            if ($transaction->wallet_funding_provider == 1) {
                $monnify = new MonnifyController($transaction->provider);
                return $monnify->verifyTransaction($transaction->transaction_reference);
            }

            if ($transaction->wallet_funding_provider == 2) {
                $squad = new SquadController($transaction->provider);
                return $squad->verifyTransaction($transaction->transaction_reference);
            }
        } else {
            $api = $transaction->api;
        }

        $query = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($api))->requery($transaction);
        return response()->json($query);
    }

    public function reverseToWallet(Transactions $transaction){
        if($transaction->buyer_status != 'ATTENTION-REQUIRED'){
            $format = [
                'status' => 'failed',
                'message' => 'Transaction not qualified to be refunded, Invalid status'
            ];

            return $format;
        }

        if($transaction->buyer_prebal <= $transaction->buyer_postbal){
            $format = [
                'status' => 'failed',
                'message' => 'Transaction not qualified to be refunded, Pre and Post Balance is the same'
            ];

            return $format;
        }

        if($transaction->is_resolved == 1){
            $format = [
                'status' => 'failed',
                'message' => 'Transaction is already resolved!'
            ];

            return $format;
        }
        
        DB::beginTransaction();

        try {
            $user = $transaction->customer;
            
            $user->lockForUpdate();

            $balance = walletBalance($user);
            $report = 'Refund of ' .$transaction->total_amount. ' for ' . $transaction->billersCode .' was succesful.';
            
            $data = [
                'mobile_number' => $transaction->mobile_number,
                'unit_price' => $transaction->total_amount,
                'buyer_amount' => $transaction->total_amount,
                'buyer_prebal' => $balance,
                'buyer_postbal' => $balance + $transaction->total_amount,
                'buyer_descr' => $report,
                'type' => 'credit',
                'buyer_status' => 'ADMIN-REFUND',
                'customer_status' => 'ADMIN-REFUND',
                'buyer_service' => $transaction->buyer_service,
                'charge_from' => 'WALLET',
                'user_type' => $user->level,
                'sys_ref' => $transaction->sys_ref.'-ADMIN-REFUND',
                'api_ref' => $transaction->api_ref.'-ADMIN-REFUND',
                'api_report' => 'DONE',
                'buyer_quantity' => $transaction->buyer_quantity,
                'provider_id' => $transaction->provider_id,
                'variation_id' => $transaction->variation_id,
                'product_id' => $transaction->product_id ?? null,
                'discount' => 0,
                'src_price' => 0,
                'channel' => 'website',
                'provider' => $transaction->provider,
                'billersCode' => $transaction->billersCode,
                'category_id' => $transaction->category_id,
                'profit' => 0,
                'api_report' => 'DONE',
                'user_id' => $user->id,
            ];
            
            $newTransaction = $this->logTransaction($data);
            
            $transaction->update([
                'buyer_status' => 'REFUNDED',
                'is_resolved' => 1,
                'admin_id' => auth()->guard('admin')->user()->id,
                'resolved_on' => now(),
                'resolved_transaction_id' => $newTransaction->id,
            ]);

            $newTransaction->update([
                'resolved_transaction_id' => $newTransaction->id,
            ]);
            
            DB::commit();

            $body = 'Dear ' .$user->full_name . '<br>Refund of ' .$transaction->total_amount. ' for ' . $transaction->billersCode .' was succesful on ' . config('app.name') . '<br><br>';
                
            // if (in_array('transaction-email', settings()->allowed_email_notifications)) {
            //     $this->sendEmail(auth()->user()->email, $subject, $body);
            // }
            return [
                'status' => 'success',
                'message' => 'Customer refunded successfully',
            ];
        }catch(\Exception $e){
            DB::rollBack();
            return [
                'status' => 'failed',
                'message' => $e->getMessage(). ' Line: '.$e->getLine().' File: '.$e->getFile(),
            ];
        }
    }

    public function resolveTransaction(Request $request, Transactions $transaction)
    {
        DB::beginTransaction();

        try {
            $data = [
                'admin_id' => auth()->guard('admin')->user()->id,
                'previous_status' => $transaction->buyer_status,
                'current_status' => $request->status,
                'transaction_id' => $transaction->id
            ];

            $transaction->buyer_status = $request->status;
            $transaction->save();
            
            $this->logTransactionStatusChangeTrack($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'failed',
                'message' => $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile(),
            ];
        }

        return back()->with('message', 'Transaction reolved successfully');
    }

    public function logTransactionStatusChangeTrack($data){
        TransactionStatusChangeTrack::create($data);
        return;
    }
}
