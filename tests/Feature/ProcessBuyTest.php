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
    //         'amount' => 10
    //     ]);
        
    //     $response->assertStatus(200);
    //     $response->assertJson(['status' => 'success']);
    // }

    /** @test */
    // public function test_process_buy_with_insufficient_balance()
    // {
    //     $user = User::find(1);
    //     $product = Product::find(12);

    //     $response = $this->actingAs($user)->post('buy/airtime', [
    //         'product_id' => $product->id,
    //         'variation' => null,
    //         'quantity' => 1,
    //         'security_pin' => '12345',
    //         'mobile_no' => '08011111111',
    //         'amount' => 10
    //     ]);

    //     $response->assertStatus(200);
    //     $response->assertJson(['status' => 'fail', 'message' => 'Insufficient Wallet Balance, Please fund wallet and retry again']);
    // }

    /** @test */
    public function test_process_buy_with_locked_records()
    {
        if(env('ENT') == 'local'){
            $user = User::find(1);
            $product = Product::find(12);

            $sss = range(1,200);

            foreach($sss as $s){
                // Simulate multiple requests
                $this->actingAs($user)->post('buy/airtime', [
                    'product_id' => $product->id,
                    'variation' => null,
                    'quantity' => 1,
                    'security_pin' => '12345',
                    'mobile_no' => '08011111111',
                    'amount' => 10
                ]);
            }

            $response = $this->actingAs($user)->post('buy/airtime', [
                'product_id' => $product->id,
                'variation' => null,
                'quantity' => 1,
                'security_pin' => '12345',
                'mobile_no' => '08011111111',
                'amount' => 10
            ]);

            // Check that the second request does not proceed due to locked record
            $response->assertStatus(200);
            $response->assertJson(['status' => 'fail', 'message' => 'Transaction failed. Please try again.']);
        }
    }

    /** @test */
    // public function test_process_buy_with_invalid_product_or_variation()
    // {
    //     $user = User::factory()->create(['wallets' => 1000]); // Assume sufficient balance

    //     $response = $this->actingAs($user)->post('/process-buy', [
    //         'product_id' => 999, // Non-existing product
    //         'variation' => 999, // Non-existing variation
    //         'quantity' => 1,
    //         'security_pin' => '1234',
    //         'mobile_no' => '1234567890'
    //     ]);

    //     $response->assertStatus(200);
    //     $response->assertJson(['status' => 'fail', 'message' => 'Product or Variation not found!']);
    // }
}
