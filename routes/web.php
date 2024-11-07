<?php

use App\Models\AutomatedDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\Airtime2CashController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerLevelController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AutomatedDepositController;
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

// Route::get('dump', [FrontendController::class, 'dumpData']);
Route::get('populate', [Controller::class, 'populateData']);

Route::get('cron/synchronize', [Controller::class, 'synchronizeTransactions']);

Route::post('webhook_payment/{type?}', [PaymentGatewayController::class, 'analyzeWebhookPayment']);

Route::get('/', [FrontendController::class, 'index'])->name('welcome');
Route::get('/tac', [FrontendController::class, 'tac'])->name('tac');

Route::get('email/verify/{token}', [VerificationController::class, 'verifyAccount'])->name('user.verify');
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
    Route::get('api-balance/{api}', [ApiController::class, 'getBalance'])->name('api.balance');
    
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
        Route::resource('airtime2cash', Airtime2CashController::class);

        Route::resource('customerlevel', CustomerLevelController::class);
        Route::resource('notification', NotificationController::class);
        Route::resource('bank', BankController::class);
        Route::resource('blacklist', BlacklistController::class);
        Route::resource('accounts', AutomatedDepositController::class);
        Route::post('pull-reserved-account-by-reference', [AutomatedDepositController::class, 'pullMonnifyReservedAccountByReference'])->name('pull.reserved.account.by.reference');
        Route::post('add-manual-reserved-account', [AutomatedDepositController::class, 'store'])->name('add.manual.reserved.account');
        
        Route::resource('customers', CustomerController::class);
        Route::resource('staff', StaffController::class);

        Route::post('customer-update-account/{customer}', [CustomerController::class, 'updateCustomerAccount'])->name('account.update');
        Route::post('customer-update-products/{customer}', [CustomerController::class, 'updateCustomerProducts'])->name('products.update');
        Route::post('customer-other-actions', [CustomerController::class, 'updateCustomerOtherActions'])->name('customer.other.actions');
        
        Route::get('active-customers', [CustomerController::class, 'activeCustomers'])->name('customers.active');
        Route::get('inactive-customers', [CustomerController::class, 'inactiveCustomers'])->name('customers.inactive');
        Route::get('pending-kyc', [CustomerController::class, 'pendingKYC'])->name('pending.kyc');
        Route::get('view-single-pending-kyc/{id}', [CustomerController::class, 'viewPendingKYC'])->name('kyc.view');

        Route::post('update-single-pending-kyc/{id}', [CustomerController::class, 'updatePendingKYC'])->name('pending.kyc.update');

        Route::any('approve-kyc/{id?}', [CustomerController::class, 'approvePendingKYC'])->name('approve.kyc');
        Route::any('decline-kyc/{id?}', [CustomerController::class, 'declinePendingKYC'])->name('decline.kyc');
        
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
        Route::get('admin-deposit-transactions', [TransactionsController::class, 'adminDepositTransactions'])->name('admin.deposit.transactions');
        Route::get('single-transaction-view/{transaction}', [TransactionsController::class, 'singleTransactionView'])->name('admin.single.transaction.view');
        Route::get('re-query-transactions/{transaction}', [TransactionsController::class, 'requery'])->name('re-query-transaction');
        Route::get('reverse-to-wallet/{transaction}', [TransactionsController::class, 'reverseToWallet'])->name('reverse-to-wallet');
        Route::post('resolve-transaction/{transaction}', [TransactionsController::class, 'resolveTransaction'])->name('resolve-transaction');

        Route::get('download-transaction-receipt/{transaction_id}', [TransactionsController::class, 'transactionReceipt'])->name('transaction.receipt.download');
        Route::get('profile', [ProfileController::class, 'showProfilePage'])->name('admin.profile');
        Route::get('post', [ProfileController::class, 'updateProfilePage'])->name('admin.profile.update');
    });
    
});

Auth::routes();

Route::middleware(['auth', 'verify_email', 'ipcheck'])->group(function () {
    Route::get('kyc-update', [HomeController::class, 'kycForm'])->name('kyc.form');
    Route::post('kyc-update', [HomeController::class, 'kycUpdate'])->name('kyc.update');
    Route::get('profile', [HomeController::class, 'profile'])->name('customer.profile');
    Route::post('profile', [HomeController::class, 'updateProfile'])->name('customer.profile.update');
    
    Route::middleware(['auth', 'verify_email', 'ipcheck', 'kyc'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::post('customer/review', [HomeController::class, 'customerReview'])->name('customer.review');
        Route::post('customer/level', [HomeController::class, 'upgradeLevel'])->name('customer.level');
        Route::get('/automated_pay', [HomeController::class, 'automatedTransfer'])->name('automated.pay');
        Route::get('/deposit_payment', [HomeController::class, 'depositPayment']);
        Route::get('transactions/{type?}', [HomeController::class, 'transactions'])->name('customer.transactions');
        Route::get('cashback_earn', [HomeController::class, 'cashbackEarned'])->name('customer.cashback');
        Route::get('withdraw-cashback', [HomeController::class, 'withdrawCashbackEarned'])->name('customer.withdraw.cashback');
        Route::get('wallet_summary', [HomeController::class, 'walletSummary']);
        Route::get('sales_analysis', [HomeController::class, 'salesAnalysis']);
        Route::get('ref_downlines', [HomeController::class, 'downlines']);
    
        Route::get('generate-api', [HomeController::class, 'generateApi'])->name('generate_api');
    
        Route::get('change-password', [HomeController::class, 'passwordChange'])->name('password.change');
        Route::post('change-password', [HomeController::class, 'processPasswordChange'])->name('process.password.change');
    
        Route::get('change-pin/{type?}', [HomeController::class, 'resetPin'])->name('pin.reset');
        Route::post('change-pin', [HomeController::class, 'processResetPin'])->name('pin.process.reset');
        Route::get('confirm_reset_pin', [HomeController::class, 'resetPin'])->name('confirm.pin.reset');
        Route::post('reset_pin_final', [HomeController::class, 'finalProcessPin'])->name('final.pin.reset');
        
        Route::get('confirm_reset_pin', [HomeController::class, 'resetPin2']);

        Route::middleware(['transaction-pin'])->group(function () {
        // Transactions
            Route::get('buy/{service?}', [TransactionsController::class, 'initialize'])->name('buy');
            Route::post('buy/{service?}', [TransactionsController::class, 'processBuy'])->name('process.buy')->middleware('transaction.throttle');
            Route::post('customer-initialize-airtime2cash-transaction', [TransactionsController::class, 'initializeAirtime2CashTransaction'])->name('initialize.airtime2cashtransaction');
            Route::get('variations/{service?}', [TransactionsController::class, 'variations'])->name('variations');
            Route::get('discounts/{service?}', [TransactionsController::class, 'discounts'])->name('discounts');
            Route::post('verify/{service?}', [TransactionsController::class, 'verify'])->name('verify');
            Route::get('customer-get-variations/{product}', [VariationController::class, 'getCustomerVariations'])->name('get.customer.variations');
            Route::post('customer-get-discount', [TransactionsController::class, 'getCustomerDiscount'])->name('get.customer.discount');
            Route::post('customer-get-charges', [TransactionsController::class, 'getCustomerCharges'])->name('get.customer.charges');
            Route::get('customer-transaction_status/{transaction_id}', [TransactionsController::class, 'transactionStatus'])->name('transaction.status');
            Route::get('download-transaction-receipt/{transaction_id}', [TransactionsController::class, 'transactionReceipt'])->name('transaction.receipt.download');
        });
    });

});