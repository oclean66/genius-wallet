<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\Merchant\LoginController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\WithdrawalController;
use App\Http\Controllers\Merchant\AuthorizationController;


// ************************** ADMIN SECTION START ***************************//

Route::prefix('merchant')->name('merchant.')->middleware('maintenance')->group(function () {

    Route::get('/register',            [LoginController::class,'registerForm'])->name('register');
    Route::post('/register',            [LoginController::class,'register']);
    Route::get('/login',            [LoginController::class,'showLoginForm'])->name('login');
    Route::post('/login',           [LoginController::class,'login']);
    Route::get('/forgot-password',   [LoginController::class,'forgotPasswordForm'])->name('forgot.password');
    Route::post('/forgot-password',   [LoginController::class,'forgotPasswordSubmit']);

    Route::get('forgot-password/verify-code',     [LoginController::class,'verifyCode'])->name('verify.code');
    Route::post('forgot-password/verify-code',     [LoginController::class,'verifyCodeSubmit']);

    Route::get('reset-password',     [LoginController::class,'resetPassword'])->name('reset.password');
    Route::post('reset-password',     [LoginController::class,'resetPasswordSubmit']);

    Route::get('verify-email',     [AuthorizationController::class,'verifyEmail'])->name('verify.email')->middleware('auth:merchant');
    Route::post('verify-email',     [AuthorizationController::class,'verifyEmailSubmit'])->middleware('auth:merchant');

    Route::get('resend/verify-email/code',     [AuthorizationController::class,'verifyEmailResendCode'])->name('verify.email.resend')->middleware('auth:merchant');

    Route::get('two-step/verification',     [AuthorizationController::class,'twoStep'])->name('two.step.verification')->middleware('auth:merchant');

    Route::post('two-step/verification',     [AuthorizationController::class,'twoStepVerify'])->middleware('auth:merchant');

    Route::get('resend/two-step/verify-code', [AuthorizationController::class,'twoStepResendCode'])->name('two.step.resend')->middleware('auth:merchant');

    Route::get('/logout',[LoginController::class,'logout'])->name('logout')->middleware('auth:merchant');

    Route::middleware(['auth:merchant','merchant_email_verify','twostep'])->group(function(){
        Route::get('transaction/details/{id}', [MerchantController::class,'trxDetails'])->name('trx.details');

        Route::get('/profile-setting', [MerchantController::class,'profileSetting'])->name('profile.setting');
        Route::post('/profile-setting', [MerchantController::class,'profileUpdate']);

        Route::get('/change-password', [MerchantController::class,'changePassword'])->name('change.password');
        Route::post('/change-password', [MerchantController::class,'updatePassword']);

        Route::get('/two-step/authentication', [MerchantController::class,'twoStep'])->name('two.step');
        Route::get('/two-step/verify', [MerchantController::class,'twoStepVerifyForm'])->name('two.step.verify');
        Route::post('/two-step/verify', [MerchantController::class,'twoStepVerifySubmit']);
        Route::post('/two-step/authentication', [MerchantController::class,'twoStepSendCode']);


        Route::get('/', [MerchantController::class,'dashboard'])->name('dashboard');
        Route::get('/generate-qrcode', [MerchantController::class,'generateQR'])->name('qr');

         //withdraw
         Route::get('withdraw-money',     [WithdrawalController::class,'withdrawForm'])->name('withdraw.form')->middleware(['module','kyc']);
         Route::post('withdraw-money',    [WithdrawalController::class,'withdrawSubmit'])->middleware(['module','kyc']);

         Route::get('withdraw-methods',   [WithdrawalController::class,'methods'])->name('withdraw.methods');
         Route::get('withdraw-history',   [WithdrawalController::class,'history'])->name('withdraw.history');

         Route::get('api-key',            [MerchantController::class,'apiKeyForm'])->name('api.key.form');
         Route::post('generate-api-key',  [MerchantController::class,'apiKeyGenerate'])->name('api.key.generate');
         Route::get('service-mode',       [MerchantController::class,'serviceMode'])->name('api.service.mode');

         Route::get('transactions',        [MerchantController::class,'transactions'])->name('transactions');
         Route::get('download-qr/{email}', [MerchantController::class,'downloadQR'])->name('download.qr');

        //kyc form
        Route::get('kyc-form',    [MerchantController::class,'kycForm'])->name('kyc.form');
        Route::post('kyc-form',   [MerchantController::class,'kycFormSubmit']);

        Route::get('module/{module}',   [MerchantController::class,'moduleOff'])->name('module.off');

        // support ticket
        Route::get('support/tickets',           [SupportTicketController::class,'index'])->name('ticket.index');
        Route::post('open/support/tickets',     [SupportTicketController::class,'openTicket'])->name('ticket.open');
        Route::post('reply/ticket/{ticket_num}',[SupportTicketController::class,'replyTicket'])->name('ticket.reply');
    });


});
