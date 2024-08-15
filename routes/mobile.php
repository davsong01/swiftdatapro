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
            Route::get('app-settings', 'getAppSettings');

            // Route::post('customer/review', [HomeController::class, 'customerReview']);
            Route::get('get-downlines', 'downlines');
            Route::get('get-account-numbers', 'getAccountnumbers');
            
            Route::post('change-pin', 'processResetPin');
            Route::post('reset_pin_final', 'finalProcessPin')->name('mobile.final.pin.reset');
            Route::get('confirm_reset_pin', 'resetPin2');
            Route::post('profile', 'updateProfile')->name('mobile.customer.profile.update');
            Route::post('upgrade-level', 'upgradeLevel')->name('mobile.customer.level');
            Route::get('customer-levels', 'customerLevels');

            Route::get('withdraw-cashback', 'withdrawCashbackEarned')->name('mobile.customer.withdraw.cashback');
            Route::post('kyc-update', 'kycUpdate')->name('mobile.kyc.update');
            Route::post('change-password', 'processPasswordChange')->name('mobile.process.password.change');

            // // Transactions
            Route::get('get-products/{service?}', 'initialize');
            Route::post('buy/{service?}', 'processBuy');
            // Route::post('customer-initialize-airtime2cash-transaction', [TransactionsController::class, 'initializeAirtime2CashTransaction'])->name('initialize.airtime2cashtransaction');
            Route::post('verify/{service?}', 'verify')->name('verify');
            Route::get('get-variations/{product}',  'getCustomerVariations');
            Route::post('get-discount', 'getCustomerDiscount');
            Route::post('get-charges', 'getCustomerCharges');
            Route::get('get-transaction-status/{transaction_id}', 'transactionStatus');
            Route::get('get-transactions/{type?}', 'transactions');
            Route::get('commissions', 'cashbackEarned');
        });
    });

});
