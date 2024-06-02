<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\BillersLog;
use App\Models\WebSetting;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\JsonResponseService;
use Illuminate\Support\Facades\Session;

class TransactionsController extends Controller
{
    public function initialize($service){
        // $productAccess = $this->getProductAccess();
        $category = Category::with([
            'products' => function ($query) {
                return $query->where('status', 'active')->get();
            }
        ])->where('status', 'active')->where('slug', $service)->first();
        if(!$category){
            return redirect(route('dashboard'));
        }
        // return view('customer.airtime', compact('service','category'));
        return view('customer.'.$service, compact('service','category'));
    }

    public function getCustomerDiscount(Request $request){
        return response()->json(getProductDiscount($request->amount, $request->product_id,$request->quantity ?? 1));
    }

    public function verify(Request $request){   
        $request['product'] = Product::where('id', $request->product_id)->first();
        $request['variation'] = Variation::where('id', $request->variation_id)->where('product_id', $request->product_id)->first();
        $api = $request['variation']->api;

        $verify = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($api))->verify($request, $api);
        
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

        return response()->json($response);

    }

    public function processBuy(Request $request, JsonResponseService $service, $type)
    {
        $user = auth()->user();
        $product_id = $request->product_id ?? $request->network_module;
        $product = Product::where('id', $product_id)->first();
        $var = $request->variation ?? $request->dataplanloader; 
        $variation = Variation::where('id', $var)->where('product_id', $product->id)->first();
        
        $quantity = $request->quantity ?? 1;
    
        $request['security_pin'] = $this->updateTransactionPinToNewFormat($request);
        
        $dateTime = new DateTime('now', new DateTimeZone('Africa/Lagos'));
        // $time = $dateTime->format("d-M-y  h:i A");
        $timexx = $dateTime->format("YmdHi");
        // $timestring = time();
        $api_reference = strtoupper($timexx . uniqid() . rand(10000, 90000)).'SWFT';
        $request_id = $api_reference;
        $request['request_id'] = $request_id;

        
        // Get amount
        $request['amount'] = $this->getAmount($product, $variation, $request);
        $request['unit_price'] = $request['amount'];
        $api = $variation->api ?? $product->api;
        
        // checks
        $checks = $this->runChecks($request,$product, $api);
        if(!empty($checks) && $checks['status'] == 'fail'){
            return $service->build($checks);
        }

        // Get discount
        if ($product->has_variations == 'yes') {
            $resource = $variation;
            $src_price = $variation->api_price * ($request['quantity'] ?? 1);
            $discount = getVariationDiscount($request['amount'], $variation->id, $request['quantity'] ?? 1);
        } else {
            $resource = $product;
            $src_price = !empty($product->system_price) ? $product->system_price * ($request['quantity'] ?? 1) : 0;
            $discount = getProductDiscount($request['amount'], $product->id, $request['quantity'] ?? 1);
        }
        
        $discountedAmount = $discount['new_amount'];
        $disCountApplied = $discount['discount_applied'];
        //
        if ($product->category->slug == 'cable' && in_array($product->slug, ['dstv', 'gotv']) && !empty($request->renewal_amount && $request->renewal_amount > 0 && $request->request_type == 'renewal')) {
            $variations['price'] = $request->renewal_amount ?? $discount;
        }
        //
        $request['quantity'] = $request->quantity ?? 1;
        $request['total_amount'] = $discountedAmount;
        $request['discount'] = $disCountApplied;
        $balance = walletBalance($user);
        
        if ($balance < $request['total_amount']) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Insufficient Wallet Balance, Please fund wallet and retry again',
            );

            return $service->build($response);
        }

        $request['package_name'] = $resource->display_name;
        $request['serviceId'] = $resource->slug ?? null;
        $request['variation_code'] = $variation->variation_code ?? null;
        $request['quantity'] = $quantity;

        if (in_array($resource->category->slug, ['electricity', 'cable'])) {
            $request['billersCode'] = $request->billersCode;
        } else {
            $request['billersCode'] = $request['account_id'] ?? $request->mobile_no;
        }
        // Log transaction
        
        $data = [
            'mobile_number' => $request->mobile_no,
            'buyer_email' => $user->email,
            'buyer_username' => $user->username,
            'unit_price' => $request['unit_price'],
            'buyer_amount' => $request['total_amount'],
            'buyer_prebal' => $balance,
            'buyer_postbal' => $balance - $request['total_amount'],
            'buyer_descr' => '',
            'buyer_status' => 'INITIATED',
            'buyer_service' => $resource->slug,
            // 'buyer_date' => $time,
            // 'timestring' => $timestring,
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
            'src_price' => $src_price,
            'channel' => 'website',
            'provider' => $api->name,
            'billersCode' => $request['billersCode'] ?? null,
            'category_id' => $resource->category->id,
            'profit' => ($src_price > 0) ? $src_price - $request['total_amount'] : $src_price,
        ];
        $transaction = $this->logTransaction($data);
        $data['resource'] = $resource;
        $data['product'] = $product;
        $data['billersCode'] = $request['billersCode'] ?? null;
        $data['request_type'] = $request->request_type;
        // Make Api call
        $api_response = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($api))->query($data, $api);
        
        if ($api_response['status'] == 'success') {
            $report = $this->formatOderReport($resource, $request,$transaction, $api_response);
            $transaction->update([
                'buyer_status' => 'SUCCESSFUL',
                'buyer_descr' => $report['order_report'],
                'api_response' => $api_response['api_response'],
                'api_report' => 'DONE',
                'api_status' => $api_response['status'],
                'extra' => $api_response['extra'] ?? null,
                'post_fields' => $api_response['postfields'],
            ]);
            
            $order_report = $report['order_report'] ?? '';
            $final_response = $report['final_response'];

            $body = 'Dear ' . auth()->user()->full_name . '<br>The transaction below was carried out on your account on' . config('app.name') . '<br><br>
            Amount Charged : ₦' . number_format($transaction->buyer_amount) . '<br>
            Previous Balance : ₦' . number_format($transaction->buyer_prebal) . '<br>
            Current Balance : ₦' . number_format($transaction->buyer_postbal) . '<br>
            Description : ' . $order_report . '<br>
            Transaction Reference : ' . $transaction->sys_ref . '<br>
            Transaction Date : ' . $transaction->buyer_date . '<br>
            Transaction Status : Success<br>';

            if (isset($api_response['extra']) && !empty($api_response['extra'])) {
                $body .= 'Extra Information : ' . $api_response['extra'];
            }

            $body .= '<br><br><br>Warm Regards<br>' . config('app.name');
            $subject = "Transaction Notification";
        } else if ($api_response['status'] == 'attention-required') {
            $report = $this->formatOderReport($resource, $request, $transaction, $api_response);
            $order_report = $report['order_report'] ?? '';
            $final_response = $report['final_response'];

            $transaction->update([
                'buyer_status' => 'ATTENTION-REQUIRED',
                'buyer_descr' => $order_report,
                'api_response' => $api_response['api_response'],
                'api_report' => 'pending',
                'failure_reason' => $api_response['error'],
                'extra' => $api_response['extra'] ?? null,
                'post_fields' => $api_response['postfields'],
            ]);

            $body = 'Dear ' . auth()->user()->email . 'The transaction below was carried out on your account on ' . config('app.name') . '<br><br>
            Amount Charged : ' . number_format($transaction->buyer_amount) . '<br>
            Previous Balance : ' . number_format($transaction->buyer_prebal) . '<br>
            Current Balance : ' . number_format($transaction->buyer_postbal) . '<br>
            Description : ' . $transaction->$order_report . '<br>
            Transaction Reference : ' . $transaction->sys_ref . '<br>
            Transaction Date : ' . $transaction->buyer_date . '<br>
            Extra Information : ' . $transaction->buyer_date . '<br><br><br>Warm Regards<br>' . config('app.name').'
            Transaction Status : Pending<br>';
            $subject = "Transaction Notification";
        } else if($api_response['status'] == 'failed'){
            $report = $this->formatOderReport($resource, $request, $transaction, $api_response);

            $order_report = $report['order_report'];
            $final_response = $report['final_response'];

            $transaction->update([
                'buyer_status' => 'FAILED',
                'post_fields' => $api_response['postfields'],
                'buyer_descr' => $order_report,
                'buyer_postbal' => $transaction->buyer_prebal,
                'api_response' => $api_response['api_response'],
                'api_report' => 'FAILED',
                'failure_reason' => $api_response['error'] ?? ($api_response['response'] ?? 'unknown error'),
            ]);

            $body = 'Dear ' . auth()->user()->email . 'The transaction below failed on your account on ' . config('app.name') . '<br><br>
            Amount Charged : ' . number_format($transaction->buyer_amount) . '<br>
            Previous Balance : ' . number_format($transaction->buyer_prebal) . '<br>
            Current Balance : ' . number_format($transaction->buyer_postbal) . '<br>
            Description : ' . $transaction->$order_report . '<br>
            Transaction Reference : ' . $transaction->sys_ref . '<br>
            Transaction Date : ' . $transaction->buyer_date . '<br>
            Transaction Status : Failed<br>';
            $subject = "Transaction Notification";
        }

        $user->wallets = $transaction->buyer_postbal;
        $user->save();
        $this->sendEmail(auth()->user()->email, $subject, $body);
        
        return response()->json($final_response);
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
        if ($api_response['status'] == 'success') {
            $order_report = strtoupper('Purchase Successful on ' . $request['billersCode'] ?? $resource->display_) .'. '. $api_response['response'];
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
            if(!empty($api_response['response'])){
                $order_report = strtoupper('Purchase failed on ' .$request['billersCode'] ?? $resource->name . ' Error: ' . $api_response['response']);
            }else{
                $order_report = strtoupper('Purchase failed on ' . $request['billersCode'] ?? $resource->name);
            }

            if ($resource->slug == 'smile-data') {
                $order_report = strtoupper(settings()->currency . $transaction->buyer_amount . ' Purchase failed on ' . $request['billersCode']);
            } elseif ($resource->slug == 'spectranet-data') {
                $order_report = strtoupper(settings()->currency . $transaction->buyer_amount . ' E-pin Purchase failed on ' . $request['billersCode']);
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

        // 'mdata_comm' => $webSetting->mdata_comm,
        // 'mcdata_comm' => $webSetting->mcdata_comm,
        // 'adata_comm' => $webSetting->adata_comm,
        // 'acdata_comm' => $webSetting->acdata_comm,
        // 'gdata_comm' => $webSetting->gdata_comm,
        // 'modata_comm' => $webSetting->modata_comm,
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

        // //CABLE COMMISSION
        // 'gotv_comm' => $webSetting->gotv_comm,
        // 'dstv_comm' => $webSetting->dstv_comm,
        // 'star_comm' => $webSetting->star_comm,

        // ///E-PINS COMMSSION
        // 'waec_comm' => $webSetting->waec_comm,
        // 'neco_comm' => $webSetting->neco_comm,
        // 'nabteb_comm' => $webSetting->nabteb_comm,

        // //BILLS COMMSSION
        // 'bills_comm' => $webSetting->bills_comm,

        // //SMS COMMSSION
        // 'sms_comm' => $webSetting->sms_comm,
        // 'smile_vtu_discount' => $webSetting->smile_vtu_discount,
        // 'smile_lock' => $webSetting->smile_lock,
        // 'smile_vtu_lock'  =>  $webSetting->smile_vtu_lock,
        // 'nbais_lock' =>  $webSetting->nbais_lock,
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
        return view('admin.transactions.debit_customer');
    }

    function creditCustomerPage()
    {
        return view('admin.transactions.credit_customer');
    }

    function processCreditDebit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'type' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)return back()->with('error', 'Account not found!');
        
        dd('continue');
        $controller = new TransactionController();
        $amount = $controller->removeCharsInAmount($request->amount);
        $currAmount = walletBalance($user);
        if ($type == 'credit') {
            $data['balance_after'] = $currAmount + $amount;
        } else {
            $data['balance_after'] = $currAmount - $amount;
        }

        $requestId = $controller->generateRequestId();
        $tid = 'A2C-' . $requestId;
        $reason = $type == 'debit' ? 'ADMIN-DEBIT' : 'ADMIN-CREDIT';
        try {
            //code...
            $data['type'] = $type;
            $data['customer_id'] = $user->customer->id;
            $data['transaction_id'] = $tid;
            $data['request_id'] = $requestId;
            $data['payment_method'] = 'wallet';
            $data['balance_before'] = $currAmount;
            $data['amount'] = $amount;
            $data['total_amount'] = $amount;
            $data['customer_email'] = $user->email;
            $data['customer_phone'] = $user->phone;
            $data['customer_name'] = $user->firstname;
            $data['unique_element'] = 'wallet';
            $data['discount'] = 0;
            $data['unit_price'] = $amount;
            $data['descr'] = $request->reason;
            $data['reason'] = $reason;
            $data['status'] = 'delivered';
            $data['admin_id'] = auth()->user()->admin->id;

            $controller->logTransaction($data);

            DB::transaction(function () use ($type, $amount, $tid, $user, $reason) {
                $wallet = new WalletController();
                $wallet->logWallet([
                    'customer_id' => $user->customer->id,
                    'type' => $type,
                    'total_amount' => $amount,
                    'reason' => $reason,
                    'transaction_id' => $tid,
                    'payment_method' => 'ADMIN-FUNDING',
                ]);
                $wallet->updateCustomerWallet($user, $amount, $type);
            });
            $sign = getSettings()->currency;
            return back()->with('message', "Customer wallet has been {$type}ed with {$sign}" . number_format($amount));
        } catch (\Exception $e) {

            return back()->with('error', 'An error occured' . $e->getMessage() . $e->getLine() . $e->getFile());
        }
    }

    public function adminTransactions(Request $request){
        $transactions = Transactions::latest();
        $products = Product::all();

        if ($request->email) {
            $transactions = $transactions->where('customer_email', $request->email);
        }

        if ($request->phone) {
            $transactions = $transactions->where('customer_phone', $request->phone);
        }

        if ($request->service) {
            $transactions = $transactions->where('product_id', $request->service);
        }
        if ($request->sys_reference) {
            $transactions = $transactions->where('sys_ref', $request->sys_reference);
        }
        // if ($request->unique_element) {
        //     $transactions = $transactions->where('unique_element', $request->unique_element);
        // }
        if ($request->status) {
            $transactions = $transactions->where('status', $request->status);
        }
        if ($request->from) {
            $time = $request->from . ' 00:00:00';
            $transactions = $transactions->where('created_at', '>', $time);
        }
        if ($request->to) {
            $time = $request->to . ' 00:00:00';
            $transactions = $transactions->where('created_at', $time);
        }

        $transactions = $transactions->paginate(15)->withQueryString();;

        return view('admin.transactions.index', compact('transactions','products'));
    }


    public function singleTransactionView(Transactions $transaction)
    {
        return view('admin.transactions.single_transaction', compact('transaction'));
    }

    public function transactionReceipt($transaction_id)
    {
        $transaction = Transactions::with(['product', 'category', 'variation'])->where('id', $transaction_id)->first()->toArray();
       
        $pdf = Pdf::loadView('customer.receipts.transaction_receipt', ['transaction' => $transaction])->setPaper('a4', 'portrait');
        return $pdf->download($transaction['sys_ref'] . '.pdf');
        // return view('customer.receipts.transaction_receipt', compact('transaction'));
    }

    public function getAmount($product, $variation, $request){
        if ($product->has_variations == 'yes') {
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

    public function runChecks($request, $product, $api){
        $checkBlacklistPhone = \DB::table('blacklist_mobiles')->where('add_mobile', $request->mobile_no)->first();
        $user = auth()->user();
        $response = null;

        if (!$user) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Basic Authorization Failed! Unable to fetch user record',
            );
        }
        
        if ($request->security_pin !== $user->transaction_pin) {
            $response = [
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Incorrect security pin !!!',
            ];
        }
        
        if (strlen($request->mobile_no) !== 11 || !is_numeric($request->mobile_no)) {
            $response = [
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Please use a valid mobile no !!!',
            ];

        } else if ($checkBlacklistPhone) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Mobile no. cannot carry out transactions !!!',
            );

        } else if (!$product || $product->status == 'inactive') {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => $product->name . ' purchase is not permitted for this account !!!',
            );

        }

        if (!$api || $api->status == 'inactive') {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Service not available for selected product at the moment, please try again later!!!',
            );

        }

        return $response;
    }
}
