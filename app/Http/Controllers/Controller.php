<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Settings;
use App\Models\WebSetting;
use App\Mail\EmailMessages;
use App\Models\Transactions;
use App\Models\CustomerLevel;
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
            //code...
        
            // Create Customer Levels
            $web_settings = DB::connection('old')->table('web_settings')->first();
            // $web_settings = json_decode($web_settings, true);
            // dd($web_settings);
            $count = 0;
            for($i=2;$i<=4;$i++){
                $charge = 'level' . $i . '_charge';
                $admin = 'level' . $i . '_adminch';
                // dd($charge, $web_settings->$charge);
                CustomerLevel::updateOrCreate(['name' => 'Level '.$i],[
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
                    "name" => 'Airtime Exchane',
                    "icon" => '',
                    "display_name" => 'Airtime Exchane',
                    "slug" => 'airtime-exchange',
                    "status" => 'active',
                    "order" => 4,
                    "description" => 'Buy airtime Exchange',
                    "seo_description" => 'Buy airtime Exchange',
                    "seo_title" => 'Buy airtime Exchange',
                    "seo_keywords" => 'Buy airtime Exchange',
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
                    "slug" => 'utility-bills',
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
                ]
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
            Product::truncate();
            foreach($categories as $category){
                if($category->slug == 'data'){
                    $toAdd = [
                        [
                            "name" => 'Mtn SME',
                            "display_name" => 'Mtn SME',
                            "category_id" => $category->id,
                            "slug" => 'mtn-sme',
                            "api_id" => 1,
                            "old_identifier" => 'mtnsmedata_plan',
                        ],
                        [
                            "name" => 'Mtn CG',
                            "display_name" => 'Mtn CG',
                            "category_id" => $category->id,
                            "slug" => 'mtn-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mtncgdata_plan',
                        ],
                        [
                            "name" => 'Mtn datacard',
                            "display_name" => 'Mtn datacard',
                            "category_id" => $category->id,
                            "slug" => 'mtn-datacard',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mtndata_card_plan',
                        ],
                        [
                            "name" => 'GLO Gifting',
                            "display_name" => 'GLO Gifting',
                            "category_id" => $category->id,
                            "slug" => 'glo-gifting',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'glocgdata_plan',
                        ],

                        [
                            "name" => 'GLO CG',
                            "display_name" => 'GLO CG',
                            "category_id" => $category->id,
                            "slug" => 'glo-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'glodata_plan',
                        ],
                        [
                            "name" => 'Airtel Gifting',
                            "display_name" => 'Airtel Gifting',
                            "category_id" => $category->id,
                            "slug" => 'airtel-gifting',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'airtelcgdata_plan',
                        ],
                        [
                            "name" => 'Airtel CG',
                            "display_name" => 'Airtel CG',
                            "category_id" => $category->id,
                            "slug" => 'airtel-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'airteldata_plan',
                        ],

                        [
                            "name" => 'Airtel CG',
                            "display_name" => 'Airtel CG',
                            "category_id" => $category->id,
                            "slug" => 'airtel-cg',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'airteldata_plan',
                        ],
                        [
                            "name" => '9Mobile',
                            "display_name" => '9Mobile',
                            "category_id" => $category->id,
                            "slug" => '9mobile',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mobiledata_plan',
                        ],
                        [
                            "name" => '9Mobile SME',
                            "display_name" => '9Mobile SME',
                            "category_id" => $category->id,
                            "slug" => '9mobile-sme',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'mobilesmedata_plan',
                        ],
                        [
                            "name" => 'Spectranet Data',
                            "display_name" => 'Spectranet Data',
                            "category_id" => $category->id,
                            "slug" => 'spectranet-data',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'spectranetdata_plan',
                        ],
                        [
                            "name" => 'Smile Data',
                            "display_name" => 'Smile Data',
                            "category_id" => $category->id,
                            "slug" => 'smile-data',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'smiledata_plan',
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
                            "api_id" => 1,
                            "old_identifier" => 'gotv_planlist',
                        ],
                        [
                            "name" => 'DSTV',
                            "display_name" => 'DSTV',
                            "category_id" => $category->id,
                            "slug" => 'dstv',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'dstv_planlist',
                        ],
                        [
                            "name" => 'Showmax',
                            "display_name" => 'Showmax',
                            "category_id" => $category->id,
                            "slug" => 'showmax',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'showmax_planlist',
                        ],
                        [
                            "name" => 'Startimes',
                            "display_name" => 'Startimes',
                            "category_id" => $category->id,
                            "slug" => 'startime',
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "old_identifier" => 'startime_planlist',
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
                            "old_identifier" => $product['old_identifier'],
                            "has_variations" => 'yes',
                            "api_id" => 1,
                        ],

                        [
                            "name" => $product['name'],
                            "display_name" => $product['display_name'],
                            "category_id" => $category->id,
                            "slug" => $product['slug'],
                            "old_identifier" => $product['old_identifier'],
                            "has_variations" => 'yes',
                            "api_id" => 1,
                            "has_variations" => 'yes',
                            "description" => '',
                            "seo_title" => '',
                            "seo_keywords" => '',
                            "status" => 'active',
                            "has_variations" => 'yes',
                            "seo_description" => '',
                            "image" => '',
                            "fixed_price" => 0,
                            "system_price" => 0,
                            "allow_quantity" => '',
                            "quantity_graduation" => '',
                            "min" => 0,
                            "max" => 0,
                            "allow_subscription_type" => 'no',
                            'referral_percentage' => 0,
                        ]
                    );
                }
            }

            dump('Variation Products created');

            
        } catch (\Throwable $th) {
            dump($th->getMessage());
        }

    }

    public function sendEmail($email_to, $subject, $body){
        // $headers = 'MIME-Version: 1.0' . "\r\n";
        // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // $headers .= 'From: ' . $email_from . "\r\n" .
        //     'Reply-To: ' . $email_from . "\r\n" .
        //     'X-Mailer: PHP/' . phpversion();
        // mail($email_to, $subject, $body, $headers);
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

    public function chargeUserWallet(){

    }

    public function logTransaction($data){
        $data['app_version'] = Session::get('app_version');
        $data['domain_name'] = Session::get('domain_name');
        $data['ip_address'] = Session::get('ip_address');

        $transaction = Transactions::create($data);

        return $transaction;
    }

    public function getDomainDetails(){
        
    }
    public function advancedApiCall($url, $payload=[], $headers, $method = 'POST', $status_code=null)
    {
        \Log::info(['Begin connection...', $url, $payload, $headers]);
        
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
        \Log::info(['End connection', $url, $payload, $headers, $response]);
        
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

        // if (env('ENT') == 'local') {
        //     $path = "/Applications/MAMP/htdocs/kingsvtu/public";
        // } else {
        //     $path = '/home/kingntxc/portal.kingsvtu.ng';
        // }
        $path = public_path();
        // 
        if (!is_dir($path . '/' . $location)) {
            mkdir($path . '/' . $location, 0777, true);
        }

        return $location;
    }
}
