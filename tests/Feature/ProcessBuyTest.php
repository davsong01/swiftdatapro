<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessBuyTest extends TestCase
{
    // public function setUp(): void
    // {
    //     parent::setUp();
    //     // $this->artisan('db:seed'); // Ensure database is seeded if necessary
    // }

    /** @test */

    ## php artisan test --filter it_prevents_race_conditions_in_process_buy


    // public function test_process_buy_with_valid_data()
    // {
    //     $user = User::find(1);
    //     $product = Product::find(12);
    //     $variation = null;

    //     $response = $this->actingAs($user)->post('buy/airtime', [
    //         'product_id' => $product->id,
    //         'variation' => null,
    //         'quantity' => 1,
    //         'security_pin' => '12345',
    //         'mobile_no' => '08011111111',
    //         'amount' => 100
    //     ]);
        
    //     $response->assertStatus(200);
    //     $response->assertJson(['status' => 'success']);
    // }

    /** @test */
    public function test_process_buy_with_insufficient_balance()
    {
        if (env('ENT') != 'local') {
            return;
        }
        $user = User::find(1);
        $product = Product::find(12);

        $response = $this->actingAs($user)->post('buy/airtime', [
            'product_id' => $product->id,
            'variation' => null,
            'quantity' => 1,
            'security_pin' => '12345',
            'mobile_no' => '08011111111',
            'amount' => 100
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['status' => 'fail']);
    }
    /** @test */

    public function it_prevents_race_conditions_in_process_buy()
    {
        if(env('ENT') != 'local'){
            return;
        }
        $user = User::find(1);
        $product = Product::find(12);

        // Simulate request data
        $requestData = [
            'product_id' => $product->id,
            'variation' => null,
            'quantity' => 1,
            'security_pin' => '12345',
            'mobile_no' => '08011111111',
            'amount' => 100
        ];

        // Lock the user for update to prevent race conditions
        // DB::transaction(function () use ($user) {
        //     $user->lockForUpdate();
        // });
        $previousBalance = $user->wallets;

        $runs = 20;
        $responses = [];

        // Run multiple concurrent transactions
        $expectedDeduction = 0;

        for ($i=0; $i <= $runs; $i++) { 
            $expectedDeduction += $requestData['amount']; 
            $responses[] = $this->actingAs($user)->postJson('/buy/airtime', $requestData);          
        }

        // Wait for the responses
        $responses = collect($responses)->map(function ($response) {
            return $response->getOriginalContent();
        });

        // Refresh user balance from the database
        $user->refresh();
        $newBalance = $user->wallets;

        // Validate the responses
        foreach ($responses as $response) {
            // Assuming each response should indicate success
            $this->assertEquals('success', $response['status']);
        }

        $data = [
            'previous balance' => $previousBalance,
            'new balance' => $newBalance,
            'Difference' => $previousBalance - $newBalance,
            'expected deduction without commission' => $expectedDeduction,
        ];

        dd($data);
        // Ensure that the total amount charged does not exceed the previous balance
        $this->assertEquals($previousBalance - min($expectedDeduction, $previousBalance), $newBalance);
    }
}
