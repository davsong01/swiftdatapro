<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Api;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Settings;
use App\Models\Variation;
use App\Models\WebSetting;
use App\Mail\EmailMessages;
use App\Models\Transactions;
use App\Models\CustomerLevel;
use Illuminate\Support\Carbon;
use App\Models\AutomatedDeposit;
use App\Models\DepositTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function populateData(){
        try {
            // Update user id in final transaction table
            // Update user id in final transaction table
            //code...
            $web_settings = DB::connection('old')->table('web_settings')->first();
            Api::truncate();
            Product::truncate();
            Category::truncate();

            $apis = [
                [
                    'name' => 'EasyAccess',
                    'status' => 'active',
                    'api_key' => $web_settings->easyaccess_token,
                    'secret_key' => '',
                    'public_key' => '',
                    'slug' => 'easyaccess',
                    'live_base_url' => 'https://easyaccess.com.ng/api/',
                    'sandbox_base_url' => 'https://easyaccess.com.ng/api/'
                ],
                [
                    'name' => 'Vtpass',
                    'status' => 'active',
                    'api_key' => 'f742eb6055ff05d109de74caaf563b4f',
                    'secret_key' => 'SK_14525bc8c1f7e82026cadc079fcdb892d3c83d58025',
                    'public_key' => 'PK_84802bc898ef45c19317552eea90c660efaa2f67122',
                    'slug' => 'easyaccess',
                    'live_base_url' => 'https://api-service.vtpass.com/api/',
                    'sandbox_base_url' => 'https://sandbox.vtpass.com/api/'
                ],
                [
                    'name' => 'Gongzo Data',
                    'status' => 'active',
                    'api_key' => '3b24d3f969dd1aa39cc434ada0c4de75f54e4948',
                    'secret_key' => '',
                    'public_key' => '',
                    'slug' => 'gongzo-data',
                    'live_base_url' => 'https://www.gongozconcept.com/api/',
                    'sandbox_base_url' => 'https://www.gongozconcept.com/api/'
                ],
                [
                    'name' => 'Naija Result Pins',
                    'status' => 'active',
                    'api_key' => $web_settings->naijaresultpins_api_key,
                    'secret_key' => '',
                    'public_key' => '',
                    'slug' => 'naija-result-pins',
                    'live_base_url' => 'https://www.naijaresultpins.com/api/v1/',
                    'sandbox_base_url' => 'https://www.naijaresultpins.com/api/v1/'
                ],
                [
                    'name' => 'Club Konnect',
                    'status' => 'active',
                    'api_key' => $web_settings->clubkonnect_api_key,
                    'secret_key' => $web_settings->clubkonnect_user_id,
                    'public_key' => '',
                    'slug' => 'club-konnect',
                    'live_base_url' => 'https://www.nellobytesystems.com/',
                    'sandbox_base_url' => 'https://www.nellobytesystems.com/'
                ],
                [
                    'name' => 'SME Plug',
                    'status' => 'active',
                    'api_key' => $web_settings->smeplug_token,
                    'secret_key' => '',
                    'public_key' => '',
                    'slug' => 'sme-plug',
                    'live_base_url' => 'https://smeplug.ng/api/v1/vtu',
                    'sandbox_base_url' => 'https://smeplug.ng/api/v1/vtu'
                ],
                [
                    'name' => 'Airtime Exchange',
                    'status' => 'active',
                    'api_key' => '',
                    'secret_key' => '',
                    'public_key' => '',
                    'slug' => 'airtime2cash',
                    'live_base_url' => '',
                    'sandbox_base_url' => ''
                ],
            ];

            foreach($apis as $api){
                Api::updateOrCreate([
                    'name' => $api['name'],
                    'status' => $api['status'],
                    'api_key' => $api['api_key'],
                    'secret_key' => $api['secret_key'],
                    'public_key' => $api['public_key'],
                    'slug' => $api['slug'],
                    'live_base_url' => $api['live_base_url'],
                    'sandbox_base_url' => $api['sandbox_base_url']
                ]);
            }
            // Create Customer Levels
            // $web_settings = json_decode($web_settings, true);
            // dd($web_settings);
            $count = 0;
            
            for($i=2;$i<=4;$i++){
                $charge = 'level' . $i . '_charge';
                $admin = 'level' . $i . '_adminch';
                // dd($charge, $web_settings->$charge);
                CustomerLevel::updateOrCreate(['name' => 'Level '.$i, 'order' => $i],[
                    'name' => 'Level ' . $i,
                    'order' => $i,
                    'upgrade_amount' => $web_settings->$charge,
                    'admin_inch' => $web_settings->$admin,
                ]);
            }        
            dump('Customer level updated');

            // Create categories
            $categories = [
                [
                    "name" => 'Data',
                    "icon" => '',
                    "display_name" => 'Data',
                    "slug" => 'data',
                    "status" => 'active',
                    "order" => 1,
                    "description" => 'Buy data',
                    "seo_description" => 'Buy data',
                    "seo_title" => 'Buy data',
                    "seo_keywords" => 'Buy data',
                    "unique_element" => 'phone',
                    "discount_type" => 'flat',
                ],
                [
                    "name" => 'Airtime VTU',
                    "icon" => '',
                    "display_name" => 'Airtime VTU',
                    "slug" => 'airtime',
                    "status" => 'active',
                    "order" => 2,
                    "description" => 'Buy airtime',
                    "seo_description" => 'Buy airtime',
                    "seo_title" => 'Buy airtime',
                    "seo_keywords" => 'Buy airtime',
                    "unique_element" => 'phone',
                    "discount_type" => 'flat',
                ],
                [
                    "name" => 'Airtime SNS',
                    "icon" => '',
                    "display_name" => 'Airtime SNS',
                    "slug" => 'airtime-sns',
                    "status" => 'active',
                    "order" => 3,
                    "description" => 'Buy airtime SNS',
                    "seo_description" => 'Buy airtime SNS',
                    "seo_title" => 'Buy airtime SNS',
                    "seo_keywords" => 'Buy airtime SNS',
                    "unique_element" => 'phone',
                    "discount_type" => 'flat',
                ],
                [
                    "name" => 'Cable TV',
                    "icon" => '',
                    "display_name" => 'Cable TV',
                    "slug" => 'cable',
                    "status" => 'active',
                    "order" => 5,
                    "description" => 'Buy Cable TV',
                    "seo_description" => 'Buy Cable TV',
                    "seo_title" => 'Buy Cable TV',
                    "seo_keywords" => 'Buy Cable TV',
                    "unique_element" => 'iuc_number',
                    "discount_type" => 'flat',
                ],
                [
                    "name" => 'Utility Bills',
                    "icon" => '',
                    "display_name" => 'Utility Bills',
                    "slug" => 'electricity',
                    "status" => 'active',
                    "order" => 6,
                    "description" => 'Buy utility bills',
                    "seo_description" => 'Buy utility bills',
                    "seo_title" => 'Buy utility bills',
                    "seo_keywords" => 'Buy utility bills',
                    "unique_element" => 'meter_number',
                    "discount_type" => 'flat',
                ],
                [
                    "name" => 'Exam Epins',
                    "icon" => '',
                    "display_name" => 'Exam Epins',
                    "slug" => 'exam-epins',
                    "status" => 'active',
                    "order" => 7,
                    "description" => 'Buy Exam Epins',
                    "seo_description" => 'Buy Exam Epins',
                    "seo_title" => 'Buy Exam Epins',
                    "seo_keywords" => 'Buy Exam Epins',
                    "unique_element" => 'phone',
                    "discount_type" => 'flat',
                ],

                [
                    "name" => 'Bulk SMS',
                    "icon" => '',
                    "display_name" => 'Order Bulk SMS',
                    "slug" => 'order-bulk-sms',
                    "status" => 'active',
                    "order" => 8,
                    "description" => 'Buy Order Bulk SMS',
                    "seo_description" => 'Buy Order Bulk SMS',
                    "seo_title" => 'Buy Order Bulk SMS',
                    "seo_keywords" => 'Buy Order Bulk SMS',
                    "unique_element" => 'phone',
                    "discount_type" => 'flat',
                ],
                [
                    "name" => 'Graphics',
                    "icon" => '',
                    "display_name" => 'Bulk SMS',
                    "slug" => 'bulk-sms',
                    "status" => 'active',
                    "order" => 9,
                    "description" => 'Buy Bulk SMS',
                    "seo_description" => 'Buy Bulk SMS',
                    "seo_title" => 'Buy Bulk SMS',
                    "seo_keywords" => 'Buy Bulk SMS',
                    "unique_element" => 'phone',
                    "discount_type" => 'flat',
                ],
                [
                    "name" => 'Exchange Airtime',
                    "icon" => '',
                    "display_name" => 'Exchange Airtime',
                    "slug" => 'airtime2cash',
                    "status" => 'active',
                    "order" => 9,
                    "description" => 'Buy Bulk SMS',
                    "seo_description" => 'Buy Bulk SMS',
                    "seo_title" => 'Buy Bulk SMS',
                    "seo_keywords" => 'Buy Bulk SMS',
                    "unique_element" => 'phone',
                    "discount_type" => 'percentage',
                ],

            ];

            foreach($categories as $category){
                Category::updateOrCreate([
                    "name" => $category['name'],
                    "icon" => $category['icon'],
                    "display_name" => $category['display_name'],
                    "slug" => $category['slug'],
                    "status" => $category['status'],
                    "order" => $category['order'],
                    "description" => $category['description'],
                    "seo_description" => $category['seo_description'],
                    "seo_title" => $category['seo_title'],
                    "seo_keywords" => $category['seo_keywords'],
                    "unique_element" => $category['unique_element'],
                    "discount_type" => $category['discount_type'],
                ]);
            }

            dump('Categories created');

            // Create Products
            $categories = Category::all();
            foreach($categories as $category){
                if($category->slug == 'data'){
                    $toAdd = [
                        [
                            "name" => 'Mtn SME',
                            "display_name" => 'Mtn SME',
                            "category_id" => $category->id,
                            "slug" => 'mtn-sme',
                            "api_id" => 3,
                            "old_identifier" => 'mtnsmedata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Mtn CG',
                            "display_name" => 'Mtn CG',
                            "category_id" => $category->id,
                            "slug" => 'mtn-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mtncgdata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Mtn datacard',
                            "display_name" => 'Mtn datacard',
                            "category_id" => $category->id,
                            "slug" => 'mtn-datacard',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mtndata_card_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'GLO Gifting',
                            "display_name" => 'GLO Gifting',
                            "category_id" => $category->id,
                            "slug" => 'glo-gifting',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'glocgdata_plan',
                            "fixed_price" => 'yes',
                        ],

                        [
                            "name" => 'GLO CG',
                            "display_name" => 'GLO CG',
                            "category_id" => $category->id,
                            "slug" => 'glo-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'glodata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Airtel Gifting',
                            "display_name" => 'Airtel Gifting',
                            "category_id" => $category->id,
                            "slug" => 'airtel-gifting',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'airtelcgdata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Airtel CG',
                            "display_name" => 'Airtel CG',
                            "category_id" => $category->id,
                            "slug" => 'airtel-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'airteldata_plan',
                            "fixed_price" => 'yes',
                        ],

                        [
                            "name" => 'Airtel CG',
                            "display_name" => 'Airtel CG',
                            "category_id" => $category->id,
                            "slug" => 'airtel-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'airteldata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => '9Mobile',
                            "display_name" => '9Mobile',
                            "category_id" => $category->id,
                            "slug" => '9mobile',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mobiledata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => '9Mobile SME',
                            "display_name" => '9Mobile SME',
                            "category_id" => $category->id,
                            "slug" => 'apimobile-sme',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mobilesmedata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Spectranet Data',
                            "display_name" => 'Spectranet Data',
                            "category_id" => $category->id,
                            "slug" => 'spectranet-data',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'spectranetdata_plan',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Smile Data',
                            "display_name" => 'Smile Data',
                            "category_id" => $category->id,
                            "slug" => 'smile-data',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'smiledata_plan',
                            "fixed_price" => 'yes',
                        ],
                    ];
                }

                if ($category->slug == 'cable') {
                    $toAdd = [
                        [
                            "name" => 'GOTV',
                            "display_name" => 'GOTV',
                            "category_id" => $category->id,
                            "slug" => 'gotv',
                            "api_id" => 2,
                            "old_identifier" => 'gotv_planlist',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'DSTV',
                            "display_name" => 'DSTV',
                            "category_id" => $category->id,
                            "slug" => 'dstv',
                            "has_variations" => 'yes',
                            "api_id" => 2,
                            "old_identifier" => 'dstv_planlist',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Showmax',
                            "display_name" => 'Showmax',
                            "category_id" => $category->id,
                            "slug" => 'showmax',
                            "has_variations" => 'yes',
                            "api_id" => 2,
                            "old_identifier" => 'showmax_planlist',
                            "fixed_price" => 'yes',
                        ],
                        [
                            "name" => 'Startimes',
                            "display_name" => 'Startimes',
                            "category_id" => $category->id,
                            "slug" => 'startime',
                            "has_variations" => 'yes',
                            "api_id" => 2,
                            "old_identifier" => 'startime_planlist',
                            "fixed_price" => 'yes',
                        ],
                    ];
                }

                if ($category->slug == 'electricity') {
                    $toAdd = [
                        [
                            "name" => 'Abuja Electric',
                            "display_name" => 'Abuja Electric',
                            "category_id" => $category->id,
                            "slug" => 'abuja-electric',
                            "api_id" => 2,
                            "old_identifier" => 'abuja',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Ibadan Electric',
                            "display_name" => 'Ibadan Electric',
                            "category_id" => $category->id,
                            "slug" => 'ibadan-electric',
                            "api_id" => 2,
                            "old_identifier" => 'ibadan',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Eko Electric',
                            "display_name" => 'Eko Electric',
                            "category_id" => $category->id,
                            "slug" => 'eko-electric',
                            "api_id" => 2,
                            "old_identifier" => 'eko',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Ikeja Electric',
                            "display_name" => 'Ikeja Electric',
                            "category_id" => $category->id,
                            "slug" => 'ikeja-electric',
                            "api_id" => 2,
                            "old_identifier" => 'ikeja',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Portharcourt Electric',
                            "display_name" => 'Portharcourt Electric',
                            "category_id" => $category->id,
                            "slug" => 'portharcourt-electric',
                            "api_id" => 2,
                            "old_identifier" => 'portharcourt',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Jos Electric',
                            "display_name" => 'Jos Electric',
                            "category_id" => $category->id,
                            "slug" => 'jos-electric',
                            "api_id" => 2,
                            "old_identifier" => 'jos',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Kaduna Electric',
                            "display_name" => 'Kaduna Electric',
                            "category_id" => $category->id,
                            "slug" => 'kaduna-electric',
                            "api_id" => 2,
                            "old_identifier" => 'kaduna',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Kano Electric',
                            "display_name" => 'Kano Electric',
                            "category_id" => $category->id,
                            "slug" => 'kano-electric',
                            "api_id" => 2,
                            "old_identifier" => 'kano',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Benin Electric',
                            "display_name" => 'Benin Electric',
                            "category_id" => $category->id,
                            "slug" => 'benin-electric',
                            "api_id" => 2,
                            "old_identifier" => 'benin',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Enugu Electric',
                            "display_name" => 'Enugu Electric',
                            "category_id" => $category->id,
                            "slug" => 'enugu-electric',
                            "api_id" => 2,
                            "old_identifier" => 'enugu',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Aba Electric',
                            "display_name" => 'Aba Electric',
                            "category_id" => $category->id,
                            "slug" => 'aba-electric',
                            "api_id" => 2,
                            "old_identifier" => 'aba',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => 'Yola Electric',
                            "display_name" => 'Yola Electric',
                            "category_id" => $category->id,
                            "slug" => 'yola-electric',
                            "api_id" => 2,
                            "old_identifier" => 'yola',
                            "fixed_price" => 'no',
                        ],
                    ];
                }

                if ($category->slug == 'exam-epins') {
                    $prices = \DB::connection('old')->table('epin_planlist')->first();
                    $toAdd = [
                        [
                            "name" => 'WAEC Scratch Card',
                            "display_name" => 'WAEC Scratch Card',
                            "category_id" => $category->id,
                            "slug" => 'waec-result',
                            "api_id" => 4,
                            "old_identifier" => 'waec',
                            'system_price' => $prices->waec,
                            "fixed_price" => 'yes',
                            "has_variations" => 'no',
                        ],
                        [
                            "name" => 'NECO Result Checker',
                            "display_name" => 'NECO Result Checker',
                            "category_id" => $category->id,
                            "slug" => 'neco-result',
                            "api_id" => 4,
                            "old_identifier" => 'neco',
                            "fixed_price" => 'yes',
                            "has_variations" => 'no',
                            'system_price' => $prices->neco,

                        ],
                        [
                            "name" => 'NABTEB Result Checker',
                            "display_name" => 'NABTEB Result Checker',
                            "category_id" => $category->id,
                            "slug" => 'nabteb-result',
                            "api_id" => 4,
                            "old_identifier" => 'nabteb',
                            "fixed_price" => 'yes',
                            "has_variations" => 'no',
                            'system_price' => $prices->nabteb,

                        ],
                        [
                            "name" => 'GCE Result Checker',
                            "display_name" => 'GCE Result Checker',
                            "category_id" => $category->id,
                            "slug" => 'gce-result',
                            "api_id" => 4,
                            "old_identifier" => 'gce',
                            "fixed_price" => 'yes',
                            "has_variations" => 'no',
                            'system_price' => $prices->gce
                        ],
                        [
                            "name" => 'WAEC Registration Card',
                            "display_name" => 'WAEC Registration Card',
                            "category_id" => $category->id,
                            "slug" => 'waec-registration',
                            "api_id" => 4,
                            "old_identifier" => 'waec_registration',
                            "fixed_price" => 'yes',
                            "has_variations" => 'no',
                            'system_price' => $prices->waec_registration
                        ],
                        [
                            "name" => 'NBAIS',
                            "display_name" => 'NBAIS',
                            "category_id" => $category->id,
                            "slug" => 'nbais',
                            "api_id" => 4,
                            "old_identifier" => 'nbais',
                            "fixed_price" => 'yes',
                            "has_variations" => 'no',
                            'system_price' => $prices->nbais
                        ],
                    ];
                }

                if ($category->slug == 'airtime') {
                    $toAdd = [
                        [
                            "name" => 'MTN VTU',
                            "display_name" => 'MTN VTU',
                            "category_id" => $category->id,
                            "slug" => 'mtn',
                            "api_id" => 1,
                            "old_identifier" => 'mtn-vtu',
                            "fixed_price" => 'no',
                            "has_variations" => 'no',
                        ],
                        [
                            "name" => 'GLO VTU',
                            "display_name" => 'GLO VTU',
                            "category_id" => $category->id,
                            "slug" => 'glo',
                            "api_id" => 1,
                            "old_identifier" => 'glo-vtu',
                            "fixed_price" => 'no',
                            "has_variations" => 'no',

                        ],
                        [
                            "name" => 'AIRTEL VTU',
                            "display_name" => 'AIRTEL VTU',
                            "category_id" => $category->id,
                            "slug" => 'airtel',
                            "api_id" => 1,
                            "old_identifier" => 'airtel-vtu',
                            "fixed_price" => 'no',
                            "has_variations" => 'no',

                        ],
                        [
                            "name" => '9MOBILE VTU',
                            "display_name" => '9MOBILE VTU',
                            "category_id" => $category->id,
                            "slug" => 'etisalat',
                            "api_id" => 1,
                            "old_identifier" => '9mobile-vtu',
                            "fixed_price" => 'no',
                            "has_variations" => 'no',

                        ],
                        [
                            "name" => 'SMILE VTU',
                            "display_name" => 'SMILE VTU',
                            "category_id" => $category->id,
                            "slug" => 'smile',
                            "api_id" => 1,
                            "old_identifier" => 'smile-vtu',
                            "fixed_price" => 'no',
                            "has_variations" => 'no',

                        ],
                    ];
                }

                if ($category->slug == 'airtime-sns') {
                    $toAdd = [
                        [
                            "name" => 'MTN SNS',
                            "display_name" => 'MTN VTU',
                            "category_id" => $category->id,
                            "slug" => 'mtn-sns',
                            "api_id" => 6,
                            "old_identifier" => 'mtn-sns',
                            "fixed_price" => 'no',
                            "has_variations" => 'no',
                        ],
                        [
                            "name" => 'GLO SNS',
                            "display_name" => 'GLO SNS',
                            "category_id" => $category->id,
                            "slug" => 'glo-sns',
                            "api_id" => 6,
                            "old_identifier" => 'glo-SNS',
                            "fixed_price" => 'no',
                            "has_variations" => 'no',
                        ],
                        [
                            "name" => 'AIRTEL SNS',
                            "display_name" => 'AIRTEL SNS',
                            "category_id" => $category->id,
                            "slug" => 'airtel-sns',
                            "api_id" => 6,
                            "old_identifier" => 'airtel-sns',
                            "has_variations" => 'no',
                            "fixed_price" => 'no',
                        ],
                        [
                            "name" => '9MOBILE SNS',
                            "display_name" => '9MOBILE SNS',
                            "category_id" => $category->id,
                            "slug" => 'etisalat-sns',
                            "api_id" => 6,
                            "old_identifier" => '9mobile-sns',
                            "has_variations" => 'no',
                            "fixed_price" => 'no',
                        ],
                    ];
                }

                if ($category->slug == 'airtime2cash') {
                    $toAdd = [
                        [
                            "name" => 'MTN',
                            "display_name" => 'MTN',
                            "category_id" => $category->id,
                            "slug" => 'mtn-exchange',
                            "status" => 'active',
                            "has_variations" => 'no',
                            "api_id" => env('EXCHANGE_AIRTIME_API_ID'),
                            "fixed_price" => 'no',
                            "type" => 'airtime2cash',
                        ],
                        [
                            "name" => 'GLO',
                            "display_name" => 'GLO',
                            "category_id" => $category->id,
                            "slug" => 'glo-exchange',
                            "status" => 'active',
                            "has_variations" => 'no',
                            "api_id" => env('EXCHANGE_AIRTIME_API_ID'),
                            "fixed_price" => 'no',
                            "type" => 'airtime2cash',
                        ],
                        [
                            "name" => 'AIRTEL',
                            "display_name" => 'AIRTEL',
                            "category_id" => $category->id,
                            "slug" => 'airtel-exchange',
                            "status" => 'active',
                            "has_variations" => 'no',
                            "api_id" => env('EXCHANGE_AIRTIME_API_ID'),
                            "fixed_price" => 'no',
                            "type" => 'airtime2cash',
                        ],
                        [
                            "name" => '9MOBILE',
                            "display_name" => '9MOBILE',
                            "category_id" => $category->id,
                            "slug" => '9mobile-exchange',
                            "has_variations" => 'no',
                            "status" => 'active',
                            "api_id" => env('EXCHANGE_AIRTIME_API_ID'),
                            "fixed_price" => 'no',
                            "type" => 'airtime2cash',
                        ],
                    ];
                }

                foreach ($toAdd as $product) {
                    $check = Product::where('slug',$product['slug'])->first();
                    if($check){
                        continue;
                    }
            
                    Product::updateOrCreate(
                        [
                            "name" => $product['name'],
                            "display_name" => $product['display_name'],
                            "category_id" => $category->id,
                            "slug" => $product['slug'],
                            "old_identifier" => $product['old_identifier'] ?? null,
                            "has_variations" => $product['has_variations'] ?? 'yes',
                            "api_id" => $product['api_id'] ?? '1',
                        ],
                        
                        [
                            "name" => $product['name'],
                            "display_name" => $product['display_name'],
                            "category_id" => $category->id,
                            "slug" => $product['slug'],
                            "old_identifier" => $product['old_identifier'] ?? null,
                            "api_id" => $product['api_id'] ?? '1',
                            "description" => '',
                            "seo_title" => '',
                            "seo_keywords" => '',
                            "status" => 'active',
                            "has_variations" =>  $product['has_variations'] ?? 'yes',
                            "seo_description" => '',
                            "image" => '',
                            "fixed_price" => $product['fixed_price'] ?? 'no',
                            "system_price" => $product['system_price'] ?? 0,
                            "allow_quantity" => 'no',
                            "quantity_graduation" => '',
                            "min" => 0,
                            "max" => 0,
                            "allow_subscription_type" => 'no',
                            'referral_percentage' => 0,
                            "type" => $product['type'] ?? 'general',

                        ]
                    );
                }
            }

            dump('Products created');

            AutomatedDeposit::truncate();
            // Create user reserved accounts and product access
            $productaccess = Product::pluck('id')->toArray();
            $users = User::all();
            foreach($users as $user){
                if(!empty($user->reserved_account)){
                    $res = !empty($user->reserved_account) ? json_decode($user->reserved_account, true) : [];
                    if(!empty($res['responseBody']) && !empty($res['responseBody']['accounts'])){
                        foreach ($res['responseBody']['accounts'] as $account) {
                            AutomatedDeposit::updateOrCreate(['account_number' => $account['accountNumber']], [
                                'account_reference' => $accounts['response']['responseBody']['accountReference'] ?? null,
                                'account_number' => $account['accountNumber'] ?? null,
                                'account_name' => $account['accountName'] ?? null,
                                'bank_name' => $account['bankName'] ?? null,
                                'bank_code' => $account['bankCode'] ?? null,
                                'payment_gateway_id' => 1,
                                'status' => 'active',
                                'bvn' => $user->bvn ?? null,
                                'nin' => $user->nin ?? null,
                                'raw_response' => $user->reserved_account,
                                'user_id' => $user->id,
                            ]);
                        }
                    }
                }

                if (!empty($user->payvessel_reserved_account)) {
                    $res = !empty($user->payvessel_reserved_account) ? json_decode($user->payvessel_reserved_account, true) : [];
                    
                    if (!empty($res['banks'])) {
                        foreach ($res['banks'] as $account) {
                            AutomatedDeposit::updateOrCreate(['account_number' => $account['accountNumber']],[
                                'account_reference' => $account['trackingReference'] ?? null,
                                'account_number' => $account['accountNumber'] ?? null,
                                'account_name' => $account['accountName'] ?? null,
                                'bank_name' => $account['bankName'] ?? null,
                                'bank_code' => $account['bankCode'] ?? null,
                                'payment_gateway_id' => 2,
                                'status' => 'active',
                                'bvn' => $user->bvn ?? null,
                                'nin' => $user->nin ?? null,
                                'raw_response' => $user->payvessel_reserved_account,
                                'user_id' => $user->id,
                            ]);
                        }
                    }
                }

                $user->update([
                    'product_access' => $productaccess,
                ]);
            }

            dump('Reserved accounts created');

            // Update deposit transactions
            $deposit_transactions = DepositTransaction::whereNull('user_id')->get();

            foreach($deposit_transactions as $transaction){
                $user = User::where('email', $transaction->user_email)->orWhere('username', $transaction->user_username)->first();
                $transaction->update([
                    'user_id' =>$user->id,
                ]);
            }
            dump('Deposit transactions updated');

            \DB::commit();
        } catch (\Throwable $th) {
            dump($th->getMessage());
        }

    }

    public function synchronizeTransactions(){

        $trans = Transactions::whereNull('created_at')->take(50000)->get();
        if (!empty($trans)) {
            foreach ($trans as $transaction) {
                if (empty($transaction->created_at) && !empty($transaction->buyer_date)) {
                    try {
                        $transaction->update([
                            'created_at' => Carbon::parse($transaction->buyer_date),
                        ]);
                    } catch (\Throwable $th) {
                        continue;
                    }
                    
                }
            }
        }

        $transactions = Transactions::whereNull('user_id')->take(5000)->get();

        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                if(empty($transaction->user_id)){
                    $user = User::where('email', $transaction->buyer_email)->first();
                    
                    if ($user) {
                        $transaction->update([
                            'user_id' => $user->id,
                        ]);
                    }
                }

                if(empty($transaction->created_at) && !empty($transaction->buyer_date)){
                    $transaction->update([
                        'created_at' => Carbon::parse($transaction->buyer_date),
                    ]);
                }
                
                if ($transaction->buyer_prebal > $transaction->buyer_postbal) {
                    $transaction->update([
                        'type' => 'debit',
                    ]);
                } else if ($transaction->buyer_prebal < $transaction->buyer_postbal) {
                    $transaction->update([
                        'type' => 'credit',
                    ]);
                }
    
                if (empty($transaction->unit_price) && $transaction->buyer_prebal > 0 && $transaction->buyer_postbal > 0) {
                    $transaction->update([
                        'unit_price' => (float) $transaction->type == 'debit' ? $transaction->buyer_prebal - $transaction->buyer_postbal :  $transaction->buyer_amount,
                    ]);
                }
            }
        }

        $transactionsX = Transactions::whereNull('product_id')->take(5000)->get();
        
        foreach($transactionsX  as $transaction){
            if (empty($transaction->product_id)) {
                $product = Product::where('slug', 'like', $transaction->buyer_service)->first();
                $variation = Variation::where('api_name', 'like', $transaction->buyer_descr)->first();

                if ($product) {
                    $transaction->update([
                        'product_id' => $product->id,
                        'category_id' => $product->category->id
                    ]);
                }

                if ($variation) {
                    $transaction->update([
                        'variation_id' => $variation->id,
                    ]);
                }
            }

            if (empty($transaction->provider_id)) {
                $provider = strtolower($transaction->provider);
                $api = Api::where('slug', 'like', $provider)->first();

                if ($api) {
                    $transaction->update([
                        'provider_id' => $api->id,
                    ]);
                }
            }
        }


        $transactionsXX = Transactions::whereNull('provider_id')->take(5000)->get();

        foreach ($transactionsXX  as $transaction) {
            $provider = strtolower($transaction->provider);
            $api = Api::where('slug', 'like', $provider)->first();

            if ($api) {
                $transaction->update([
                    'provider_id' => $api->id,
                ]);
            }
        }

        // $users = User::where('status', 'active')->orWhere('status','inactive')->get();

        // if (!empty($users)) {
        //     foreach ($users as $user) {
        //         if ($user->status == 'active') {
        //             $user->update([
        //                 'status' => 1,
        //             ]);
        //         }

        //         if ($user->status == 'inactive') {
        //             $user->update([
        //                 'status' => 0,
        //             ]);
        //         }
        //     }
        // }

        $users2 = User::whereNull('email_verified_at')->get();

        if (!empty($users2)) {
            foreach ($users2 as $user) {
                $user->update([
                    'email_verified_at' => now(),
                ]);
            }
        }

        dd('done');
    }

    public function sendEmail($email_to, $subject, $body){
        $data = [
            'subject' => $subject,
            'body' => $body,
        ];

        try {
            Mail::to($email_to,)->send(new EmailMessages($data));
            \Log::info(['Email: '=>$data]);
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }

    public function basicApiCall($url, $payload, $headers, $method = 'POST'){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, url($url));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($method == "GET") {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } elseif ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        } elseif ($method == "PUT") {
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        } elseif (!empty($timeout)) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Auth: ' . $xAuth,
            'token: ' . $token,
        ));

        $response = curl_exec($ch);
        
        // if ($response == false) {
        //     $err =  new \Exception(curl_error($ch), curl_errno($ch));
        //     // \Log::info(['url' => $url, 'payload' => $payload, 'res'=>$response]);
        //     // \Log::error($err);
        //     return (object)['message' => $err->getMessage(), 'code' => $err->getCode()];
        // }
      
        return $response;
    
    }

    public function generateRequestId($suffix=null){        
        if(!empty($suffix)){
            $trx = date("YmdHi") . rand(10000, 90000). '-'.$suffix;
        }else{
            $trx = date("YmdHi") . rand(10000, 90000) . '-SWFT';
        }

        return $trx;
    }

    public function chargeUserWallet(){

    }

    public function logTransaction($request){
        $data = $request;
        $data['app_version'] = Session::get('app_version');
        $data['domain_name'] = Session::get('domain_name');
        $data['ip_address'] = Session::get('ip_address');
        $data['user_id'] = $request['user_id'] ?? auth()->user()->id;
        
        $transaction = Transactions::create($data);
        return $transaction;
    }

    public function updateWalletBalance($user, $amount, $type){
        
        if($type == 'credit'){
            $user->wallets = $user->wallets + $amount;
        }else{
            $user->wallets = $user->wallets - $amount;
        }

        $user->save();

        return $user;
    }

    public function getDomainDetails(){
        
    }

    public function advancedApiCall($url, $payload=[], $headers, $method = 'POST', $status_code=null)
    {
        // \Log::info(['Begin connection...', $url, $payload, $headers]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($method == "GET") {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } elseif ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        } elseif ($method == "PUT") {
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        } elseif (!empty($timeout)) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_exec($ch);
        // \Log::info(['End connection', $url, $payload, $headers, $response]);
        
        if(isset($status_code)){
            return [
                'response' => $response,
                'status_code' => $code
            ];
        }
        
        return $response;
    }

    public function uploadFile($file, $location = null, $width = null, $height = null)
    {
        $folder = $this->getPathToSaveFile($location);
        $name = uniqid(11) . '.' . $file->getClientOriginalExtension();

        if (substr($file->getMimeType(), 0, 5) == 'image') {
            $imageFile = Image::make($file);
            $imgWidth = $width ?? $imageFile->width();
            $imgHeight = $imgHeight ?? $imageFile->height();

            if (!is_null($imgWidth) && !is_null($imgHeight)) {
                if ($imgWidth < $imgHeight) {
                    $imageFile->resize($imgWidth, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $imageFile->resize(null, $imgWidth, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
        }

        $imageFile->save($folder . '/' . $name);

        return $folder . '/' . $name;
    }

    public function getPathToSaveFile($location = null)
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        if ($location) {
            $location = $location . '/' . $year . '/' . $month . '/' . $day;
        } else {
            $location = $year . '/' . $month . '/' . $day;
        }

        if (env('ENT') == 'local') {
            $path = "/Applications/MAMP/htdocs/kingsvtu/public";
        } else {
            $path = '/home/swifvmvz/beta.swiftdatang.com';
        }

        // $path = public_path();
        // 
        
        if (!is_dir($path . '/' . $location)) {
            mkdir($path . '/' . $location, 0777, true);
        }

        return $location;
    }

    public function getApiFilename($api)
    {
        if ($api->slug == 'easyaccess') {
            $controller = 'EasyAccessController';
        } else if ($api->slug == 'vtpass') {
            $controller = 'VtpassController';
        } else if ($api->slug == 'gongzo-data') {
            $controller = 'GongzoController';
        } else if ($api->slug == 'naija-result-pins') {
            $controller = 'NaijaResultPinsController';
        } else if ($api->slug == 'club-konnect') {
            $controller = 'ClubkonnectController';
        } else if ($api->slug == 'sme-plug') {
            $controller = 'SmePlugController';
        }

        return $controller;
    }

    public function sendLoginEmail($user){
        $body = 'Dear ' . $user->full_name . '<br>Your login to ' . config('app.php') . ' at ' . Carbon::now() . ' was successful<br>';
        $body .= '<br><br>Warm Regards<br>' . config('app.name');
        $subject = "Welcome Back!";

        // $this->sendEmail($user->email, $subject, $body);
    }
}
