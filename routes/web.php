<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontendController::class, 'index']);
Route::get('/tac', [FrontendController::class, 'tac'])->name('tac');

Route::get('email/verify/{token}', [VerificationController::class, 'verifyAccount'])->name('user.verify')->middleware(['auth']);

Route::get('/resendverification/{email}', [RegisterController::class, 'resendEmailVerificationLink'])->name('resend.verification.link');
Route::get('reset-password', function(){
    return view('reset-password-view');
});


Route::group(['prefix' => 'admin5609', 'before' => 'admin5609'],function () {

});

Auth::routes();
Route::middleware(['auth', 'verify_email', 'ipcheck'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    // Route::get('/home', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('customer/review', [HomeController::class, 'customerReview'])->name('customer.review');
    Route::post('customer/level', [HomeController::class, 'upgradeLevel'])->name('customer.level');
    Route::get('/automated_pay', [HomeController::class, 'automatedTransfer'])->name('automated.pay');
    Route::get('/deposit_payment', [HomeController::class, 'depositPayment']);
    Route::get('transactions/{type?}', [HomeController::class, 'transactions']);
    Route::get('customer/transactions', [HomeController::class, 'customerTransactions']);
    Route::get('reference/{reference?}', [HomeController::class, 'customerTransactions']);
    Route::get('wallet_summary', [HomeController::class, 'walletSummary']);
    Route::get('sales_analysis', [HomeController::class, 'salesAnalysis']);
    Route::get('ref_downlines', [HomeController::class, 'downlines']);
    Route::get('cashback_earn', [HomeController::class, 'cashbackEarned']);
    Route::get('profile', [HomeController::class, 'profile']);
    Route::get('change-password', [HomeController::class, 'passwordChange'])->name('password.change');
    Route::post('change-password', [HomeController::class, 'processPasswordChange'])->name('process.password.change');

    Route::get('change-pin', [HomeController::class, 'resetPin'])->name('pin.reset');
    Route::post('change-pin', [HomeController::class, 'processResetPin'])->name('pin.process.reset');
    Route::get('confirm_reset_pin', [HomeController::class, 'resetPin'])->name('confirm.pin.reset');
    Route::post('reset_pin_final', [HomeController::class, 'finalProcessPin'])->name('final.pin.reset');
    
    Route::get('confirm_reset_pin', [HomeController::class, 'resetPin2']);
    
    
    // Route::get('forgot-password', [HomeControllerController::class, 'create'])->name('password.request');
    // Route::post('forgot-password', [HomeControllerController::class, 'store'])->name('password.email');
    // Route::get('reset-password/{token}', [HomeController::class, 'create'])->name('password.reset');
    // Route::post('reset-password', [HomeController::class, 'updatePassword'])->name('password.update');

    // Transactions
    Route::get('buy/{service?}', [TransactionsController::class, 'initialize'])->name('buy');
    Route::post('buy/{service?}', [TransactionsController::class, 'processBuy'])->name('process.buy');
    Route::get('variations/{service?}', [TransactionsController::class, 'variations'])->name('variations');
    Route::get('discounts/{service?}', [TransactionsController::class, 'discounts'])->name('discounts');
    Route::post('verify/{service?}', [TransactionsController::class, 'verify'])->name('verify');



    
});


// Auth::routes(['verify' => true]);