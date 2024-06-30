<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\MobileController;

Route::group(['prefix' => 'v1', 'before' => 'v1'], function () {
    Route::controller(MobileController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        
        Route::middleware(['mobile','verify_email', 'ipcheck'])->group(function () {
            Route::get('get-user-details', 'userDetails');
            Route::get('get-menus', 'getMenu');
            Route::get('get-customer-levels', 'getCustomerLevels');

            // Route::post('customer/review', [HomeController::class, 'customerReview']);
            Route::get('get-automated-pay', 'automatedTransfer')->name('automated.pay');
            Route::get('get-transactions/{type?}', 'transactions');
            Route::get('get-downlines', 'downlines');
            
            // Route::get('change-pin', [HomeController::class, 'resetPin'])->name('pin.reset');
            // Route::post('change-pin', [HomeController::class, 'processResetPin'])->name('pin.process.reset');
            // Route::get('confirm_reset_pin', [HomeController::class, 'resetPin'])->name('confirm.pin.reset');
            // Route::post('reset_pin_final', [HomeController::class, 'finalProcessPin'])->name('final.pin.reset');
            // Route::get('confirm_reset_pin', [HomeController::class, 'resetPin2']);
            // Route::get('/deposit_payment', [HomeController::class, 'depositPayment']);
            // Route::post('profile', [HomeController::class, 'updateProfile'])->name('customer.profile.update');

            // Route::get('cashback_earn', [HomeController::class, 'cashbackEarned'])->name('customer.cashback');
            // Route::get('withdraw-cashback', [HomeController::class, 'withdrawCashbackEarned'])->name('customer.withdraw.cashback');
            // Route::get('generate-api-keys', [HomeController::class, 'generateApi'])->name('generate_api');
            // Route::get('kyc-update', [HomeController::class, 'kycForm'])->name('kyc.form');
            // Route::post('kyc-update', [HomeController::class, 'generateApi'])->name('kyc.update');

            // Route::get('change-password', [HomeController::class, 'passwordChange'])->name('password.change');
            // Route::post('change-password', [HomeController::class, 'processPasswordChange'])->name('process.password.change');


            // // Transactions
            Route::get('get-products/{service?}', 'initialize');
            Route::post('buy/{service?}', 'processBuy');
            // Route::post('customer-initialize-airtime2cash-transaction', [TransactionsController::class, 'initializeAirtime2CashTransaction'])->name('initialize.airtime2cashtransaction');
            // Route::post('verify/{service?}', [TransactionsController::class, 'verify'])->name('verify');
            Route::get('get-variations/{product}',  'getCustomerVariations');
            Route::post('get-discount', 'getCustomerDiscount');
            Route::post('get-charges', 'getCustomerCharges');
            Route::get('get-transaction-status/{transaction_id}', 'transactionStatus');
        });
    });

});
