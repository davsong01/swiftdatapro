<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\BillersLog;
use App\Models\WebSetting;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\JsonResponseService;

class TransactionsController extends Controller
{

    public function initialize($service){
        // $productAccess = $this->getProductAccess();
        $category = Category::with([
            'products' => function ($query) {
                return $query->where('status', 'active')->get();
            }
        ])->where('status', 'active')->where('slug', $service)->first();
        
        return view('customer.'.$service, compact('service','category'));
    }

    public function getCustomerDiscount(Request $request){
        return response()->json(getProductDiscount($request->amount, $request->product_id, $request->variation_id));
    }

    // public function getProductAccess($needle=null){
    //     $webSetting = WebSetting::first();

    //     ////LOCK CABLE
    //     $access = [
    //         'dstv_lock' => $webSetting->dstv_lock,
    //         'gotv_lock' => $webSetting->gotv_lock,
    //         'star_lock' => $webSetting->star_lock,
    //         'showmax_lock' => $webSetting->showmax_lock,

    //         ////LOCK EPINS
    //         'waec_lock' => $webSetting->waec_lock,
    //         'gce_lock' => $webSetting->gce_lock,
    //         'neco_lock' => $webSetting->neco_lock,
    //         'waec_reg_lock' => $webSetting->waec_registration_lock,
    //         'nabt_lock' => $webSetting->nabt_lock,

    //         ////LOCK VTU
    //         'm_lock2' => $webSetting->m_lock2,
    //         'g_lock2' => $webSetting->g_lock2,
    //         'a_lock2' => $webSetting->a_lock2,
    //         'mo_lock2' => $webSetting->mo_lock2,


    //         ////LOCK SNS
    //         'm_lock3' => $webSetting->m_lock3,
    //         'g_lock3' => $webSetting->g_lock3,
    //         'a_lock3' => $webSetting->a_lock3,
    //         'mo_lock3' => $webSetting->mo_lock3,

    //         ////LOCK DATA
    //         'm_lock1' => $webSetting->m_lock1,
    //         'mg_lock1' => $webSetting->mg_lock1,
    //         'g_lock1' => $webSetting->g_lock1,
    //         'gcg_lock1' => $webSetting->gcg_lock1,
    //         'a_lock1' => $webSetting->a_lock1,
    //         'ag_lock1' => $webSetting->ag_lock1,
    //         'mo_lock1' => $webSetting->mo_lock1,
    //         'mobilesme_lock' => $webSetting->mobilesme_lock,
    //         'mtn_data_card' => $webSetting->mtn_data_card,
    //         'mtn_data_card_lock' => $webSetting->mtn_data_card_lock,
    //         'spectranet_lock' => $webSetting->spectranet_lock,

    //         ////LOCK BILLS
    //         'abuja_lock' => $webSetting->abuja_lock,
    //         'eko_lock' => $webSetting->eko_lock,
    //         'ibadan_lock' => $webSetting->ibadan_lock,
    //         'ikeja_lock' => $webSetting->ikeja_lock,
    //         'jos_lock' => $webSetting->jos_lock,
    //         'kano_lock' => $webSetting->kano_lock,
    //         'kaduna_lock' => $webSetting->kaduna_lock,
    //         'port_lock' => $webSetting->port_lock,
    //         'benin_lock' => $webSetting->benin_lock,
    //         'enugu_lock' => $webSetting->enugu_lock,
    //         'aba_lock' => $webSetting->aba_lock,

    //         ///
    //         'sms_access' => $webSetting->sms_access,
    //         'sms_api' => $webSetting->sms_api,
    //         'withdraw_access' => $webSetting->withdraw_access,
    //         'withdraw_charge' => $webSetting->withdraw_charge,
    //         'level2_charge' => $webSetting->level2_charge,
    //         'level2_adminch' => $webSetting->level2_adminch,
    //         'level3_charge' => $webSetting->level3_charge,
    //         'level3_adminch' => $webSetting->level3_adminch,
    //         'level4_charge' => $webSetting->level4_charge,
    //         'level4_adminch' => $webSetting->level4_adminch,
    //         'smile_vtu_lock' => $webSetting->smile_vtu_lock,
    //         'smile_lock' => $webSetting->smile_lock,

    //     ];

    //     if(!empty($needle)){
    //         $access = $access[$needle] ?? null;
    //     }

    //     return $access;
        
    // }

    public function verify(Request $request){        
        $check = BillersLog::where('unique_element', $request->smart_no)->where('product', strtolower($request->service_module))->first();
       
        if(!empty($check)){
            $verify = [
                'customerName' => $check->customer_name,
                'customerAddress' => $check->customer_address,
            ];
        }else{
            $api = $this->getProvider($request->service_module);
            $variations = app('App\Http\Controllers\Providers\\' . $api['provider_name'])->getVariations($request->type, strtolower($request->service_module), auth()->user()->level, $request->plan_id);
            
            $verify = app('App\Http\Controllers\Providers\\' . $api['provider_name'])->verify($request->all());
            if(isset($verify['status']) && $verify['status'] == 'success'){
                // BillersLog::updateOrCreate(['unique_element' => $request->smart_no, 'product' => strtolower($request->service_module)],[
                //     'unique_element' => $request->smart_no,
                //     'provider' => $verify['provider'],
                //     'customer_name' => $verify['customerName'] ?? null,
                //     'customer_address' => $verify['customerAddress'] ?? null,
                //     'category' => $request->type,
                //     'product' => strtolower($request->service_module),
                // ]);
            }
        }

        
        if ($request->type == 'electricity') {
            $button = '<button class="btn btn-success btn-block" onclick="pay4Bills()">Make Payment</button> <button class="btn btn-danger" onclick="closeModal()">Close</button>';
            
            $response = array(
                'status' => 'success',
                'status_code' => '200',
                'message' => $verify['message'] ?? '',
                'title' => $verify['title'] ?? '',
                'desc' => $verify['message'] ?? '',
            );
        }
       
        if ($request->type == 'cable') {
            $response = array(
                'status' => $verify['status'] ?? '',
                'status_code' => $verify['status_code'] ?? '100',
                'message' => $verify['message'] ?? '',
                'title' => $verify['title'] ?? '',
                'desc' => $verify['message'] ?? '',
                'renewal_amount' => !empty($request->request_type) && $request->request_type == 'renewal' ? ($verify['renewal_amount'] ?? $variations['price']) : ($variations['price'] ?? 0),
            );
        }

        return response()->json($response);

    }

    public function variations(Request $request){
        $service = strtolower($request->services);
        $product = strtolower($request->product);
        
        $level = strtolower($request->level);
        $api = $this->getProvider($service, $product);
        $echo = '<option>No Available variations at the moment</option>';
        
        if($api['individual_access'] == 0 || $api['product_access'] == 0){
            return response()->json($echo);
        }
        
        $variations = app('App\Http\Controllers\Providers\\'.$api['provider_name'])->getVariations($product, $service, $level);
        
        $discount = app('App\Http\Controllers\TransactionsController')->discounts($request);
    
        if (isset($variations) && !empty($variations)) {
            $echo = "";
            $echo .= $discount ?? '';
            foreach ($variations as $res) {
                if (isset($res['price']) && $res['price'] > 0) {
                    $echo .= '<option value="' . $res['code'] . '">' . $res['name'] . ' - ₦' . $res['price'] . '</option>';
                }
            }
        }
        
        return response()->json($echo);

    }

    public function getProvider($service, $product = null, $user=null){
        $webSetting = WebSetting::first();
        $api = '';
        $user = $user ?? auth()->user();
        $service =  strtolower($service);
        
        if($service == "mtn"){
            $api = [
                'provider_slug' => $webSetting->api_mtn,
                'provider_name' => str_replace(".php", "",str_replace(" ", "", ucwords(str_replace("_"," ", $webSetting->api_mtn))).'Controller').'.php',
                'individual_access' => $user->mtnsme_access,
                'product_access' => $this->getProductAccess('m_lock1'),
            ];
        }

        if ($service == "mtn-datacard") {
            $api = [
                'provider_slug' => $webSetting->mtn_data_card,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->mtn_data_card))) . 'Controller') . '.php',
                'individual_access' => $user->mtnsme_access,
                'product_access' => $this->getProductAccess('m_lock1'),
            ];
        }

        if ($service == "mtn-cg" || $service == "gifting" ) {
            $api = [
                'provider_slug' => $webSetting->api_gift,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_gift))) . 'Controller') . '.php',

                'individual_access' => $user->mtncg_access,
                'product_access' => $this->getProductAccess('mg_lock1'),
            ];
        }

        if ($service == "9mobile") {
            $api = [
                'provider_slug' => $webSetting->api_mobile,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mobile))) . 'Controller') . '.php',

                'individual_access' => $user->mobile_access,
                'product_access' => $this->getProductAccess('mo_lock1'),
            ];
        }
        
        if ($service == "mobile-sme") {
            $api = [
                'provider_slug' => $webSetting->api_mobilesme,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mobilesme))) . 'Controller') . '.php',

                'individual_access' => $user->mobile_access,
                'product_access' => $this->getProductAccess('mobilesme_lock'),
            ];
        }

        if ($service == "airtel") {
            $api = [
                'provider_slug' => $webSetting->api_airtel,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_airtel))) . 'Controller') . '.php',

                'individual_access' => $user->airtel_access,
                'product_access' => $this->getProductAccess('a_lock1'),
            ];
        }

        if ($service == "airtel-cg") {
            $api = [
                'provider_slug' => $webSetting->api_airtelcg,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_airtelcg))) . 'Controller') . '.php',

                'individual_access' => $user->airtelcg_access,
                'product_access' => $this->getProductAccess('ag_lock1'),
            ];
        }

        if ($service == "glo") {
            $api = [
                'provider_slug' => $webSetting->api_glo,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_glo))) . 'Controller') . '.php',

                'individual_access' => $user->glo_access,
                'product_access' => $this->getProductAccess('g_lock1'),
            ];
        }

        if ($service == "glo-cg") {
            $api = [
                'provider_slug' => $webSetting->api_glocg,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_glocg))) . 'Controller') . '.php',

                'individual_access' => $user->glo_access,
                'product_access' => $this->getProductAccess('gcg_lock1'),
            ];
        }

        if ($service == "9mobile-cg" || $service == "etisalat") {
            $api = [
                'provider_slug' => $webSetting->api_mobilesme,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mobilesme))) . 'Controller') . '.php',

                'individual_access' => $user->mobile_access,
                'product_access' => $this->getProductAccess('mo_lock1'),
            ];
        }

        if ($service == "spectranet-data") {
            $api = [
                'provider_slug' => $webSetting->api_spectranet,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_spectranet))) . 'Controller') . '.php',

                'individual_access' => 1,
                'product_access' => $this->getProductAccess('spectranet_lock'),
            ];
        }

        if ($service == "smile-data") {
            $api = [
                'provider_slug' => $webSetting->api_smile,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_smile))) . 'Controller') . '.php',

                'individual_access' => 1,
                'product_access' => $this->getProductAccess('smile_lock'),
            ];
        }

        if ($service == "smile-vtu") {
            $api = [
                'provider_slug' => $webSetting->api_smile,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_smile))) . 'Controller') . '.php',

                'individual_access' => 1,
                'product_access' => $this->getProductAccess('smile_vtu_lock'),
            ];
        }

        if ($service == "mtn-vtu") {
            $api = [
                    'provider_slug' => $webSetting->api_mtn2,
                    'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mtn2))) . 'Controller') . '.php',
                    'individual_access' => $user->airtime_access,
                    'product_access' => $this->getProductAccess('m_lock2'),
                ];
        }

        if ($service == "glo-vtu") {
            $api = [
                'provider_slug' => $webSetting->api_glo2,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_glo2))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('g_lock2'),
            ];
        }

        if ($service == "airtel-vtu") {
            $api = [
                'provider_slug' => $webSetting->api_airtel2,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_airtel2))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('a_lock2'),
            ];
        }

        if ($service == "9mobile-vtu" || $service == "eitsalat-vtu") {
            $api = [
                'provider_slug' => $webSetting->api_mobile2,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mobile2))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('mo_lock2'),
            ];
        }

        if ($service == "mtn-sns") {
            $api = [
                'provider_slug' => $webSetting->api_mtn3,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mtn3))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('m_lock3'),
            ];
        }

        if ($service == "glo-sns") {
            $api = [
                'provider_slug' => $webSetting->api_airtel3,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_airtel3))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('g_lock3'),
            ];
        }

        if ($service == "airtel-sns") {
            $api = [
                'provider_slug' => $webSetting->api_airtel3,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_airtel3))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('a_lock3'),
            ];
        }

        if ($service == "9mobile-sns") {
            $api = [
                'provider_slug' => $webSetting->api_mobile3,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mobile3))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('mo_lock3'),
            ];
        }

        if ($service == "9mobile-sns") {
            $api = [
                'provider_slug' => $webSetting->api_mobile3,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_mobile3))) . 'Controller') . '.php',
                'individual_access' => $user->airtime_access,
                'product_access' => $this->getProductAccess('mo_lock3'),
            ];
        }
       
        if ($service == "waec") {
            $api = [
                'provider_slug' => $webSetting->api_waec,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_waec))) . 'Controller') . '.php',
                'individual_access' => $user->epin_access,
                'product_access' => $this->getProductAccess('waec_lock'),
            ];
        }

        if ($service == "waec-reg") {
            $api = [
                'provider_slug' => $webSetting->api_waec_registration,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_waec_registration))) . 'Controller') . '.php',
                'individual_access' => $user->epin_access,
                'product_access' => $this->getProductAccess('waec_reg_lock'),
            ];
        }

        if ($service == "gce") {
            $api = [
                'provider_slug' => $webSetting->api_gce,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_gce))) . 'Controller') . '.php',
                'individual_access' => $user->epin_access,
                'product_access' => $this->getProductAccess('gce_lock'),
            ];
        }

        if ($service == "neco") {
            $api = [
                'provider_slug' => $webSetting->api_neco,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_neco))) . 'Controller') . '.php',
                'individual_access' => $user->epin_access,
                'product_access' => $this->getProductAccess('neco_lock'),
            ];
        }

        if ($service == "nabteb") {
            $api = [
                'provider_slug' => $webSetting->api_nabteb,
                'provider_name' => str_replace(".php", "", str_replace(" ", "", ucwords(str_replace("_", " ", $webSetting->api_nabteb))) . 'Controller') . '.php',
                'individual_access' => $user->epin_access,
                'product_access' => $this->getProductAccess('nabt_lock'),
            ];
        }

        
        // if ($service == "WAEC" && $waec_lock == 1 && $service_access == 1) {
        //     if ($api_waec == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }

        //     if ($api_waec == 'easyaccess.php') {
        //         require("epinsModule/easyaccess.php");
        //     }
        // } else if ($service == "WAEC-REG" && $waec_reg_lock == 1 && $service_access == 1) {
        //     if ($api_waec_registration == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }
        //     if ($api_waec_registration == 'vtpasseducation.php') {
        //         require("epinsModule/vtpasseducation.php");
        //     }
        // } else if ($service == "GCE" && $gce_lock == 1 && $service_access == 1) {
        //     if ($api_gce == 'easyaccess.php') {
        //         require("epinsModule/easyaccess.php");
        //     }
        //     if ($api_gec == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }
        // } else if ($service == "NECO" && $neco_lock == 1 && $service_access == 1) {
        //     if ($api_neco == 'easyaccess.php') {
        //         require("epinsModule/easyaccess.php");
        //     }
        //     if ($api_neco == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }
        // } else if ($service == "NABTEB" && $nabt_lock == 1 && $service_access == 1) {


        if ($service == "abuja-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('mo_lock3'),
            ];
        }

        if ($service == "eko-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('eko_lock'),
            ];
        }

        if ($service == "ibadan-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('ibadan_lock'),
            ];
        }

        if ($service == "ikeja-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('ikeja_lock'),
            ];
        }

        if ($service == "kaduna-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('kaduna_lock'),
            ];
        }

        if ($service == "kano-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('kano_lock'),
            ];
        }

        if ($service == "jos-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('jos_lock'),
            ];
        }
        if ($service == "portharcourt-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('port_lock'),
            ];
        }
        if ($service == "benin-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('benin_lock'),
            ];
        }
        if ($service == "enugu-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('enugu_lock'),
            ];
        }
        if ($service == "aba-electric") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->bills_access,
                'product_access' => $this->getProductAccess('aba_lock'),
            ];
        }
        
        if ($service == "dstv") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->cable_access,
                'product_access' => $this->getProductAccess('dstv_lock'),
            ];
        }

        if ($service == "gotv") {
            $api = [
                    'provider_slug' => 'vtpass',
                    'provider_name' => 'VtpassController',
                    'individual_access' => $user->cable_access,
                    'product_access' => $this->getProductAccess('gotv_lock'),
                ];
        }

        if ($service == "startimes") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->cable_access,
                'product_access' => $this->getProductAccess('star_lock'),
            ];
        }

        if ($service == "showmax") {
            $api = [
                'provider_slug' => 'vtpass',
                'provider_name' => 'VtpassController',
                'individual_access' => $user->cable_access,
                'product_access' => $this->getProductAccess('showmax_lock'),
            ];
        }
        
        // if ($service == "WAEC" && $waec_lock == 1 && $service_access == 1) {
        //     if ($api_waec == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }

        //     if ($api_waec == 'easyaccess.php') {
        //         require("epinsModule/easyaccess.php");
        //     }
        // } else if ($service == "WAEC-REG" && $waec_reg_lock == 1 && $service_access == 1) {
        //     if ($api_waec_registration == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }
        //     if ($api_waec_registration == 'vtpasseducation.php') {
        //         require("epinsModule/vtpasseducation.php");
        //     }
        // } else if ($service == "GCE" && $gce_lock == 1 && $service_access == 1) {
        //     if ($api_gce == 'easyaccess.php') {
        //         require("epinsModule/easyaccess.php");
        //     }
        //     if ($api_gec == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }
        // } else if ($service == "NECO" && $neco_lock == 1 && $service_access == 1) {
        //     if ($api_neco == 'easyaccess.php') {
        //         require("epinsModule/easyaccess.php");
        //     }
        //     if ($api_neco == 'naijaresultpins.php') {
        //         require("epinsModule/naijaresultpins.php");
        //     }
        // } else if ($service == "NABTEB" && $nabt_lock == 1 && $service_access == 1) {
        if (in_array($api['provider_name'], ['MtnsmeGonController.php', 'GongzoDataController.php', 'GongzoController.php'])) {
            $api['provider_name'] = 'GongzoController';
        }

        if (in_array($api['provider_name'], ['MobileDonController.php'])) {
            $api['provider_name'] = 'DonlesonController';
        }

        if (in_array($api['provider_name'], ['EasyAccessDataController.php'])) {
            $api['provider_name'] = 'EasyAccessController';
        }

        if (in_array($api['provider_name'], ['MtnsmeDonController.php'])) {
            $api['provider_name'] = 'DonlessonCntroller';
        }
    
        if (in_array($api['provider_name'], ['VtpassController','AirtimevtuVtpassController.php','VtpassDataController.php'])) {
            $api['provider_name'] = 'VtpassController';
        }

        if (in_array($api['provider_name'], ['ClubkonnectController.php'])) {
            $api['provider_name'] = 'ClubkonnectController';
        }


        if (in_array($api['provider_name'], ['AirtimesnsSmsController.php'])) {
            $api['provider_name'] = 'AirtimesnsSmsController';
        }

        if (in_array($api['provider_name'], ['NaijaresultpinsController.php'])) {
            $api['provider_name'] = 'NaijaresultpinsController';
        }
    
        return $api;
    
    }

    public function processBuy(Request $request, JsonResponseService $service, $type){
        $user = auth()->user();

        $network_module = strtoupper($request->network_module);
        $service_module = strtoupper($request->service_module);
        
        $product = strtolower($network_module);
        
        $dataplanloader = $request->dataplanloader ?? $product;
        $mobile_no = $request->mobile_no;
        $no_of_pins = $request->no_of_pins ?? 1;
        $security_pin = base64_encode(base64_encode(base64_encode($request->security_pin)));
        $account_id = $request->account_id;
        $plan_code = $dataplanloader;
        $network = !empty($network_module) ? $network_module : $service_module;
        
        $dateTime = new DateTime('now', new DateTimeZone('Africa/Lagos'));
        $time = $dateTime->format("d-M-y  h:i A");
        $timexx = $dateTime->format("YmdHi");
        $timestring = time();
        $api_reference = strtoupper($timexx . uniqid() . rand(10000, 90000));
        $request_id = $api_reference;
        $checkBlacklistPhone = \DB::table('blacklist_mobiles')->where('add_mobile', $mobile_no)->first();

        $request['request_id'] = $request_id;
        
        // checks
        if(!$user){
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Basic Authorization Failed! Unable to fetch user record',
            );
            return $service->build($response);
        }
       
        if (strlen($mobile_no) !== 11 || !is_numeric($mobile_no)) {
            $response = [
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Please use a valid mobile no !!!',
            ];

            return $service->build($response);
        }else if($security_pin !== $user->transaction_pin){
            $response = [
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Incorrect security pin !!!',
            ];
            return $service->build($response);

        }else if (!empty($mobile_no) && !empty($reference) && !empty($network) && !empty($user->api_token)) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Please make sure all the parameters are included.',
            );
            return $service->build($response);
        }else if($user->block == 1){
            $response = [
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Permission denied !!!',
            ];
            return $service->build($response);
        }else if($checkBlacklistPhone) {
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Mobile no. cannot carry out transactions !!!',
            );

            return $service->build($response);

        }
        
        // Check provider and access
        
        $api = $this->getProvider(strtolower($network));
       
        if($api){
            if ($api['individual_access'] == 0 || $api['product_access'] == 0) {
                $response = [
                    'status' => 'fail',
                    'status_code' => '100',
                    'message' => $network . ' purchase is not permitted for this account !!!',
                ];

                return $service->build($response);
            }
        }else{
            $response = array(
                'status' => 'fail',
                'status_code' => '100',
                'message' => 'Service is currently down or Restricted for this account. kindly check and try again !!!',
            );

            return $service->build($response);
        }

        if($dataplanloader == 'glo' || $dataplanloader == 'glo-vtu' ){
            $request['plan_id'] = 'glo';
        }elseif($dataplanloader == 'mtn-vtu' || $dataplanloader == 'mtn'){
            $request['plan_id'] = 'mtn';
        }elseif($dataplanloader == 'airtel-vtu' || $dataplanloader == 'airtel'){
            $request['plan_id'] = 'airtel';
        }else{
            $request['plan_id'] = $dataplanloader;
        }

        $request['type'] = $type;
        $request['level'] = $user->level;
        
        $variations = app('App\Http\Controllers\Providers\\' . $api['provider_name'])->getVariations($type, $product, $request['level'], $request['plan_id'], $request['amount'], $request['service_plan']);
        
        $request['services'] = $request['service_module'];
        $discount = app('App\Http\Controllers\TransactionsController')->discounts($request) ?? 0;
    
        if($type == 'cable' && in_array($request->network_module, ['DSTV','GOTV']) && !empty($request->renewal_amount && $request->renewal_amount > 0 && $request->request_type == 'renewal')){
            $variations['price'] = $request->renewal_amount ?? $discount;
        }

        $service_price = $variations['price'];
        // Take care of discounts later in the future

        // if($discount > 0){
        //     $discount = ($discount /100) * 
        // }
        $databyte = $variations['databyte'] ?? 0;
        $package_name = $variations['name'] ?? '';
        $serviceId = $variations['serviceId'] ?? '';
        $variation_code = $variations['variation_code'] ?? '';
        
        $user = auth()->user();
    
        $request['price'] = $service_price ?? null;
        
        $request['serviceId'] = $serviceId;
        $request['no_of_pins'] = $no_of_pins;
        $request['variation_code'] = $variation_code;

        if(in_array($type, ['electricity','cable'])){
            $request['billersCode'] = $request->smart_no;
        }else{
            $request['billersCode'] = $account_id ?? $mobile_no;
        }
        // Prepare params
        $response = app('App\Http\Controllers\Providers\\' . $api['provider_name'])->prepareParams($request);
    
        // Charge customer
        $old_wallet = $user->wallets;
        $service_price = !empty($service_price) ? $service_price : $request['service_price'];

        if ($user->wallets < $service_price) {
            return [
                'status' => 'failed',
                'message' => 'Not enough wallet balance',
                'error' => 'Not enough wallet balance',
            ];
        }

        $new_wallet = $user->wallets - $service_price;
        
        $user->wallets = $new_wallet;
        $user->save();
        
        // Log transaction
        $data = [
            'mobile_number' => $mobile_no,
            'buyer_email' => $user->email,
            'buyer_username' => $user->username,
            'buyer_service' => $request->network_module ?? $request->service_module,
            'buyer_amount' => $service_price,
            'buyer_prebal' => $old_wallet,
            'buyer_postbal' => $new_wallet,
            'buyer_descr' => '',
            'buyer_status' => 'INITIATED',
            'buyer_date' => $time,
            'timestring' => $timestring,
            'charge_from' => 'WALLET',
            'user_type' => $user->level,
            'sys_ref' => $request_id ,
            'api_ref' => $api_reference,
            'api_report' => 'PENDING',
            'src_price' => '0',
            'profit' => '0',
            'databyte' => $databyte,
            'buyer_quantity' => $no_of_pins,
            'post_fields' => json_encode($response['postfields']['payload'] ?? []),
            'provider' => $response['provider'] ?? null,
        ];
        
        $transaction = $this->logTransaction($data);
        // Make Api call
        $api_response = app('App\Http\Controllers\Providers\\' . $api['provider_name'])->query($response,$request->all(), $type);
    
        if($api_response['status'] == 'success'){
            $mobile_no = $request->smart_no ?? $mobile_no;
            
            $report = $this->formatOderReport($package_name, $mobile_no, $network, $old_wallet, $new_wallet, $time, $service_price, $api_response);

            $order_report = $report['order_report'] ?? '';
            $final_response = $report['final_response'];
            
            $transaction->update([
                'buyer_status' => 'SUCCESSFUL',
                'buyer_descr' => $order_report,
                'api_response' => $api_response['api_response'],
                'api_report' => 'DONE',
                'extra' => $api_response['extra'] ?? null
            ]);

            $body = 'Dear '.auth()->user()->full_name . '<br>The transaction below was initiated on your account '.config('app.php'). '<br><br>
            Amount Charged : ₦'. number_format($transaction->buyer_amount). '<br>
            Previous Balance : ₦'. number_format($old_wallet) . '<br>
            Current Balance : ₦'. number_format($new_wallet) .'<br>
            Description : '. $order_report . '<br>
            Transaction Reference : ' . $transaction->sys_ref . '<br>
            Transaction Date : ' . $transaction->buyer_date . '<br>
            Transaction Status : Success<br>';

            if(isset($api_response['extra']) && !empty($api_response['extra'])){
                $body .= 'Extra Information : ' . $api_response['extra'];
            }
            
            $body .= '<br><br><br>Warm Regards<br>'.config('app.name');

            $subject = "Transaction Notification";
            $this->sendEmail(auth()->user()->email, $subject, $body);
        }else if($api_response['status'] == 'attention-required'){

            $report = $this->formatOderReport($package_name, $mobile_no, $network, $old_wallet, $new_wallet, $time, $service_price, $api_response);

            $order_report = $report['order_report'] ?? '';
            $final_response = $report['final_response'];

            $transaction->update([
                'buyer_status' => 'ATTENTION-REQUIRED',
                'buyer_descr' => $order_report,
                'api_response' => $api_response['api_response'],
                'api_report' => 'DONE',
                'failure_reason' => $api_response['error']
            ]);

            $body = 'Dear ' . auth()->user()->email . 'The transaction below was initiated on your account ' . config('app.php') . '<br><br>
            Amount Charged : ' . number_format($transaction->buyer_amount) . '<br>
            Previous Balance : ' . number_format($transaction->prebal) . '<br>
            Current Balance : ' . number_format($transaction->postbal) . '<br>
            Description : ' . $transaction->$order_report . '<br>
            Transaction Reference : ' . $transaction->sys_ref . '<br>
            Transaction Date : ' . $transaction->buyer_date . '<br>
            Extra Information : ' . $transaction->buyer_date . '<br><br><br>Warm Regards<br>' . config('app.name');

            $subject = "Transaction Notification";
        }else{
            $report = $this->formatOderReport($package_name, $mobile_no, $network, $old_wallet, $new_wallet, $time, $service_price, $api_response);

            $order_report = $report['order_report'];
            $final_response = $report['final_response'];

            $transaction->update([
                'buyer_status' => 'FAILED',
                'buyer_descr' => $order_report,
                'buyer_postbal' => $old_wallet,
                'api_response' => $api_response['api_response'],
                'api_report' => 'FAILED',
                'failure_reason' => $api_response['error'],
            ]);

            $user->wallets = $old_wallet;
            $user->save(); 
        }
        
        return response()->json($final_response);
    }

    public function formatOderReport($package_name, $mobile_no, $network, $old_wallet, $new_wallet, $time, $service_price,$api_response){
        if ($api_response['status'] == 'success') {
            $order_report = strtoupper('Purchase Successful for ' . $package_name . ($network != 'MTN-DATACARD' ? ' on ' . $mobile_no : '')) . '. ' . $api_response['response'];
            $extra = $api_response['extra'] ?? '';
            
            if ($network == 'SMILE-DATA') {
                $order_report = strtoupper($package_name. ' N' . $service_price . ' Purchase Successful on ' . $mobile_no);
            } elseif ($network == 'SEPCTRANET-DATA') {
                $order_report = strtoupper($package_name. ' N' . $service_price . ' Purchase Successful on ' . $mobile_no . ' [' . $extra . ']');
            } 

            $final_response = [
                'status' => 'success',
                'status_code' => '200',
                'old_balance' => $old_wallet, ////// 
                'new_balance' => $new_wallet, ////// 
                'time' => $time,
                'amountPaid' => $service_price,
                'message' => $order_report,
                'extra' => $api_response['extra'] ?? null,
            ];
        }else if($api_response['status'] == 'attention-required'){
            $order_report = strtoupper('Purchase successful for ' . $package_name . ($network != 'MTN-DATACARD' ? ' on ' . $mobile_no : ''));

            $final_response = [
                'status' => 'success',
                'status_code' => '200',
                'old_balance' => $old_wallet, ////// 
                'new_balance' => $new_wallet, ////// 
                'time' => $time,
                'amountPaid' => $service_price,
                'message' => $order_report,
            ];
        }else{
            if(!empty($api_response['response'])){
                $order_report = strtoupper('Purchase failed for ' . $package_name . ' on ' . $mobile_no . ' Error: ' . $api_response['response']);
            }else{
                $order_report = strtoupper('Purchase failed for ' . $package_name . ' on ' . $mobile_no);
            }

            if ($network == 'SMILE-DATA') {
                $order_report = strtoupper($network. ' N' . $service_price . ' Purchase failed on ' . $mobile_no);
            } elseif ($network == 'SEPCTRANET-DATA') {
                $order_report = strtoupper($network. ' N' . $service_price . ' E-pin Purchase failed on ' . $mobile_no);
            }

            $final_response = array(
                'status' => 'fail',
                'status_code' => '100',
                'old_balance' => $old_wallet, ////// 
                'new_bal' => $old_wallet, ////// 
                'time' => $time,
                'message' => $order_report
            );
        }
        
        return [
            'order_report' => $order_report,
            'final_response' => $final_response
        ];
        
    }

    public function discounts(Request $request)
    {
        $webSetting = webSetting::first(); 
        $service = strtolower($request->services);
        $discount = 0;
        if ($service == "mtn-vtu") {
            $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->mtn_discount;
        }

        if ($service == "glo-vtu") {
            $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->glo_discount;
        }

        if ($service == "airtel-vtu") {
            $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->airtel_discount;
        }

        if ($service == "9mobile-vtu") {
            $discount = \DB::table('airtimevtu_plan')->where('id', $request->level)->first()->mobile_discount;
        }
    
        if ($service == "smile-vtu") {
            $discount = $webSetting->smile_vtu_discount;
        }
        ///AIRTIME VTU-END

        //AIRTIME SNS-START

        if ($service == "mtn-sns") {
            $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->mtn_discount;
        }

        if ($service == "glo-sns") {
            $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->glo_discount;
        }

        if ($service == "airtel-sns") {
            $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->airtel_discount;
        }

        if ($service == "9mobile-sns") {
            $discount = \DB::table('airtimesns_plan')->where('id', $request->level)->first()->mobile_discount;
        }

        //AIRTIME SWAP-START
        if ($service == "mtn-swap") {
            $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->mtn_rate;
        }

        if ($service == "glo-swap") {
            $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->glo_rate;
        }

        if ($service == "airtel-swap") {
            $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->airtel_rate;
        }

        if ($service == "9mobile-swap") {
            $discount = \DB::table('airtimeswap_plan')->where('id', $request->level)->first()->mobile_rate;
        }

        // Electricity
        if ($service == "aba-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->aba_discount;
        }

        if ($service == "abuja-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->abuja_discount;
        }

        if ($service == "eko-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->eko_discount;
        }

        if ($service == "ibadan-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->ibadan_discount;
        }
        
        if ($service == "ikeja-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->ikeja_discount;
        }

        if ($service == "kaduna-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->kaduna_discount;
        }

        if ($service == "kano-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->kano_discount;
        }

        if ($service == "jos-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->jos_discount;
        }

        if ($service == "portharcourt-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->portharcourt_discount;
        }

        if ($service == "benin-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->benin_discount;
        }

        if ($service == "enugu-electric") {
            $discount = \DB::table('bills_planlists')->where('id', $request->level)->first()->enugu_discount;
        }
    
        // Gotv
        if(!empty($request->ajax)){
            // $echo = '<option value="">xdvxc</option>';
            $echo = $this->reFormatDiscount($service, $discount);
            return $echo;
            // return response()->json($this->reFormatDiscount($service, $discount));
            // return response()->json($discount);
        }else{
            return $discount;
        }
    }

    public function reFormatDiscount($getservices, $discount){
        if(empty($discount) || $discount < 1){
            $message = '';
        }
        if ($getservices == "abuja-electric") {
            if ($discount > 0) {
                $message = '<div class="alert alert-success alert-dismissible"><strong class="discount-text">' . $discount . '% Discount applies for you</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>';
            }
        }

        if ($getservices == "ibadan-electric") {
            if ($discount > 0) {
                $message =  '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }

        if ($getservices == "eko-electric") {
            if ($discount > 0) {
                $message = '
                <div class="alert alert-success alert-dismissible">
                    <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                    <button type="button" class="btn-close" 
                    data-bs-dismiss="alert"
                    aria-label="Close">
                    </button>
                    </div>
                </div>
                ';
            }
        }

        if ($getservices == "jos-electric") {
            if ($discount > 0) {
                $message = '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }

        if ($getservices == "kaduna-electric") {
            if ($discount > 0) {
                $message = '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }

        if ($getservices == "kano-electric") {
            if ($discount > 0) {
                $message = '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }
        
        if ($getservices == "ikeja-electric") {
            if ($discount > 0) {
                $message = '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }

        if ($getservices == "portharcourt-electric") {
            if ($discount > 0) {
                $message = '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }

        if ($getservices == "benin-electric") {
            if ($discount > 0) {
                $message = '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }

        if ($getservices == "enugu-electric") {
            if ($discount > 0) {
                $message = '
                    <div class="alert alert-success alert-dismissible">
                        <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                        <button type="button" class="btn-close" 
                        data-bs-dismiss="alert"
                        aria-label="Close">
                        </button>
                        </div>
                    </div>
                ';
            }
        }

        if ($getservices == "aba-electric") {
            if ($discount > 0) {
                $message =  '
                <div class="alert alert-success alert-dismissible">
                    <strong class="discount-text">' . $discount . '% Discount applies for you</strong>
                    <button type="button" class="btn-close" 
                    data-bs-dismiss="alert"
                    aria-label="Close">
                    </button>
                    </div>
                </div>
            ';
            }
        }
        return $message;

    }

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
            $transactions = $transactions->where('sys_reference', $request->sys_reference);
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
}
