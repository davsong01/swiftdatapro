<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerLevelController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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
Route::get('populate', [Controller::class, 'populateData']);

Route::get('/', [FrontendController::class, 'index'])->name('welcome');
Route::get('/tac', [FrontendController::class, 'tac'])->name('tac');

Route::get('email/verify/{token}', [VerificationController::class, 'verifyAccount'])->name('user.verify')->middleware(['auth']);

Route::get('/resendverification/{email}', [RegisterController::class, 'resendEmailVerificationLink'])->name('resend.verification.link');
// Route::get('reset-password', function(){
//     return view('reset-password-view');
// });

// For version one users
Route::get('get-new-password/{user_id}', [LoginController::class, 'resetNewPassword'])->name('password.reset.new');
Route::post('get-new-password', [LoginController::class, 'processResetNewPassword'])->name('process.password.reset.new');

Route::get('reset-password', [LoginController::class, 'changePassword'])->name('password.reset.change');
Route::post('reset-password', [LoginController::class, 'processChangePassword'])->name('password.reset.change.process'); // Throttle this link to once in 2 minutes
// end version one users

Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forget.password.get');
Route::post('forgot-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
// Route::get('reset-password/{token}', [HomeController::class, 'create'])->name('password.reset');
// Route::post('reset-password', [HomeController::class, 'updatePassword'])->name('password.update');

// Admin users
Route::group(['prefix' => 'swt5609', 'before' => 'swt5609'],function () {
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin-login', [AdminAuthController::class, 'login'])->name('admin.login.process');
    Route::post('/admin-logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth.admin', 'adminRoute'])->group(function () {
        Route::get('/admin-dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('settings-update', [WebSettingController::class, 'edit'])->name('settings.edit');
        Route::post('settings-update', [WebSettingController::class, 'update'])->name('settings.update');

        Route::resource('role', RoleController::class);
        Route::resource('permission', RolePermissionController::class);
        Route::resource('api', ApiController::class);
        Route::resource('paymentgateway', PaymentGatewayController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('product', ProductController::class);
        Route::resource('customerlevel', CustomerLevelController::class);
        Route::resource('notification', NotificationController::class);
        Route::resource('bank', BankController::class);
        Route::resource('blacklist', BlacklistController::class);
    
        Route::get('duplicate-product/{product}', [ProductController::class, 'duplicateProduct'])->name('duplicate.product');

        Route::get('pull-old-variations/{product}', [VariationController::class, 'pullOldVariations'])->name('variations.old.pull');
        Route::get('pull-variations/{product}', [VariationController::class, 'pullVariations'])->name('variations.pull');
        Route::post('update-variations/{product}', [VariationController::class, 'updateVariations'])->name('variations.update');
        Route::post('manual-variations-add/{product}', [VariationController::class, 'addManualVariations'])->name('manual.variations.add');
        Route::get('delete-variations/{variation}', [VariationController::class, 'deleteVariations'])->name('variation.delete');

        Route::get('credit-customer', [TransactionsController::class, 'creditCustomerPage'])->name('admin.credit.customer');
        Route::get('debit-customer', [TransactionsController::class, 'debitCustomerPage'])->name('admin.debit.customer');
        Route::post('process-credit-debit', [TransactionsController::class, 'processCreditDebit'])->name('admin.process.credit.debit');
        Route::get('admin-transactions', [TransactionsController::class, 'adminTransactions'])->name('admin.transactions');
        Route::get('single-transaction-view/{transaction}', [TransactionsController::class, 'singleTransactionView'])->name('admin.single.transaction.view');
        Route::get('download-transaction-receipt/{transaction_id}', [TransactionsController::class, 'transactionReceipt'])->name('transaction.receipt.download');

    });
    
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
    Route::get('generate-api', [HomeController::class, 'generateApi'])->name('generate_api');

    
    Route::get('change-password', [HomeController::class, 'passwordChange'])->name('password.change');
    Route::post('change-password', [HomeController::class, 'processPasswordChange'])->name('process.password.change');

    Route::get('change-pin', [HomeController::class, 'resetPin'])->name('pin.reset');
    Route::post('change-pin', [HomeController::class, 'processResetPin'])->name('pin.process.reset');
    Route::get('confirm_reset_pin', [HomeController::class, 'resetPin'])->name('confirm.pin.reset');
    Route::post('reset_pin_final', [HomeController::class, 'finalProcessPin'])->name('final.pin.reset');
    
    Route::get('confirm_reset_pin', [HomeController::class, 'resetPin2']);
    
    // Transactions
    Route::get('buy/{service?}', [TransactionsController::class, 'initialize'])->name('buy');
    Route::post('buy/{service?}', [TransactionsController::class, 'processBuy'])->name('process.buy');
    Route::get('variations/{service?}', [TransactionsController::class, 'variations'])->name('variations');
    Route::get('discounts/{service?}', [TransactionsController::class, 'discounts'])->name('discounts');
    Route::post('verify/{service?}', [TransactionsController::class, 'verify'])->name('verify');
    Route::get('customer-get-variations/{product}', [VariationController::class, 'getCustomerVariations'])->name('get.customer.variations');
    Route::post('customer-get-discount', [TransactionsController::class, 'getCustomerDiscount'])->name('get.customer.discount');
});

// Auth::routes(['verify' => true]);