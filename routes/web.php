<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
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

    
    
    
});


// Auth::routes(['verify' => true]);