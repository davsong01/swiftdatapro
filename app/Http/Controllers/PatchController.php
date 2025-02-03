<?php

namespace App\Http\Controllers;

use App\Models\BillersLog;
use App\Models\Transaction;
use App\Models\Transactions;
use Illuminate\Http\Request;

class PatchController extends Controller
{
    public function updateBillersLogFromTransactions()
    {
        $transactions = Transactions::whereIn('provider_id', [2])
        ->whereIn('category_id', [4, 5,])
        ->whereNotNull('api_response')
        ->orderBy('created_at', 'desc')
        ->get();
        // dd($transactions);
        foreach ($transactions as $transaction) {
            $response = json_decode($transaction->api_response);
            
            if (isset($response->code) && $response->code === "000" && isset($response->Name)) {
                $transactionData = $response->content->transactions;
                
                BillersLog::updateOrCreate(
                    ['unique_element' => $transactionData->unique_element ?? null],
                    [
                        'unique_element' => $transactionData->unique_element ?? null,
                        'customer_name' => $response->Name ?? null,
                        'customer_address' => $response->Address ?? null,
                        'category_id' => $transaction->category_id ?? null,
                        'product_id' => $transaction->product_id ?? null,
                        'provider_id' => 2,
                        // 'raw_response' => json_encode($response),
                    ]
                );
            }
        }
        
    }
}
